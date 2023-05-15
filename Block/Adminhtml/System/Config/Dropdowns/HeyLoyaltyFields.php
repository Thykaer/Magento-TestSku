<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns;

use Magento\Framework\View\Element\Context;
use Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyConfigInterface;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\AbstractSelect;

class HeyLoyaltyFields extends AbstractSelect
{
    public function __construct(
        Context $context,
        public HeyLoyaltyConfigInterface $config,
        public HeyLoyaltyApiInterface $api,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Override to get HeyLoyalty to map Magento 2 fields to
     *
     * @return array[]
     */
    public function getSourceOptions(): array
    {
        $fields = [];
        $list = $this->api->getList($this->config->getList());
        foreach ($list['fields'] as $field) {
            $fields[$field['name']] = $field['label'];
        }
        return [
            [
                'label' => 'Fields in list',
                'value' => $fields
            ],
            /*[
                'label' => 'HeyLoyalty Fixed Fields',
                'value' => [
                    'firstname' => 'First Name',
                    'lastname' => 'Last Name',
                    'email' => 'Email',
                    'mobile' => 'Mobile',
                    'sex' => 'Sex',
                    'birthdate' => 'Birthdate',
                    'address' => 'Address',
                    'postalcode' => 'Postal Code',
                    'city' => 'City',
                    'Country' => 'Country',
                    'Password' => 'Password',
                    'Reference' => 'Reference'
                ]
            ]*/
        ];
    }
}
