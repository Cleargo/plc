require([
    'jquery',
    'mage/translate'
], function($){
    $(document).ready(function() {
        $('#brandList10').show();
        $('.cat-top-nav').find('li').on('click',function () {
            $('.brands_list').hide();
            $('#brandList' + $(this).attr('target')).show();
        });

    });
});