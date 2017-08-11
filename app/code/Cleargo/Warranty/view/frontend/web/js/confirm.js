require([
    'jquery','Magento_Ui/js/modal/confirm'
], function($,confirmation){
    $(document).ready(function(){
        $("#warrantRegContainer").find(".registration-agree").click(function (event) {
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
                    }
                }
            });
            $(".action-accept").text($.mage.__('Proceed as Guest'));
            $(".action-dismiss").text($.mage.__('Login'));
            return false;
        });
    });
});