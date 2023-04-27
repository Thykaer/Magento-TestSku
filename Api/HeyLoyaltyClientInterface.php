<?php

namespace Wexo\HeyLoyalty\Api;

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
     * @param int $listId
     * @return array
     */
    public function fetchList(int $listId): array;

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
     * @param int $memberId
     * @return array
     */
    public function fetchListMember(int $listId, int $memberId): array;

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
     * @param int $memberId
     * @param array $params
     * @return array
     */
    public function editListMember(int $listId, int $memberId, array $params): array;

    /**
     * Edit a member for a list. Get which params to post by calling fetchList() first
     * Updates only field values sent in params without affecting the others
     *
     * @param int $listId
     * @param int $memberId
     * @param array $params
     * @return array
     */
    public function patchListMember(int $listId, int $memberId, array $params): array;

    /**
     * Deletes a list member
     *
     * @param int $listId
     * @param int $memberId
     * @return array
     */
    public function deleteListMember(int $listId, int $memberId): array;

    /**
     * Import list members from a file. File must be CSV format. Refer to HeyLoyalty API for different kind of fields
     *
     * @param int $listId
     * @param string $fileName
     * @param string $filePath
     * @param array $fields
     * @param string $dateFormat
     * @param int $skipHeaderLine
     * @param int $triggerAutoresponder
     * @param int $triggerOptin
     * @param string $handleExisting
     * @param int $emptyField
     * @param string $duplicateField
     * @param string $sendErrorsTo
     * @param string $delimiter
     * @return array
     */
    public function importListMembers(
        int $listId,
        string $fileName,
        string $filePath,
        array $fields = ['email'], // Which fields the import file contains
        string $dateFormat = 'd-m-Y', // Date format for all dates in import file
        int $skipHeaderLine = 0, // Set to 1 if import file has header line (skip first line)
        int $triggerAutoresponder = 0,
        int $triggerOptin = 0,
        string $handleExisting = 'ignore', // 'update' = create + update, 'updateOnly' = update, 'ignore' = create
        int $emptyField = 0, // Set to 1 to delete existing data not included in import file
        string $duplicateField = 'email', // Which file that determines if two entries is duplicates
        string $sendErrorsTo = 'mkk@wexo.dk', // Email to send errors to
        string $delimiter = ';' // Which character to separate columns by. Any combo of , ; | :
    ): array;

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
     * Send a request to the HeyLoyalty API. Returns [] on all errors.
     *
     * @param string $endpoint
     * @param string $method
     * @param array $payload
     * @param array $multipart
     * @return array
     */
    public function request(
        string $endpoint,
        string $method = 'GET',
        array $payload = [],
        array $multipart = []
    ): array;
}
