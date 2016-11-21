require([
    'jquery',
    'mage/translate'
], function($){
    $(document).ready(function() {
        $('#brandList10').show();
        $('.cat-top-nav').find('li').on('click',function () {
            $('.cat-top-nav').find('a').removeClass('active');
            $(this).find('a').addClass('active');
            $('.brands_list').hide();
            $('#brandList' + $(this).attr('target')).show();
        });

    });
});