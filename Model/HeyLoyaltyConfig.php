<?php

namespace Wexo\HeyLoyalty\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;

class HeyLoyaltyConfig implements HeyLoyaltyConfigInterface
{
    public const CONFIG_ENABLED = 'heyloyalty/general/enabled';
    public const CONFIG_API_KEY = 'heyloyalty/general/api_key';
    public const CONFIG_API_SECRET = 'heyloyalty/general/api_secret';
    public const CONFIG_LIST = 'heyloyalty/general/list';
    public const CONFIG_TRACKING_ACTIVATE = 'heyloyalty/general/tracking_activate';
    public const CONFIG_MAPPER = 'heyloyalty/general/mapper';

    public function __construct(
        public ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_ENABLED,
            ScopeInterface::SCOPE_STORE
        ) === '1';
    }

    public function getApiKey(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_API_KEY,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    public function getApiSecret(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_API_SECRET,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    public function getTrackingActivated(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_TRACKING_ACTIVATE,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    public function getList(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_LIST,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    public function getMapper(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_MAPPER,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }
}
