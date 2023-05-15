<?php

namespace Wexo\HeyLoyalty\Model;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
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
    public const CONFIG_TRACKING_ID = 'heyloyalty/general/tracking_id';
    public const CONFIG_SESSION_TIME = 'heyloyalty/general/session_time';
    public const CONFIG_PURCHASE_HISTORY_ACTIVATE = 'heyloyalty/general/purchase_history_activate';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        public ScopeConfigInterface $scopeConfig,
        public Json $json
    ) {
    }

    /**
     * Get if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_ENABLED,
            ScopeInterface::SCOPE_STORE
        ) === '1';
    }

    /**
     * Get HeyLoyalty API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_API_KEY,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * Get HeyLoyalty API secret
     *
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_API_SECRET,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * Get if tracking is activated
     *
     * @return bool
     */
    public function getIsTrackingActivated(): bool
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_TRACKING_ACTIVATE,
            ScopeInterface::SCOPE_STORE
        ) === '1';
    }

    /**
     * Get list chosen in config
     *
     * @return string
     */
    public function getList(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_LIST,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * Get HeyLoyalty field mapping
     *
     * @return string
     */
    public function getMapping(): string
    {
        try {
            return $this->json->unserialize($this->scopeConfig->getValue(
                self::CONFIG_MAPPER,
                ScopeInterface::SCOPE_STORE
            )) ?? '';
        } catch (InvalidArgumentException $e) {
            return '';
        }
    }

    /**
     * Get HeyLoyalty tracking id
     *
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_TRACKING_ID,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * Get HeyLoyalty session time
     *
     * @return string
     */
    public function getSessionTime(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_SESSION_TIME,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * Get if purchase history export is activated
     *
     * @return bool
     */
    public function getIsPurchaseHistoryActivated(): bool
    {
        return $this->scopeConfig->getValue(
                self::CONFIG_PURCHASE_HISTORY_ACTIVATE,
                ScopeInterface::SCOPE_STORE
            ) === '1';
    }
}
