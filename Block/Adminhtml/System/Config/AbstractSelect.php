<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config;

use Magento\Framework\View\Element\Html\Select;

abstract class AbstractSelect extends Select
{
    /**
     * Set input name
     *
     * @param $value
     * @return mixed
     */
    public function setInputName($value): mixed
    {
        return $this->setName($value);
    }

    /**
     * Set input id
     *
     * @param $value
     * @return AbstractSelect
     */
    public function setInputId($value): AbstractSelect
    {
        return $this->setId($value);
    }

    /**
     * Convert to HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * Get source options
     *
     * @return array
     */
    public function getSourceOptions(): array
    {
        return [];
    }

    /**
     * Get extra parameters
     *
     * @return string
     */
    public function getExtraParams(): string
    {
        return 'style="width:200px"';
    }
}
