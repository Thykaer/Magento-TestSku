<?php

namespace Wexo\HeyLoyalty\Controller\Adminhtml\PurchaseHistory;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultFactory;

class MarkForExport extends Action implements HttpGetActionInterface
{
    public function __construct(
        public Context $context,
        public ResourceConnection $connection,
        public \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        public \Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface $api,
        public \Magento\Framework\UrlInterface $url,
        public \Magento\Store\Model\App\Emulation $emulation,
        public \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $storeId = $this->getRequest()->getParam('store', false);
            $this->emulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            $securityKey = $this->api->generatePurchaseHistorySecurityKey();
            $url = $this->url->getBaseUrl() . 'wexo_heyloyalty/purchasehistory/csvexport?security_key=' . $securityKey;
            if ($storeId) {
                $url .= "&store_id=$storeId";
            }
            $this->emulation->stopEnvironmentEmulation();
            $response = $this->api->exportPurchaseHistory($url);
            $this->logger->debug('HeyLoyalty :: Purchase History', [
                'url' => $url,
                'response' => $response
            ]);
            $this->messageManager->addSuccessMessage(__('Orders marked for export successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->logger->error('HeyLoyalty :: Purchase History', [
                'error' => $e->getMessage()
            ]);
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_url->getUrl('adminhtml/system_config/edit', ['section' => 'heyloyalty']));
        return $resultRedirect;
    }
}
