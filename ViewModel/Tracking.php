<?php

namespace Wexo\HeyLoyalty\ViewModel;

use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class Tracking
{
    public function __construct(
        public HeyLoyaltyConfigInterface $config
    ) {
    }

    public function getIsTrackingActivated(): bool
    {
        return $this->config->getIsTrackingActivated();
    }

    public function getApiKey(): string
    {
        return $this->config->getApiKey();
    }

    public function getSessionTime(): string
    {
        return '';
    }

    public function getTrackingId(): string
    {
        return $this->config->getTrackingId();
    }
}
