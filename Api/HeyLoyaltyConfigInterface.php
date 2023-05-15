<?php

namespace Wexo\HeyLoyalty\Api;

interface HeyLoyaltyConfigInterface
{
    /**
     * Get if the HeyLoyal module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get HeyLoyalty API key
     *
     * @return string
     */
    public function getApiKey(): string;

    /**
     * Get HeyLoyalty API secret
     *
     * @return string
     */
    public function getApiSecret(): string;

    /**
     * Get if tracking is activated
     *
     * @return bool
     */
    public function getIsTrackingActivated(): bool;

    /**
     * Get list chosen in config
     *
     * @return string
     */
    public function getList(): string;

    /**
     * Get HeyLoyalty field mapping
     *
     * @return string
     */
    public function getMapping(): string;

    /**
     * Get HeyLoyalty tracking id
     *
     * @return string
     */
    public function getTrackingId(): string;

    /**
     * Get HeyLoyalty session time
     *
     * @return string
     */
    public function getSessionTime(): string;
}
