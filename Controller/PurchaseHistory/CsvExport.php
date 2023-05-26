<?php

namespace Wexo\HeyLoyalty\Controller\PurchaseHistory;

use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;

class CsvExport implements HttpGetActionInterface
{
    public function __construct(
        public ResourceConnection $connection,
        public \Magento\Framework\App\RequestInterface $request,
        public \Magento\Framework\App\ResponseFactory $responseFactory,
        public \Magento\Store\Model\App\Emulation $emulation,
        public \Magento\Store\Model\StoreManagerInterface $storeManager,
        public \Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface $config,
        public \Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface $api,
        public \Magento\Framework\App\CacheInterface $cache
    ) {
    }

    /**
     * Create CSV export file from table and return it
     *
     * @return ResponseInterface
     * @throws FileSystemException
     * @throws Exception
     */
    public function execute(): ResponseInterface
    {
        $securityKeyParam = $this->request->getParam('security_key');
        $storeId = $this->request->getParam('store_id', 1);
        $debug = (bool) $this->request->getParam('debug', false);

        $this->validate($storeId, $securityKeyParam);

        $csv = $this->api->generatePurchaseHistory($storeId);

        $response = $this->responseFactory->create();
        if($debug){
            $response->setHeader('Content-Type', 'text/plain charset=UTF-8');
        }else{
            $response->setHeader('Content-Type', 'text/csv charset=UTF-8');
            $response->setHeader('Content-Disposition', 'attachment; filename="purchase_history.csv"');
        }
        $response->setBody($csv);
        return $response;
    }

    public function validate($storeId, $securityKeyParam)
    {
        $securityKey = $this->api->generatePurchaseHistorySecurityKey();
        if($securityKeyParam !== $securityKey){
            throw new \Exception('Security key is not valid');
        }
        $this->emulation->startEnvironmentEmulation($storeId, 'frontend');
        $enabled = $this->config->isEnabled();
        if(!$enabled){
            throw new \Exception('Module is not enabled');
        }
        $this->emulation->stopEnvironmentEmulation();
    }

}
