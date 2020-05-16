<?php

namespace Mgroup\ShippingRate\Block\Adminhtml\Order;

/**
 * Adminhtml order totals block
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Totals extends \Magento\Sales\Block\Adminhtml\Order\Totals
{
    /**
     * Initialize order totals array
     *
     * @return $this
     */
    protected function _initTotals()
    {
        parent::_initTotals();
        $source = $this->getSource();

        $this->_totals['perItemSpecShippingFee'] = new \Magento\Framework\DataObject(
            [
                'code' => 'perItemSpecShippingFee',
                'value' =>  $this->getPerPrice($source),
                'label' => __('Per Item Spec Shipping Price')
            ]
        );

        $this->_totals['paid'] = new \Magento\Framework\DataObject(
            [
                'code' => 'paid',
                'strong' => true,
                'value' => $this->getSource()->getTotalPaid(),
                'base_value' => $this->getSource()->getBaseTotalPaid(),
                'label' => __('Total Paid'),
                'area' => 'footer',
            ]
        );
        $this->_totals['refunded'] = new \Magento\Framework\DataObject(
            [
                'code' => 'refunded',
                'strong' => true,
                'value' => $this->getSource()->getTotalRefunded(),
                'base_value' => $this->getSource()->getBaseTotalRefunded(),
                'label' => __('Total Refunded'),
                'area' => 'footer',
            ]
        );
        $this->_totals['due'] = new \Magento\Framework\DataObject(
            [
                'code' => 'due',
                'strong' => true,
                'value' => $this->getSource()->getTotalDue(),
                'base_value' => $this->getSource()->getBaseTotalDue(),
                'label' => __('Total Due'),
                'area' => 'footer',
            ]
        );
        return $this;
    }

    public function getPerPrice($source){
        $perPrice = $source->getGrandTotal() - $this->getSource()->getShippingAmount() -
            $source->getSubtotal() -  $this->getSource()->getTaxAmount();
        return $perPrice;
    }
}
