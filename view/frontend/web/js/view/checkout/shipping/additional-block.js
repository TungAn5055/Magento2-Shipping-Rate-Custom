define([
    'jquery',
    'Magento_Checkout/js/view/summary/abstract-total',
], function ($, Component) {
    'use strict';

    var perItemShipping = window.checkoutConfig.perItemShipping;

    return Component.extend({
        defaults: {
            template: 'An_ShippingRate/checkout/shipping/additional-block'
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
        getName: function () {
            return perItemShipping['name'];
        },

        /**
         * @return {*}
         */
        getTitle: function () {
            return perItemShipping['title'];
        },

        /**
         * @return {*}
         */
        getFee: function () {
            return this.getFormattedPrice(perItemShipping['fee']);
        },
    });
});