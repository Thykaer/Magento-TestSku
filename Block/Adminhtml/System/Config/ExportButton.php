<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ExportButton extends Field
{
    /** @var string  */
    const AJAX_URL = "heyloyalty/purchasehistory/markforexport";

    /** @var string  */
    public $_template = "Wexo_HeyLoyalty::system/config/export_button.phtml";

    /**
     * Get controller url
     *
     * @return string
     */
    public function getAjaxUrl(): string
    {
        return $this->getUrl(self::AJAX_URL);
    }

    /**
     * Generate button html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function _getElementHtml(AbstractElement $element): string
    {
        $this->addData([
                'id' => 'heyloyalty_export_button',
                'label' => __('Prepare Export')
            ]);
        return $this->_toHtml();
    }
}
