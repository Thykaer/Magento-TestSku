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
}
