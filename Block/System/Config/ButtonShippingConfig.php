<?php

namespace Mgroup\ShippingRate\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ButtonShippingConfig extends Field
{
    protected $_template = 'Mgroup_ShippingRate::system/config/button_config.phtml';

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
    public function getCustomUrl()
    {
        return $this->getUrl('mgshippingrate/update/config');
    }
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'btn_id',
                'label' => __('Update Config'),
                'onclick' => 'setLocation(\'' . $this->getUrl('mgshippingrate/update/config') . '\')',
            ]
        );
        return $button->toHtml();
    }
}