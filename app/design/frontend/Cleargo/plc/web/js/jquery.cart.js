require(['jquery'], function($) {
    //
    // Shopping Cart
    //--------------------------------------------------
    jQuery('body').on('change','.input-text.qty', function(){
        button = jQuery('.cart.main.actions .action');
        if(button.hasClass('active')) {
            button.removeClass('active');
        }
        else {
            button.addClass('active');
        }
    });
});
