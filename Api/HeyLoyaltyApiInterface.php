<?php

namespace Wexo\HeyLoyalty\Api;

interface HeyLoyaltyApiInterface
{
    /**
     * Get is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get all lists
     *
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

    /**
     * Export purchase history
     *
     * @param string $csvUrl
     * @return array
     */
    public function exportPurchaseHistory(string $csvUrl): array;

    /**
     * Generate purchase history
     *
     * @param int|string $storeId
     * @return string
     */
    public function generatePurchaseHistory(mixed $storeId): string;

    /**
     * Generate purchase history security key
     *
     * @return string
     */
    public function generatePurchaseHistorySecurityKey(): string;


    /**
     * Create a list member in Heyloyalty
     *
     * @param string $listId
     * @param array $fields
     * @return array
     */
    public function createListMember(string $listId, array $fields = []): array;

    /**
     * Delete list member by email
     *
     * @param string $listId
     * @param string $email
     * @return array
     */
    public function deleteListMemberByEmail(string $listId, string $email): array;
}
