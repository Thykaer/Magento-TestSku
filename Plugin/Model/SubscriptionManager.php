<?php

namespace Wexo\Heyloyalty\Plugin\Model;

class SubscriptionManager
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Wexo\Heyloyalty\Api\HeyLoyaltyConfigInterface
     */
    private $heyLoyaltyConfig;

    /**
     * @var \Wexo\Heyloyalty\Api\HeyLoyaltyApiInterface
     */
    private $heyLoyaltyApi;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Wexo\Heyloyalty\Api\HeyLoyaltyConfigInterface $heyLoyaltyConfig,
        \Wexo\Heyloyalty\Api\HeyLoyaltyApiInterface $heyLoyaltyApi,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->logger = $logger;
        $this->heyLoyaltyConfig = $heyLoyaltyConfig;
        $this->heyLoyaltyApi = $heyLoyaltyApi;
        $this->customerRepository = $customerRepository;
    }

    public function afterSubscribe(
        \Magento\Newsletter\Model\SubscriptionManager $subject,
        \Magento\Newsletter\Model\Subscriber $result
    ) {
        $this->subscribe($result);
        return $result;
    }

    public function afterUnsubscribe(
        \Magento\Newsletter\Model\SubscriptionManager $subject,
        \Magento\Newsletter\Model\Subscriber $result
    ) {
        $this->unsubscribe($result);
        return $result;
    }

    /**
     * Subscribe customer to newsletter
     *
     * @param int $customerId
     * @param int $storeId
     * @return Subscriber
     */
    public function afterSubscribeCustomer(
        \Magento\Newsletter\Model\SubscriptionManager $subject,
        \Magento\Newsletter\Model\Subscriber $result,
        int $customerId,
        int $storeId
    ) {
        $this->subscribe($result, $customerId);
        return $result;
    }

    /**
     * Unsubscribe customer from newsletter
     *
     * @param int $customerId
     * @param int $storeId
     * @return Subscriber
     */
    public function afterUnsubscribeCustomer(
        \Magento\Newsletter\Model\SubscriptionManager $subject,
        \Magento\Newsletter\Model\Subscriber $result,
        int $customerId,
        int $storeId
    ) {
        $this->unsubscribe($result, $customerId);
        return $result;
    }

    public function subscribe($subscriber, $customerId = null)
    {
        $this->logger->info('Customer Subscribed to Newsletter', [
            'subscriber' => $subscriber->getData()
        ]);

        if ($this->heyLoyaltyConfig->isEnabled()) {

            $listId = $this->heyLoyaltyConfig->getList();
            if (!empty($listId)) {
                $fields = [
                    'email' => $subscriber->getSubscriberEmail()
                ];
                if ($customerId !== null) {
                    $customer = $this->customerRepository->get($subscriber->getSubscriberEmail());
                    $fields['firstname'] = $customer->getFirstname();
                    $fields['lastname'] = $customer->getLastname();
                    $fields = array_merge(
                        $fields,
                        $this->heyLoyaltyConfig->mapFields($customer)
                    );
                }
                $this->heyLoyaltyApi->createListMember($listId, $fields);
            }
        }
    }

    public function unsubscribe($subscriber)
    {
        $this->logger->info('Customer Unsubscribed to Newsletter', [
            'subscriber' => $subscriber->getData()
        ]);
        if ($this->heyLoyaltyConfig->isEnabled()) {
            $listId = $this->heyLoyaltyConfig->getList();
            if (!empty($listId)) {
                $this->heyLoyaltyApi->deleteListMemberByEmail($listId, $subscriber->getSubscriberEmail());
            }
        }
    }
}
