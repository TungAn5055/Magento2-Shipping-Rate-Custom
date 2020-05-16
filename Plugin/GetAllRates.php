<?php

namespace Mgroup\ShippingRate\Plugin;

class GetAllRates{

    /**
     * Disable the marked shipping rates.
     *
     * NOTE: If you can not see some of the shipping rates, start debugging from here. At first, check 'is_disabled'
     * param in the shipping rate object.
     *
     * @param \Magento\Shipping\Model\Rate\Result $subject
     * @param array $result
     * @return array
     */

    public function afterGetAllRates($subject, $result)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/an.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("annn");
        $check = false;

        foreach ($result as $key => $rate) {
            if($rate->getCarrier() == 'customshippingrate'){
                $check = true;
            }
        }

        if($check == true){
            foreach ($result as $key => $rate) {
                if ($rate->getIsDisabled()) {
                    unset($result[$key]);
                }
            }
        }


        return $result;
    }}