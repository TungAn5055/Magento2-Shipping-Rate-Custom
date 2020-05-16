<?php

namespace Mgroup\ShippingRate\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;

class AdditionalConfigVars implements ConfigProviderInterface
{
    const SHIPPINNG_TITLE = "carriers/customshippingrate/title";
    const SHIPPINNG_NAME = "carriers/customshippingrate/name";
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * @var CheckoutSession
     */
    private $cart;

    /**
     * @var CheckoutSession
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * AdditionalConfigVars constructor.
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->cart = $cart;
        $this->productFactory = $productFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function getConfig()
    {
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

        $additionalVariables['perItemShipping']['status'] = $status;
        $additionalVariables['perItemShipping']['name'] = $this->getConfigNameShippingMethod();
        $additionalVariables['perItemShipping']['title'] = $this->getConfigTitleShippingMethod();
        $additionalVariables['perItemShipping']['fee'] = $shippingCost;

        return $additionalVariables;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfigNameShippingMethod(){
        return $this->scopeConfig->getValue(
            self::SHIPPINNG_NAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfigTitleShippingMethod(){
        return $this->scopeConfig->getValue(
            self::SHIPPINNG_TITLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

}