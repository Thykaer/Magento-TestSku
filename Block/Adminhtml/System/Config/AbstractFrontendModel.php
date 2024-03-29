<?php

namespace Wexo\HeyLoyalty\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Html\Select;

class AbstractFrontendModel extends AbstractFieldArray
{
    /** @var bool|BlockInterface */
    public BlockInterface|bool $selectOptions;
    /** @var bool|BlockInterface */
    public BlockInterface|bool $heyLoyaltyFields = false;
    /** @var bool|BlockInterface */
    public BlockInterface|bool $magentoFields = false;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        public Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare array row
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $selectFieldData = $row->getSelectField();
        if ($selectFieldData !== null) {
            $options['option_' . $this->getSelectFieldOptions()->calcOptionHash($selectFieldData)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get select field options
     *
     * @return mixed
     * @throws LocalizedException
     */
    private function getSelectFieldOptions(): mixed
    {
        if (!$this->selectOptions) {
            $this->selectOptions = $this->getLayout()->createBlock(
                Select::class,
                '',
            );
        }
        return $this->selectOptions;
    }
}
