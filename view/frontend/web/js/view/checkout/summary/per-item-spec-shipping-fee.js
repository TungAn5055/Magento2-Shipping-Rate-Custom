define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
    ],
    function ($,Component) {
        "use strict";

        var perItemShipping = window.checkoutConfig.perItemShipping;
        return Component.extend({
            defaults: {
                template: 'An_ShippingRate/checkout/summary/per-item-spec-shipping-fee'
            },

            /**
             * @return {*}
             */
            getStatus: function () {
                return perItemShipping['status'];
            },

            /**
             * @return {*}
             */
            getFee: function () {
                return this.getFormattedPrice(perItemShipping['fee']);
            },

            /**
             * @return {*}
             */
            getTitle: function () {
                return perItemShipping['title'];
            },
        });
    }
);