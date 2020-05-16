<?php

namespace Mgroup\ShippingRate\Plugin;

class ShippingMethod
{
    public $__code = array();

    public function beforeAppend($subject, $result) {
        if (!$result instanceof \Magento\Quote\Model\Quote\Address\RateResult\Method) {
            return [$result];
        }

        $this->getShipCode($result);

        if ($this->isMethodRestricted($result)) {
            try{
                $result->setIsDisabled(true);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }


        return [$result];
    }

    /**
     * @param $shippingModel
     */
    public function getShipCode($shippingModel) {
        $this->__code = $shippingModel->getCarrier();
    }

    /**
     * @param $shippingModel
     * @return bool
     */
    public function isMethodRestricted($shippingModel) {
        $code = $shippingModel->getCarrier();

        if($this->__code == 'customshippingrate') {
            return false;
        }

        return true;
    } }