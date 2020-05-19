<?php
namespace An\ShippingRate\Model\Quote\Address\Total;

class HandlingFee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * @var CheckoutSession
     */
    private $cart;

    /**
     * HandlingFee constructor.
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->_priceCurrency = $priceCurrency;
        $this->cart = $cart;
        $this->productFactory = $productFactory;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|HandlingFee
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $shippingCost = 0;
        $status = false;
        $num = 0;
        $items = $this->cart->getQuote()->getAllItems();
        foreach($items as $item) {
            $product = $this->productFactory->create()->load($item->getProductId());
            if(!empty($product->getAmShippingPeritem())){
                $num++;
                $shippingCost += $product->getAmShippingPeritem() * $item->getQty();
            }
        }

        if($num > 0 && $num < count($items)){
            $status = true;
        }

        if($status){
            $handlingFee = $shippingCost;
            $total->addTotalAmount('peritemspecshippingfee', $handlingFee);
            $total->addBaseTotalAmount('peritemspecshippingfee', $handlingFee);
            $quote->setHandlingFee($handlingFee);
        }

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $shippingCost = 0;
        $status = false;
        $num = 0;
        $items = $this->cart->getQuote()->getAllItems();
        foreach($items as $item) {
            $product = $this->productFactory->create()->load($item->getProductId());
            if(!empty($product->getAmShippingPeritem())){
                $num++;
                $shippingCost += $product->getAmShippingPeritem() * $item->getQty();
            }
        }

        if($num > 0 && $num < count($items)){
            $status = true;
        }

        if($status){
            return [
                'code' => 'per-item-spec-shipping-fee',
                'title' => $this->getLabel(),
                'value' => $shippingCost
            ];
        } else {
            return [];
        }

    }

    /**
     * get label
     * @return string
     */
    public function getLabel() {
        return __('Per item spec shipping fee');
    }
}