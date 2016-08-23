
require(['jquery'], function($) {
    //
    // Product listing sorter 
    //----------------------------------------------------
    $('.filter-title').click(function () {
        if ($('.block.filter').hasClass('active')) {
            $('.toolbar.toolbar-products').addClass('active');
        } else {
            $('.toolbar.toolbar-products').removeClass('active');
        }
    })
})