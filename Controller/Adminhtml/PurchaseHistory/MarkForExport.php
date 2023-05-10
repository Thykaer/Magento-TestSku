<?php

namespace Wexo\HeyLoyalty\Controller\Adminhtml\PurchaseHistory;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResourceConnection;

class MarkForExport extends Action implements HttpGetActionInterface
{
    public function __construct(
        Context $context,
        public ResourceConnection $connection
    )
    {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     */
    public function execute()
    {
        return;
        $connection = $this->connection->getConnection();
        $table = $connection->getTableName('sales_order');
        $query = "UPDATE {$table} SET export_to_heyloyalty = 1 " .
            "WHERE export_to_heyloyalty = 0 " .
            "AND customer_id IS NOT NULL " .
            "AND created_at >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";
        $connection->query($query);
    }
}