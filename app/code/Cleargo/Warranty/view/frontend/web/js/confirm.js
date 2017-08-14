require([
    'jquery','Magento_Ui/js/modal/confirm', 'Magento_Customer/js/customer-data'
], function($,confirmation,customerData){
    $(document).ready(function(){
        //customerData.reload();
        var customer = customerData.get('customer');
        console.log( window.isCustomerLoggedIn );
        $("#warrantRegContainer").find(".registration-agree").click(function (event) {
            if(!window.isCustomerLoggedIn ){
                var nextPage = window.location.origin + "/warranty/form/index";
                var loginPage = window.location.origin + "/customer/account/login";
                confirmation({
                    title: $.mage.__('Friendly Reminder') ,
                    content: $.mage.__('For users who have registered PLC eShop account, please login first before you fill in the warranty form.<br/>Or you can choose to proceed as a guest.') ,
                    actions: {
                        confirm: function(){
                            window.location.href = nextPage;
                        },
                        cancel: function(){
                            window.location.href = loginPage;
                        },
                        closed: function () {
                            return false;
                        }
                    }
                });
                $(".action-accept").text($.mage.__('Proceed as Guest'));
                $(".action-dismiss").text($.mage.__('Login'));
                return false;
            }
        });
    });
});