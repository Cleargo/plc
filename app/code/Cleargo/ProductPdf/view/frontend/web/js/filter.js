require([
    'jquery',
    'mage/translate'
], function($){
    $(document).ready(function() {
        $('.cat-top-nav').find('li').on('click',function () {
            $('.cat-top-nav').find('a').removeClass('active');
            $(this).find('a').addClass('active');
            $('.category-container').hide();
            $('#categoryContainer' + $(this).attr('target')).show();
        });

    });
});