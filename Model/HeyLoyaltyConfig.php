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
}
