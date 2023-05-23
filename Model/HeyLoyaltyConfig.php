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
    public const CONFIG_MAPPER = 'heyloyalty/general/mappings';
    public const CONFIG_TRACKING_ACTIVATE = 'heyloyalty/tracking/enabled';
    public const CONFIG_TRACKING_ID = 'heyloyalty/tracking/tracking_id';
    public const CONFIG_SESSION_TIME = 'heyloyalty/tracking/session_time';
    public const CONFIG_PURCHASE_HISTORY_ACTIVATE = 'heyloyalty/purchase_history/activate';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        public ScopeConfigInterface $scopeConfig,
        public Json $json,
        public \Magento\Store\Model\Information $storeInformation,
        public \Magento\Store\Model\StoreManagerInterface $storeManager,
        public \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
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
     * @return array
     */
    public function getMappings(): array
    {
        try {
            return $this->json->unserialize($this->scopeConfig->getValue(
                self::CONFIG_MAPPER,
                ScopeInterface::SCOPE_STORE
            )) ?? [];
        } catch (InvalidArgumentException $e) {
            return [];
        }
    }

    public function mapFields($customer = null): array
    {
        if (!$customer) {
            return [];
        }
        $fields = [];
        $mappings = $this->getMappings();
        try {
            $defaultBillingId = $customer->getDefaultBilling();
            $defaultShippingId = $customer->getDefaultShipping();
            $billingAddress = $this->addressRepository->getById($defaultBillingId);
            $shippingAddress = $this->addressRepository->getById($defaultShippingId);
            $storeInformation = $this->storeInformation->getStoreInformationObject($this->storeManager->getStore());

            foreach ($mappings as $mapping) {
                if(!isset($mapping['magento_field']) && !isset($mapping['heyloyalty_field'])) {
                    continue;
                }
                $magentoField = $mapping['magento_field'];
                if (str_contains($magentoField, 'store')) {
                    $fields[$mapping['heyloyalty_field']] = $storeInformation->getData(str_replace('store_', '', $magentoField));
                    continue;
                }
                if (str_contains($magentoField, 'shipping')) {
                    $methodName = 'get' . str_replace('shipping_', '', ucwords($magentoField, '_'));
                    $fields[$mapping['heyloyalty_field']] = $shippingAddress->$methodName();
                    continue;
                }
                if (str_contains($magentoField, 'billing')) {
                    $methodName = 'get' . str_replace('billing_', '', ucwords($magentoField, '_'));
                    $fields[$mapping['heyloyalty_field']] = $billingAddress->$methodName();
                    continue;
                }

                $methodName = 'get' . str_replace('_', '', ucwords($magentoField, '_'));
                try{
                    $fields[$mapping['heyloyalty_field']] = $customer->$methodName();
                }catch(\Throwable $e){
                    // do nothing, field might not exist on customer object
                }
            }
            dd($fields);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $fields;
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
