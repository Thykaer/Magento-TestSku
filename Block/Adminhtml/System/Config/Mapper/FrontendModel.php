<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Mapper;

use Magento\Framework\Exception\LocalizedException;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\AbstractFrontendModel;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns\HeyLoyaltyFields;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns\MagentoFields;

class FrontendModel extends AbstractFrontendModel
{
    /**
     * Get HeyLoyalty mapping fields
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getHeyLoyaltyFields(): mixed
    {
        if (!$this->heyLoyaltyFields) {
            $this->heyLoyaltyFields = $this->getLayout()->createBlock(
                HeyLoyaltyFields::class,
                ''
            );
        }
        return $this->heyLoyaltyFields;
    }

    /**
     * Get Magento 2 mapping fields
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getMagentoFields(): mixed
    {
        if (!$this->magentoFields) {
            $this->magentoFields = $this->getLayout()->createBlock(
                MagentoFields::class,
                ''
            );
        }
        return $this->magentoFields;
    }
}
