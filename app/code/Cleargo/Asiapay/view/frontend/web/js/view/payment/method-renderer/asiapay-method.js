/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'mage/url',
        'Cleargo_Asiapay/js/view/payment/form-builder',
        'jquery'
    ],
    function (Component, url, formBuilder, $) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Cleargo_Asiapay/payment/asiapay',
                redirectAfterPlaceOrder: false
            },

            getCode: function() {
                return 'cleargo_asiapay';
            },

            getGatewayUrl: function () {
                return window.checkoutConfig.payment[this.getCode()].gatewayUrl;
            },

            getFormData: function () {
                var orderData, configData, formData;
                configData = window.checkoutConfig.payment[this.getCode()].configData;
                orderData = {};

                $.ajax({
                    url: url.build('asiapay/payment/ajax/'),
                    async: false,
                    success: function(data) {
                        console.log("Ajax");
                        console.log(data);
                        orderData = data;
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        if (XMLHttpRequest.status == 0) {
                            console.log(' Check Your Network.');
                        } else if (XMLHttpRequest.status == 404) {
                            console.log('Requested URL not found.');
                        } else if (XMLHttpRequest.status == 500) {
                            console.log('Internel Server Error.');
                        }  else {
                            console.log('Unknow Error.\n' + XMLHttpRequest.responseText);
                        }
                    }
                });

                formData = $.extend({}, configData, orderData);
                return formData;
            },

            redirectToAsiapay: function () {
                formBuilder.build(
                    {
                        action: this.getGatewayUrl(),
                        fields: this.getFormData()
                    }
                ).submit();
            },

            afterPlaceOrder: function () {
                //window.location.replace(url.build('asiapay/payment/redirect/'));
                this.redirectToAsiapay();
            }
        });
    }
);
