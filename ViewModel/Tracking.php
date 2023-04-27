<?php

namespace Wexo\HeyLoyalty\ViewModel;

use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class Tracking
{
    public function __construct(
        public HeyLoyaltyConfigInterface $config
    ) {
    }

    /**
     * Get from config if tracking is activated
     *
     * @return bool
     */
    public function getIsTrackingActivated(): bool
    {
        return $this->config->getIsTrackingActivated();
    }

    /**
     * Get API key from config
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->config->getApiKey();
    }

    /**
     * Get session time
     *
     * @return string
     */
    public function getSessionTime(): string
    {
        return '';
    }

    /**
     * Get tracking id from config
     *
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->config->getTrackingId();
    }
}
