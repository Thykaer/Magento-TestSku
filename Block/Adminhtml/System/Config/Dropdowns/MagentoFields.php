<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns;

use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\AbstractSelect;

class MagentoFields extends AbstractSelect
{
    public function getSourceOptions()
    {
        return [
            [
                'label' => 'Billing Information',
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
                    'customer_id' => 'Customer ID',
                    'customer_type' => 'Customer Type'
                ]
            ]
            /*[
                'label' => 'Store Information',
                'value' => [
                    'store_name' => 'Name',
                    'store_phone' => 'Phone',
                    'store_country_id' => 'Country',
                    'store_region_id' => 'Region',
                    'store_postcode' => 'Zip/Postal Code',
                    'store_city' => 'City',
                    'store_street_line1' => 'Street1',
                    'store_street_line2' => 'Street2',
                    'store_merchant_vat_number' => 'Vat',
                ]
            ],[
                'label' => 'Session Information',
                'value' => [
                    'session_name' => 'Name',
                    'session_email' => 'Email',
                    // 'session_get_data' => 'getData() from static',
                ]
            ],[
                'label' => 'Shipping Information',
                'value' => [
                    'shipping_name' => 'Name',
                    'shipping_firstname' => 'First Name',
                    'shipping_lastname' => 'Last Name',
                    'shipping_email' => 'Email',
                    'shipping_telephone' => 'Phone',
                    'shipping_country_id' => 'Country',
                    'shipping_region' => 'Region',
                    'shipping_postcode' => 'Zip/Postal Code',
                    'shipping_city' => 'City',
                    'shipping_street' => 'Street',
                    'shipping_company' => 'Company',
                    'shipping_fax' => 'Fax',
                    // 'shipping_get_data' => 'getData() from static'
                ]
            ],[
                'label' => 'Billing Information',
                'value' => [
                    'billing_name' => 'Name',
                    'billing_firstname' => 'First Name',
                    'billing_lastname' => 'Last Name',
                    'billing_email' => 'Email',
                    'billing_telephone' => 'Phone',
                    'billing_country_id' => 'Country',
                    'billing_region' => 'Region',
                    'billing_postcode' => 'Zip/Postal Code',
                    'billing_city' => 'City',
                    'billing_street' => 'Street',
                    'billing_company' => 'Company',
                    'billing_fax' => 'Fax',
                    // 'billing_get_data' => 'getData() from static'
                ]
            ]*/
        ];
    }
}
