<?php

namespace An\ShippingRate\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 * @package An\ShippingRate\Helper
 */
class Data
{
    const CONFIG_SHIPPING_RATE = 'An_perspecshipping/general/shipping_rate_fee_product';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get file name
     * @return string|null
     */
    public function getConfigShippingRate()
    {
        $value = $this->scopeConfig->getValue(self::CONFIG_SHIPPING_RATE);

        return $value;
    }
}