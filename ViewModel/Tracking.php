<?php

namespace Wexo\HeyLoyalty\ViewModel;

use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Registry;

class Tracking implements ArgumentInterface
{
    public function __construct(
        public HeyLoyaltyConfigInterface $config,
        public Http $request,
        public Registry $_coreRegistry,
        public CustomerSession $customerSession,
        public CheckoutSession $checkoutSession,
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
        return $this->config->getSessionTime();
    }

    public function getTrackingId(): string
    {
        return $this->config->getTrackingId();
    }

    public function isProductPage(): bool
    {
        return $this->request->getFullActionName() === 'catalog_product_view';
    }

    public function isCategoryPage(): bool
    {
        return $this->request->getFullActionName() === 'catalog_category_view';
    }

    public function isSuccessPage(): bool
    {
        return $this->request->getFullActionName() === 'checkout_onepage_success';
    }

    public function getCurrentProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getCurrentCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }

    public function getQuoteId()
    {
        return $this->checkoutSession->getQuoteId();
    }

    public function getEmail()
    {
        return $this->customerSession->getCustomer()->getEmail();
    }
}
