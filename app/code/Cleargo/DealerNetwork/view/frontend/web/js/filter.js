require([
    'jquery',
    'mage/translate'
], function($){
    $(document).ready(function() {
        $('.region-filter input[type="checkbox"]').change(function() {
            updateDealerList();
        });

        $('.region-filter select').change(function() {
            updateDealerList();
        });

        $('.brand-filter select').change(function() {
            updateDealerList();
        });

        function updateDealerList() {
            var queryString = "";
            var country = [];
            var region = [];
            $('.region-filter input[type="checkbox"]').each(function() {
                if(jQuery(this).prop('checked')) {
                    var boxVal = $(this).val();
                    country.push(boxVal);
                    if(jQuery(this).siblings('select').length > 0) {
                        var tmp = jQuery(this).siblings('select').val();
                        if(tmp != "") {
                            region.push(boxVal + "_" + tmp);
                        }
                    }
                }
            });
            queryString = "country="+country.join(',')+"&region="+region.join(',')+"&brand="+$('.brand-filter select').val();

            var filterURL = document.location.protocol + '//' + document.location.host + document.location.pathname + "?" + queryString;

            $(".dealer-list-container").addClass('loading');
            $.ajax({
                url: filterURL,
                success: function(data) {
                    $('.dealer-list-container').html($(data).find('.dealer-list-container').html());
                    //console.log($.trim($(data).find('.dealer-list-container .country-list').html()).length);
                    if($.trim($(data).find('.dealer-list-container .country-list').html()).length == 0) {
                        $('.dealer-list-container').html('<p class="no-result">' + $.mage.__('There is no result.') + '</p>');
                    }
                    $('.dealer-list-container').removeClass('loading');
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