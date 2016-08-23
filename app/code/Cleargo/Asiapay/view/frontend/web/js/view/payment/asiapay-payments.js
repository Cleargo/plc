/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'cleargo_asiapay',
                    component: 'Cleargo_Asiapay/js/view/payment/method-renderer/asiapay-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);