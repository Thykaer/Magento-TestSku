<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns;

use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\AbstractSelect;

class HeyLoyaltyFields extends AbstractSelect
{
    /**
     * Override to get HeyLoyalty to map Magento 2 fields to
     *
     * @return array[]
     */
    public function getSourceOptions(): array
    {
        return [
            [
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
                    'Country' => 'Country'
                ]
            ],
            [
                'label' => 'HeyLoyalty Custom Fields',
                'value' => [
                    'customer_id' => 'Customer ID',
                    'customer_type' => 'Customer Type'
                ]
            ]
            /*[
                'label' => 'Webshipper Address Fields',
                'value' => [
                    'att_contact' => 'Att Contact',
                    'company_name' => 'Company Name',
                    'address_1' => 'Address 1',
                    'address_2' => 'Address 2',
                    'country_code' => 'Country Code',
                    'state' => 'State',
                    'phone' => 'Phone',
                    'email' => 'Email',
                    'zip' => 'Zip',
                    'city' => 'City',
                    'vat_no' => 'Vat No',
                    'address_type' => 'Address Type',
                    'ext_location' => 'Ext Location',
                    'voec' => 'Voec',
                    'eori' => 'Eori',
                    'sprn' => 'Sprn',
                    'personal_customs_no' => 'Personal Customs No',
                    'company_customs_numbers' => 'Company Customs Numbers',
                ]
            ]*/
        ];
    }
}
