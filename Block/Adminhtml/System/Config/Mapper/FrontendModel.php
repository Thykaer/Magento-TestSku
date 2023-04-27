<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Mapper;

use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\AbstractFrontendModel;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns\HeyLoyaltyFields;
use Wexo\HeyLoyalty\Block\Adminhtml\System\Config\Dropdowns\MagentoFields;

class FrontendModel extends AbstractFrontendModel
{
    public function getHeyLoyaltyFields()
    {
        if (!$this->heyLoyaltyFields) {
            $this->heyLoyaltyFields = $this->getLayout()->createBlock(
                HeyLoyaltyFields::class,
                ''
            );
        }
        return $this->heyLoyaltyFields;
    }
    public function getMagentoFields()
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
