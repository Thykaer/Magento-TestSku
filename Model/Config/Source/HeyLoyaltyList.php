<?php

namespace Wexo\HeyLoyalty\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Wexo\HeyLoyalty\Api\HeyLoyaltyApiInterface;

class HeyLoyaltyList implements OptionSourceInterface
{

    /**
     * @param HeyLoyaltyApiInterface $api
     */
    public function __construct(
        public HeyLoyaltyApiInterface $api
    ) {
    }

    /**
     * Create list option array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [
            ['value' => 0, 'label' => __('Select list')]
        ];
        $lists = $this->api->getLists() ?? [];
        foreach ($lists as $list) {
            $options[] = ['value' => $list['id'] ?? 0, 'label' => $list['name'] ?? ''];
        }
        return $options;
    }
}
