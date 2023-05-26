<?php

namespace Wexo\HeyLoyalty\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface HeyLoyaltyClientInterface
{
    /**
     * Fetch all lists from HeyLoyalty API
     *
     * @return array
     */
    public function fetchLists(): array;

    /**
     * Fetch a single list from HeyLoyalty API
     *
     * @param string $listId
     * @return array
     */
    public function fetchList(string $listId): array;

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
    ): array;

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
    ): array;

    /**
     * Delete a list. Deletes all members on the list and all campaigns and automations sent from that list as well
     *
     * @param int $listId
     * @return array
     */
    public function deleteList(int $listId): array;

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
    ): array;

    /**
     * Fetch a single member from a single list from HeyLoyalty API
     *
     * @param int $listId
     * @param string $memberId
     * @return array
     */
    public function fetchListMember(int $listId, string $memberId): array;

    /**
     * Create a new member for a list. Get which params to post by calling fetchList() first
     *
     * @param int $listId
     * @param array $params
     * @return array
     */
    public function createListMember(int $listId, array $params): array;

    /**
     * Edit a member for a list. Get which params to post by calling fetchList() first
     * Overrides all field values not in the params; field values not in params will be deleted
     *
     * @param int $listId
     * @param string $memberId
     * @param array $params
     * @return array
     */
    public function editListMember(int $listId, string $memberId, array $params): array;

    /**
     * Edit a member for a list. Get which params to post by calling fetchList() first
     * Updates only field values sent in params without affecting the others
     *
     * @param int $listId
     * @param string $memberId
     * @param array $params
     * @return array
     */
    public function patchListMember(int $listId, string $memberId, array $params): array;

    /**
     * Deletes a list member
     *
     * @param int $listId
     * @param string $memberId
     * @return array
     */
    public function deleteListMember(int $listId, string $memberId): array;

    /**
     * Delete list member by email
     *
     * @param integer $listId
     * @param string $email
     * @return array
     */
    public function deleteListMemberByEmail(int $listId, string $email): array;

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
    ): array;

    /**
     * Fetch product feeds from HeyLoyalty API
     *
     * @return array
     */
    public function fetchProductfeeds(): array;

    /**
     * Add a new product feed
     *
     * @param string $name
     * @param string $url
     * @return array
     */
    public function createProductfeed(string $name, string $url): array;

    /**
     * Update a product feed
     *
     * @param int $feedId
     * @param string $name
     * @param string $url
     * @return array
     */
    public function editProductfeed(int $feedId, string $name, string $url): array;

    /**
     * Delete a product feed
     *
     * @param int $feedId
     * @return array
     */
    public function deleteProductfeed(int $feedId): array;

    /**
     * Fetch mapping for a product feed
     *
     * @param int $feedId
     * @return array
     */
    public function fetchProductfeedMapping(int $feedId): array;

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
    ): array;

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
        string $customField5 = '',
    ): array;

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
    ): array;

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
    ): array;

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
    ): array;

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
    ): array;
}
