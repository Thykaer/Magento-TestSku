<?php namespace Wexo\HeyLoyalty\Api;

interface HeyLoyaltyApiInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return array
     */
    public function getLists(): array;

    /**
     * Get a list from client
     *
     * @param string $id
     * @return array
     */
    public function getList(string $id): array;

    /**
     * Get from config if tracking is activated
     *
     * @return bool
     */
    public function getIsTrackingActivated(): bool;

    /**
     * Get tracking id from config
     *
     * @return string
     */
    public function getTrackingId(): string;

    public function exportPurchaseHistory($csvFileUrl);
    public function generatePurchaseHistory($storeId);
    public function generatePurchaseHistorySecurityKey(): string;


    /**
     * Create a list member in Heyloyalty
     *
     * @param string $listId
     * @param array $fields
     * @return array
     */
    public function createListMember(string $listId, array $fields = []): array;

    public function deleteListMemberByEmail(string $listId, string $email): array;
}
