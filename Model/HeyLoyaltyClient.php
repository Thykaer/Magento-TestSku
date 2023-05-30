<?php

namespace Wexo\HeyLoyalty\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManager;
use Psr\Log\LoggerInterface;
use Throwable;
use Wexo\HeyLoyalty\Api\HeyLoyaltyClientInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class HeyLoyaltyClient implements HeyLoyaltyClientInterface
{
    public const BASE_URI = 'https://api.heyloyalty.com/loyalty/v1/';
    public const BASE_URI_V2 = 'https://api.heyloyalty.com/loyalty/v2/';
    public const BI_URI = 'https://bi.heyloyalty.com/api/';
    public const EXPORT_CSV_URL = 'wexo_heyloyalty/purchasehistory/csvexport';

    /**
     * @param Client $client
     * @param HeyLoyaltyConfigInterface $config
     * @param Json $json
     * @param LoggerInterface $logger
     * @param StoreManager $storeManager
     */
    public function __construct(
        public Client $client,
        public HeyLoyaltyConfigInterface $config,
        public Json $json,
        public LoggerInterface $logger,
        public StoreManager $storeManager
    ) {
    }

    /**
     * Fetch all lists from HeyLoyalty API
     *
     * @return array
     */
    public function fetchLists(): array
    {
        return $this->vOneRequest("lists");
    }

    /**
     * Fetch a single list from HeyLoyalty API
     *
     * @param string $listId
     * @return array
     */
    public function fetchList(string $listId): array
    {
        return $this->vOneRequest("lists/{$listId}");
    }

    /**
     * Create a list. Refer to HeyLoyalty API for different kinds of fields
     *
     * @param string $name
     * @param int $countryId
     * @param array $fields
     * @param string $allowDuplicates
     * @return array
     */
    public function createList(
        string $name,
        int $countryId,
        array $fields,
        string $allowDuplicates = 'disallow'
    ): array {
        $payload = [
            'name' => $name,
            'country_id' => $countryId,
            'duplicates' => $allowDuplicates,
            'fields' => $fields
        ];
        return $this->vOneRequest("lists", 'POST', $payload);
    }

    /**
     * Edit a single list. Overrides all fields; fields not specified as parameter will be deleted
     *
     * @param int $listId
     * @param string $name
     * @param int $countryId
     * @param array $fields
     * @param string $allowDuplicates
     * @return array
     */
    public function editList(
        int $listId,
        string $name,
        int $countryId,
        array $fields,
        string $allowDuplicates = 'disallow'
    ): array {
        $payload = [
            'name' => $name,
            'country_id' => $countryId,
            'duplicates' => $allowDuplicates,
            'fields' => $fields
        ];
        return $this->vOneRequest("lists/{$listId}", 'PUT', $payload);
    }

    /**
     * Delete a list. Deletes all members on the list and all campaigns and automations sent from that list as well
     *
     * @param int $listId
     * @return array
     */
    public function deleteList(int $listId): array
    {
        return $this->vOneRequest("lists/{$listId}", 'DELETE');
    }

    /**
     * Fetch members of a single list from HeyLoyalty API
     *
     * @param int $listId
     * @param int $page
     * @param int $perPage
     * @param string $orderBy
     * @param int $order
     * @param string $filter
     * @param string $logic
     * @return array
     */
    public function fetchListMembers(
        int $listId,
        int $page = 1,
        int $perPage = 50,
        string $orderBy = 'created_at',
        int $order = 1, // Ascending = 1, Descending = -1
        string $filter = '', // Refer to HeyLoyalty API for filtering
        string $logic = 'and' // Filtering logic to apply with multiple filters ("and"/"or")
    ): array {
        $payload = [
            'page' => $page,
            'perpage' => $perPage,
            'orderby' => $orderBy,
            'order' => $order,
            'logic' => $logic
        ];
        if ($filter !== '') {
            $payload['filter'] = $filter;
        }
        return $this->vOneRequest("lists/{$listId}/members", 'GET', $payload);
    }

    /**
     * Fetch a single member from a single list from HeyLoyalty API
     *
     * @param int $listId
     * @param string $memberId
     * @return array
     */
    public function fetchListMember(int $listId, string $memberId): array
    {
        return $this->vOneRequest("lists/{$listId}/members/{$memberId}");
    }

    /**
     * Create a new member for a list. Get which params to post by calling fetchList() first
     *
     * @param int $listId
     * @param array $params
     * @return array
     */
    public function createListMember(int $listId, array $params): array
    {
        return $this->vOneRequest("lists/{$listId}/members", 'POST', $params);
    }

    /**
     * Edit a member for a list. Get which params to post by calling fetchList() first
     * Overrides all field values not in the params; field values not in params will be deleted
     *
     * @param int $listId
     * @param string $memberId
     * @param array $params
     * @return array
     */
    public function editListMember(int $listId, string $memberId, array $params): array
    {
        return $this->vOneRequest("lists/{$listId}/members/{$memberId}", 'PUT', $params);
    }

    /**
     * Edit a member for a list. Get which params to post by calling fetchList() first
     * Updates only field values sent in params without affecting the others
     *
     * @param int $listId
     * @param string $memberId
     * @param array $params
     * @return array
     */
    public function patchListMember(int $listId, string $memberId, array $params): array
    {
        return $this->vOneRequest("lists/{$listId}/members/{$memberId}", 'PATCH', $params);
    }

    /**
     * Deletes a list member
     *
     * @param int $listId
     * @param string $memberId
     * @return array
     */
    public function deleteListMember(int $listId, string $memberId): array
    {
        return $this->vOneRequest("lists/{$listId}/members/{$memberId}", 'DELETE');
    }

    public function deleteListMemberByEmail(int $listId, string $email): array
    {
        return $this->request(self::BASE_URI_V2, "lists/members/byfield", 'DELETE', [
            "field" => "email",
            "value" => $email,
            "lists" => [$listId]
        ]);
    }
    /**
     * Move members to another list
     *
     * @param int $sourceListId
     * @param int $targetListId
     * @param array $members
     * @param string $action
     * @param string $duplicatesField
     * @param string $duplicatesAction
     * @return array
     */
    public function moveListMembers(
        int $sourceListId,
        int $targetListId,
        array $members, // Array of memberId's
        string $action = 'copy', // Can be 'copy' or 'move'
        string $duplicatesField = 'both', // Can be 'email', 'mobile' or 'both'
        string $duplicatesAction = 'patch' // 'patch'=keep existing, 'update'=delete existing, 'skip'=skip duplicates
    ): array
    {
        $payload = [
            'listId' => $targetListId,
            'handleDuplicates[field]' => $duplicatesField,
            'handleDuplicates[action]' => $duplicatesAction,
            'members' => $members
        ];
        $i = 0;
        foreach ($members as $member) {
            //$payload['members[' . $i . ']'] = $member;
            $i++;
        }
        return $this->vOneRequest("lists/{$sourceListId}/members/bulk/{$action}", 'POST', $payload);
    }

    /**
     * Fetch product feeds from HeyLoyalty API
     *
     * @return array
     */
    public function fetchProductfeeds(): array
    {
        return $this->vOneRequest('integrations/productfeed');
    }

    /**
     * Add a new product feed
     *
     * @param string $name
     * @param string $url
     * @return array
     */
    public function createProductfeed(string $name, string $url): array
    {
        $payload = [
            'name' => $name,
            'url' => $url
        ];
        return $this->vOneRequest('integrations/productfeed', 'POST', $payload);
    }

    /**
     * Update a product feed
     *
     * @param int $feedId
     * @param string $name
     * @param string $url
     * @return array
     */
    public function editProductfeed(int $feedId, string $name, string $url): array
    {
        $payload = [
            'name' => $name,
            'url' => $url
        ];
        return $this->vOneRequest("integrations/productfeed/{$feedId}", 'PUT', $payload);
    }

    /**
     * Delete a product feed
     *
     * @param int $feedId
     * @return array
     */
    public function deleteProductfeed(int $feedId): array
    {
        return $this->vOneRequest("integrations/productfeed/{$feedId}", 'DELETE');
    }

    /**
     * Fetch mapping for a product feed
     *
     * @param int $feedId
     * @return array
     */
    public function fetchProductfeedMapping(int $feedId): array
    {
        return $this->vOneRequest("integrations/productfeed-mapping/{$feedId}");
    }

    /**
     * Create a product feed mapping
     *
     * @param int $feedId
     * @param string $feedType
     * @param string $searchField
     * @param int $productId
     * @param string $name
     * @param string $url
     * @param string $originalPrice
     * @param string $salePrice
     * @param string $discount
     * @param string $description
     * @param string $imageUrl
     * @param string $currency
     * @param string $categoryName
     * @param string $categoryId
     * @param string $inStock
     * @param string $customField1
     * @param string $customField2
     * @param string $customField3
     * @param string $customField4
     * @param string $customField5
     * @return array
     */
    public function createProductfeedMapping(
        int $feedId,
        string $feedType,
        string $searchField,
        int $productId,
        string $name,
        string $url = '',
        string $originalPrice = '',
        string $salePrice = '',
        string $discount = '',
        string $description = '',
        string $imageUrl = '',
        string $currency = '',
        string $categoryName = '',
        string $categoryId = '',
        string $inStock = '',
        string $customField1 = '',
        string $customField2 = '',
        string $customField3 = '',
        string $customField4 = '',
        string $customField5 = '',
    ): array {
        $payload = [
            'product_feed_id' => $feedId,
            'feed_type' => $feedType,
            'search_field' => $searchField,
            'productId' => $productId,
            'name' => $name
        ];
        $notEmptyArgs = $this->getNotEmptyArguments(func_get_args(), 5);
        foreach ($notEmptyArgs as $key => $arg) {
            $payload[$key] = $arg;
        }
        return $this->vOneRequest('integrations/productfeed-mapping', 'POST', $payload);
    }

    /**
     * Update a product feed mapping
     *
     * @param int $feedId
     * @param string $feedType
     * @param string $searchField
     * @param int $productId
     * @param string $name
     * @param string $url
     * @param string $originalPrice
     * @param string $salePrice
     * @param string $discount
     * @param string $description
     * @param string $imageUrl
     * @param string $currency
     * @param string $categoryName
     * @param string $categoryId
     * @param string $inStock
     * @param string $customField1
     * @param string $customField2
     * @param string $customField3
     * @param string $customField4
     * @param string $customField5
     * @return array
     */
    public function editProductfeedMapping(
        int $feedId,
        string $feedType,
        string $searchField,
        int $productId,
        string $name,
        string $url = '',
        string $originalPrice = '',
        string $salePrice = '',
        string $discount = '',
        string $description = '',
        string $imageUrl = '',
        string $currency = '',
        string $categoryName = '',
        string $categoryId = '',
        string $inStock = '',
        string $customField1 = '',
        string $customField2 = '',
        string $customField3 = '',
        string $customField4 = '',
        string $customField5 = ''
    ): array {
        $payload = [
            'feed_type' => $feedType,
            'search_field' => $searchField,
            'productId' => $productId,
            'name' => $name
        ];
        $notEmptyArgs = $this->getNotEmptyArguments(func_get_args(), 5);
        foreach ($notEmptyArgs as $key => $arg) {
            $payload[$key] = $arg;
        }
        return $this->vOneRequest("integrations/productfeed-mapping/{$feedId}", 'PUT', $payload);
    }

    /**
     * Import purchase history from csv file. Refer to HeyLoyalty API for different kind of fields
     *
     * @param array $fields
     * @param string $dateFormat
     * @param bool $skipHeaderLine
     * @param string $sendErrorsTo
     * @param string $delimiter
     * @return array
     * @throws NoSuchEntityException
     */
    public function exportPurchaseHistory(
        string $file,
        string $trackingId,
        array $fields = [],
        string $sendErrorsTo = '',
        string $dateFormat = 'Y-m-d H:i:s',
        bool $skipHeaderLine = false,
        string $delimiter = ',',
    ): array {
        $payload = [
            'file' => $file,
            'date_format' => $dateFormat,
            'skip_header_line' => $skipHeaderLine,
            'sendErrorsTo' => $sendErrorsTo,
            'delimiter' => $delimiter,
            'fields_selected' => $fields,
        ];
        return $this->request("https://bi.heyloyalty.com/","api/booking/import/{$trackingId}", 'POST', $payload);
    }

    /**
     * Send a v1 request.
     *
     * @param string $endpoint
     * @param string $method
     * @param array $payload
     * @param bool $multipart
     * @return array
     */
    public function vOneRequest(
        string $endpoint,
        string $method = 'GET',
        array $payload = [],
        bool $multipart = false
    ): array {
        return $this->request(self::BASE_URI, $endpoint, $method, $payload, $multipart);
    }

    /**
     * Send a bi request
     *
     * @param string $endpoint
     * @param string $method
     * @param array $payload
     * @param bool $multipart
     * @return array
     */
    public function biRequest(
        string $endpoint,
        string $method = 'GET',
        array $payload = [],
        bool $multipart = false
    ): array {
        return $this->request(self::BI_URI, $endpoint, $method, $payload, $multipart);
    }

    /**
     * Send a request to the HeyLoyalty API. Returns [] on all errors.
     *
     * @param string $host
     * @param string $endpoint
     * @param string $method
     * @param array $payload
     * @param bool $multipart
     * @return array
     */
    public function request(
        string $host,
        string $endpoint,
        string $method = 'GET',
        array $payload = [],
        bool $multipart = false
    ): array {
        $requestTimestamp = gmdate("D, d M Y H:i:s") . ' GMT';
        $requestSignature = base64_encode(hash_hmac('sha256', $requestTimestamp, $this->config->getApiSecret()));
        $options = [
            'headers' => [
                'X-Request-Timestamp' => $requestTimestamp,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'auth' => [
                $this->config->getApiKey(),
                $requestSignature
            ]
        ];

        if ($method !== 'GET' || $payload) {
            $type = $multipart ? 'multipart' : 'json';
            $options[$type] = $payload;
        }
        try {
            $this->logger->debug('\Wexo\HeyLoyalty\Model\HeyLoyaltyClient::request - Request', [
                'host' => $host,
                'endpoint' => $endpoint,
                'method' => $method,
                'options' => $options
            ]);
            $response = $this->client->request(
                $method,
                $host . $endpoint,
                $options
            );
            $responseJson = $this->json->unserialize($response->getBody()->getContents());
            $this->logger->debug('\Wexo\HeyLoyalty\Model\HeyLoyaltyClient::request - Response', [
                'host' => $host,
                'endpoint' => $endpoint,
                'method' => $method,
                'payload' => $payload,
                'response' => $responseJson
            ]);
            return $responseJson;
        } catch (ClientException $e) {
            $response = $e?->getResponse();
            $this->logger->error('\Wexo\HeyLoyalty\Model\HeyLoyaltyClient::request Error',[
                'message' => $e->getMessage(),
                'body' => $response?->getBody()?->getContents()
            ]);
        } catch (Throwable $t) {
            $this->logger->error('\Wexo\HeyLoyalty\Model\HeyLoyaltyClient::request Error',[
                'message' => $t->getMessage()
            ]);
        }
        return [];
    }

    /**
     * Function designed to return the arguments that are not their default value
     *
     * @param array $args
     * @param int $shift
     * @return array
     */
    private function getNotEmptyArguments(array $args, int $shift): array
    {
        $notEmptyArgs = [];
        for ($i = 0; $i < $shift; $i++) {
            array_shift($args);
        }

        foreach ($args as $index => $arg) {
            // Only include arguments that are key-value pairs
            if ($index % 2 == 0 && isset($args[$index + 1])) {
                $key = $arg;
                $value = $args[$index + 1];

                if ($value !== '') {
                    $notEmptyArgs[$key] = $value;
                }
            }
        }
        return $notEmptyArgs;
    }
}
