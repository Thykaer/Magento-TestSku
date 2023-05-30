<?php

namespace Wexo\HeyLoyalty\Block;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Tracking extends Template
{
    public function __construct(
        public Registry        $_coreRegistry,
        public Session         $customerSession,
        public CheckoutSession $checkoutSession,
        public Template\Context       $context,
        array                  $data = []
    )
    {
        parent::__construct($context, $data);
    }
}
