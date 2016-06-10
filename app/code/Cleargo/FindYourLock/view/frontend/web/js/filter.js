require([
    'jquery',
    'mage/translate'
], function($){
    $(document).ready(function() {
        $('.district-filter input[type="radio"]').change(function() {
            updateDealerList();
        });

        $('.district-filter select').change(function() {
            updateDealerList();
        });

        $('.keyword-container input').change(function() {
            updateDealerList();
        });



        function updateDealerList() {
            var queryString = "";
            var region = [];
            var district = [];
            var keyword = jQuery('.keyword-container input').val();
            $('.district-filter input[type="radio"]').each(function() {

                if(jQuery(this).prop('checked')) {
                    var boxVal = $(this).val();
                    region.push(boxVal);
                    if(jQuery('select[name="district"]').length > 0) {

                            district= jQuery('select[name="district"]').val();
                        console.log(district);

                    }
                }
            });
            queryString = "region="+region.join(',')+"&district="+district+"&keyword="+keyword;

            var filterURL = document.location.protocol + '//' + document.location.host + document.location.pathname + "?" + queryString;

            $(".lock-list-container").addClass('loading');
            $.ajax({
                url: filterURL,
                success: function(data) {
                    $('.lock-list-container').html($(data).find('.lock-list-container').html());
                    //console.log($.trim($(data).find('.lock-list-container .region-list').html()).length);
                    if($.trim($(data).find('.lock-list-container .region-list').html()).length == 0) {
                        $('.lock-list-container').html('<p class="no-result">' + $.mage.__('There is no result.') + '</p>');
                    }
                    $('.lock-list-container').removeClass('loading');
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
        }
    });
});