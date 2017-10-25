//for responsive, when click the minicart logo on the top, the search msg on logo right will be disappeared.
require([
    'jquery'
], function ($) {
    $('.action.showcart').click(function() {
        $('.block.block-content').css('display','none');
    });
});