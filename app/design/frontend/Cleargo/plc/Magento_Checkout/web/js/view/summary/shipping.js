/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote'
    ],
    function ($, Component, quote) {
        var weightArr = [1,
            36,
            46,
            57,
            70,
            83,
            96,
            109,
            122,
            135,
            148,
            161,
            174,
            187,
            200,
            213,
            226,
            239,
            252,
            265,
            266,
            340,
            356,
            373,
            389,
            405,
            421,
            437,
            454,
            470,
            486,
            502,
            518,
            535,
            551,
            567,
            583,
            599,
            600,
            632,
            648,
            664,
            680,
            697,
            713,
            729,
            745,
            761,
            778,
            794,
            810,
            826,
            842,
            859,
            875,
            891,
            907,
            923,
            940,
            956,
            972,
            988,
            989,
            1021,
            1037,
            1053,
            1069,
            1085,
            1102,
            1118,
            1134,
            1150,
            1166,
            1167,
            1199,
            1215,
            1231,
            1247,
            1264,
            1280,
            1296,
            1312,
            1328,
            1345,
            1361,
            1377,
            1393,
            1409,
            1426,
            1442,
            1458];

        var totalItems = quote.getItems();
        var totalItemsWeight = 0 ;
        if(totalItems){
            $.each(totalItems,function () {
                totalItemsWeight += parseInt(this.weight);
            });
        }

        if(totalItemsWeight > 90){
            totalItemsWeight = 90;
        }
        totalItemsWeight = Math.floor(totalItemsWeight);

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/summary/shipping'
            },
            quoteIsVirtual: quote.isVirtual(),
            totals: quote.getTotals(),
            getShippingMethodTitle: function() {
                if (!this.isCalculated()) {
                    return '';
                }
                var shippingMethod = quote.shippingMethod();
                return shippingMethod ? shippingMethod.carrier_title + " - " + shippingMethod.method_title : '';
            },
            isCalculated: function() {
                return this.totals() && this.isFullMode() && null != quote.shippingMethod();
            },
            getOriginalFee:function(){
                var value = weightArr[totalItemsWeight];
                return this.getFormattedPrice(value);
            },
            getValue: function() {
                if (!this.isCalculated()) {
                    return this.notCalculatedMessage;
                }
                var price =  this.totals().shipping_amount;
                return this.getFormattedPrice(price);
            }
        });
    }
);
