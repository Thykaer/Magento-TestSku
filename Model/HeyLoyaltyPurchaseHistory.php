<?php

namespace Wexo\HeyLoyalty\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManager;
use Wexo\HeyLoyalty\Api\HeyLoyaltyPurchaseHistoryInterface;

class HeyLoyaltyPurchaseHistory implements HeyLoyaltyPurchaseHistoryInterface
{
    /**
     * @param State $state
     * @param Emulation $emulation
     * @param StoreManager $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        // State Emulation
        public State $state,
        public Emulation $emulation,
        public StoreManager $storeManager,
        public ScopeConfigInterface $scopeConfig,

        // Models required
        public OrderRepositoryInterface $orderRepository,
        public CustomerRepositoryInterface $customerRepository,
        public OrderItemRepositoryInterface $orderItemRepository,

        // Search Criteria
        public SearchCriteriaBuilder $searchCriteriaBuilder,
        public FilterBuilder $filterBuilder,
    ) {
    }

    /**
     * @return array|CustomerInterface[]
     * @throws LocalizedException
     */
    public function execute(): array
    {
        /*
         select
            "" as member_email,
            soi.sku as product_id,
            "" as category_id,
            "" as category_name,
            "" as variation_type,
            "" as variation_id,
            "" as product_url,
            
        from sales_order_item as soi
        left join sales_order as so on so.entity_id = soi.order_id
        where
            so.customer_id is not null
            and
            so.created_at > DATE_SUB(NOW(),INTERVAL 2 YEAR)
        order by customer_id
         */
        $storeId = 1;
        $area = Area::AREA_FRONTEND;

        $this->setAreaCode($area);
        $this->storeManager->setCurrentStore($storeId);
        $this->emulation->startEnvironmentEmulation($storeId, $area, true);

        $customers = $this->getAllCustomers();
        foreach ($customers as $customerKey => $customer) {
            $customerId = $customer->getId();
            $orders = $this->getAllOrdersForACustomer($customerId);
            foreach ($orders as $orderKey => $order) {
                $items = $this->getAllItemsOnAnOrder($order);
                $orders[$orderKey]['items'] = $items;
                $customers[$customerKey]['orders'] = $orders[$orderKey];
            }
        }

        $this->emulation->stopEnvironmentEmulation();

        return $customers;
    }

    /**
     * Get all customers
     *
     * @return CustomerInterface[]
     * @throws LocalizedException
     */
    public function getAllCustomers(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->customerRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Get all orders placed by a customer
     *
     * @param int $customerId
     * @return OrderInterface[]
     */
    public function getAllOrdersForACustomer(int $customerId): array
    {
        $filter = $this->filterBuilder
            ->setField('customer_id')
            ->setValue($customerId)
            ->setField('export_to_heyloyalty')
            ->setValue(1)
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter($filter)
            ->create();
        return $this->orderRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Get all items on an order
     *
     * @param OrderInterface $order
     * @return OrderItemInterface
     */
    public function getAllItemsOnAnOrder(OrderInterface $order): array
    {
        return $order->getAllVisibleItems() ?? [];
    }

    /**
     * Try to set area code
     *
     * @param $areaCode
     * @return void
     */
    public function setAreaCode($areaCode): void
    {
        try{
            $this->state->setAreaCode($areaCode);
        }catch(LocalizedException $e){
            // intentionally left empty
        }
    }
}
