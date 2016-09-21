require(["jquery","jquery.touchwipe","plc.fn"], function($){
    function initArrow(thisContainer){
        $('.slider_wrapper').each(function(){

            if(thisContainer==null || thisContainer=="")
            //container = $(this).attr("class").split(" ")[0];
                container = $(this).parent().attr("id");
            else
                container = thisContainer;

            thisObj = $('#' + container + ' .slider');

            if(thisObj[0])
            {
                displayWidth = 0;
                sliderCanvas = $('#' + container + ' .slider-canvas');

                if(sliderCanvas)
                    displayWidth = parseInt(sliderCanvas.width());

                //if(thisObj.has('li'))
                if(thisObj.children("li").length > 0)
                {
                    itemWidth = thisObj.children('li').outerWidth();
                    itemCount = thisObj.children('li').size();

                    maxWidth = itemWidth * itemCount;
                    cssLeft = parseInt(thisObj.css('left'));

                    if(maxWidth <= displayWidth)
                        updateArrow(container, 'right', 'hide');
                    else
                        updateArrow(container, 'right', 'show');
                }
                else
                {
                    updateArrow(container, 'right', 'hide');
                }
                updateArrow(container, 'left', 'hide');
            }
        });
    }

    function updateArrow(container, direction, status){
        thisArrowObj = $('#'+container+' .slider-'+direction);

        if(status=="show")
            thisArrowObj.removeClass("off");
        else
            thisArrowObj.addClass("off");
    }
    /*
     function resetArrow(thisTabID, container){
     thisObj = $('#' + container + ' .slider');

     if(thisObj[0])
     {
     displayWidth = $('.' + container + ' .slider-canvas').width();
     itemWidth = thisObj.children('li').outerWidth();
     itemCount = thisObj.children('li').size();

     updateArrow(container, 'left', 'hide');

     if((itemCount * itemWidth) < displayWidth)
     updateArrow(container, 'right', 'hide');
     else
     updateArrow(container, 'right', 'show');
     }
     }
     */

    function productSlider(sliderContainer, direction){
        thisObj = $('.' + sliderContainer + ' .slider');

        winwidth = $(window).width();

        if(!thisObj.is(':animated'))
        {
            displayWidth = parseInt($('.' + sliderContainer + ' .slider-canvas').width());
            itemWidth = thisObj.children('li').outerWidth();
            itemCount = thisObj.children('li').size();

            if(displayWidth < 768){
                itemsToFlow = Math.floor(displayWidth / itemWidth);
                displayWidth = itemsToFlow * itemWidth;
            }
            maxWidth = itemWidth * itemCount;
            cssLeft = parseInt(thisObj.css('left'));

            outerContainer = $('.'+sliderContainer+'.slider_wrapper').parent().attr('id');

            if(direction=='right')
            {
                cssLeft -= displayWidth;

                if((maxWidth+cssLeft) <= displayWidth)
                    updateArrow(outerContainer, 'right', 'hide');
                else
                    updateArrow(outerContainer, 'right', 'show');

                updateArrow(outerContainer, 'left', 'show');
            }
            else
            {
                cssLeft += displayWidth;

                if(cssLeft==0)
                    updateArrow(outerContainer, 'left', 'hide');
                else
                    updateArrow(outerContainer, 'left', 'show');

                updateArrow(outerContainer, 'right', 'show');
            }
            thisObj.animate({ 'left':cssLeft,  duration:250 });
        }
    }
    
    // prevent android browser scrollbar hiding or showing to fire window.resize
    var contentWidth = $(window).width();
    var contentHeight = $(window).height();

    //Product Slider Arrow Actions
    $(".slider-left").click(function(){
        thisContainer = $(this).closest("DIV.slider_wrapper").attr("class").split(" ");
        if(!$(this).hasClass('off'))
            productSlider(thisContainer[0], "left");
    });
    $(".slider-right").click(function(){
        thisContainer = $(this).closest("DIV.slider_wrapper").attr("class").split(" ");
        if(!$(this).hasClass('off'))
            productSlider(thisContainer[0], "right");
    });

    //
    // Mobile Device detect swipe slider, trigger left and right action
    //-----------------------------------------------------------------
    $.detectSwipe = function(slider){
        sliderWrapper = $('#'+slider).children('.slider_wrapper');

        if(!$.isDesktop() && $(window).width() <=1024 )
        {
            $('#'+slider).touchwipe({
                wipeLeft: function() {
                    $('#'+slider).find('.slider-right').trigger('click');
                },
                wipeRight: function() {
                    $('#'+slider).find('.slider-left').trigger('click');
                },
                min_move_x: 20,
                min_move_y: 20,
                preventDefaultEvents: false
            });
        }
    };

    $('.slider_wrapper').each(function(){
        $.detectSwipe($(this).parent().attr('id'));
    });

    /*
     $.getInitData = function(){
     $('.slider_wrapper').each(function(){
     container = $(this).attr("class").split(" ")[0];

     itemWidth = $('.' + container + ' .slider LI').outerWidth();
     $('.' + container + ' .slider').attr('data-item-basewidth', itemWidth);
     });
     }
     */

    $.adjustSlider = function(){
        winwidth = $(window).width();
        maxwidth = 1140;

        if(winwidth <= 768)
            arrowWidth = 10;    //left & right arrow
        else
            arrowWidth = 30;

        $('.slider_wrapper').each(function(){
            container = $(this).parent().attr('id');
            containerWidth = $(this).parent().outerWidth();
            sliderCanvas = $('#' + container + ' .slider-canvas');
            thisObj = $('#' + container + ' .slider');

            if(thisObj)
            {
                displayWidth = 0;

                if(sliderCanvas)
                {
                    itemWidth = thisObj.children('LI').outerWidth();

                    if(winwidth <= 768){
                        displayWidth = winwidth;
                        maxDisplayItems = Math.floor((displayWidth - arrowWidth*2) / itemWidth);
                    }
                    else{
                        //containerWidth = $('#' + container + ' .slider_wrapper').outerWidth();
                        //maxDisplayItems = Math.floor((winwidth - arrowWidth*2) / itemWidth);
                        displayWidth = parseInt(sliderCanvas.width());
                        maxDisplayItems = Math.floor((containerWidth - arrowWidth*2) / itemWidth);
                    }

                    if(maxDisplayItems <= 0)
                        maxDisplayItems = 1;

                    //if((winwidth <= maxwidth) && (maxDisplayItems > 0) && (displayWidth != itemWidth*maxDisplayItems)){
                    if(displayWidth != itemWidth*maxDisplayItems){
                        $(this).css('width', itemWidth*maxDisplayItems);
                        sliderCanvas.css('width', itemWidth*maxDisplayItems);
                        thisObj.css('left',0);
                    }
                    initArrow();
                }
                else
                    return;
            }
        });
    };

    $(window).resize(function(){
        $.adjustSlider();
    });

    // Capture Event : Rotate, add Event Listener cannot add to document.ready state
    if(!$.isDesktop() && $(window).width() <=1024 ){
        window.addEventListener('orientationchange', function() {
            $.adjustSlider();
        }, false);
    }

    //init.
    //$.getInitData();

    //init arrow included into adjustSlider()
    //initArrow();
    $.adjustSlider();
   // $(document).ready(function(){
   //     $(window).trigger('resize');
   // });
});