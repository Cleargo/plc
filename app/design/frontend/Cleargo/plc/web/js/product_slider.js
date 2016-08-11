function initArrow(thisContainer){
    jQuery('.slider_wrapper').each(function(){

        if(thisContainer==null || thisContainer=="")
        //container = jQuery(this).attr("class").split(" ")[0];
            container = jQuery(this).parent().attr("id");
        else
            container = thisContainer;

        thisObj = jQuery('#' + container + ' .slider');

        if(thisObj[0])
        {
            displayWidth = 0;
            sliderCanvas = jQuery('#' + container + ' .slider-canvas');

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
    thisArrowObj = jQuery('#'+container+' .slider-'+direction);

    if(status=="show")
        thisArrowObj.removeClass("off");
    else
        thisArrowObj.addClass("off");
}
/*
 function resetArrow(thisTabID, container){
 thisObj = jQuery('#' + container + ' .slider');

 if(thisObj[0])
 {
 displayWidth = jQuery('.' + container + ' .slider-canvas').width();
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
    thisObj = jQuery('.' + sliderContainer + ' .slider');

    winwidth = jQuery(window).width();

    if(!thisObj.is(':animated'))
    {
        displayWidth = parseInt(jQuery('.' + sliderContainer + ' .slider-canvas').width());
        itemWidth = thisObj.children('li').outerWidth();
        itemCount = thisObj.children('li').size();

        if(displayWidth < 768){
            itemsToFlow = Math.floor(displayWidth / itemWidth);
            displayWidth = itemsToFlow * itemWidth;
        }
        maxWidth = itemWidth * itemCount;
        cssLeft = parseInt(thisObj.css('left'));

        outerContainer = jQuery('.'+sliderContainer+'.slider_wrapper').parent().attr('id');

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

require(["jquery"], function(){
    // prevent android browser scrollbar hiding or showing to fire window.resize
    var contentWidth = jQuery(window).width();
    var contentHeight = jQuery(window).height();

    //Product Slider Arrow Actions
    jQuery(".slider-left").click(function(){
        thisContainer = jQuery(this).closest("DIV.slider_wrapper").attr("class").split(" ");
        if(!jQuery(this).hasClass('off'))
            productSlider(thisContainer[0], "left");
    });
    jQuery(".slider-right").click(function(){
        thisContainer = jQuery(this).closest("DIV.slider_wrapper").attr("class").split(" ");
        if(!jQuery(this).hasClass('off'))
            productSlider(thisContainer[0], "right");
    });

    //
    // Mobile Device detect swipe slider, trigger left and right action
    //-----------------------------------------------------------------
    jQuery.detectSwipe = function(slider){
        sliderWrapper = jQuery('#'+slider).children('.slider_wrapper');

        if(!jQuery.isDesktop() && jQuery(window).width() <=1024 )
        {
            jQuery('#'+slider).touchwipe({
                wipeLeft: function() {
                    jQuery('#'+slider).find('.slider-right').trigger('click');
                },
                wipeRight: function() {
                    jQuery('#'+slider).find('.slider-left').trigger('click');
                },
                min_move_x: 20,
                min_move_y: 20,
                preventDefaultEvents: false
            });
        }
    };

    jQuery('.slider_wrapper').each(function(){
        jQuery.detectSwipe(jQuery(this).parent().attr('id'));
    });

    /*
     jQuery.getInitData = function(){
     jQuery('.slider_wrapper').each(function(){
     container = jQuery(this).attr("class").split(" ")[0];

     itemWidth = jQuery('.' + container + ' .slider LI').outerWidth();
     jQuery('.' + container + ' .slider').attr('data-item-basewidth', itemWidth);
     });
     }
     */

    jQuery.adjustSlider = function(){
        winwidth = jQuery(window).width();
        maxwidth = 1140;

        if(winwidth <= 768)
            arrowWidth = 10;    //left & right arrow
        else
            arrowWidth = 50;

        jQuery('.slider_wrapper').each(function(){
            container = jQuery(this).parent().attr('id');
            containerWidth = jQuery(this).parent().outerWidth();
            sliderCanvas = jQuery('#' + container + ' .slider-canvas');
            thisObj = jQuery('#' + container + ' .slider');

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
                        //containerWidth = jQuery('#' + container + ' .slider_wrapper').outerWidth();
                        //maxDisplayItems = Math.floor((winwidth - arrowWidth*2) / itemWidth);
                        displayWidth = parseInt(sliderCanvas.width());
                        maxDisplayItems = Math.floor((containerWidth - arrowWidth*2) / itemWidth);
                    }

                    if(maxDisplayItems <= 0)
                        maxDisplayItems = 1;

                    //if((winwidth <= maxwidth) && (maxDisplayItems > 0) && (displayWidth != itemWidth*maxDisplayItems)){
                    if(displayWidth != itemWidth*maxDisplayItems){
                        jQuery(this).css('width', itemWidth*maxDisplayItems);
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

    jQuery(window).resize(function(){
        jQuery.adjustSlider();
    });

    // Capture Event : Rotate, add Event Listener cannot add to document.ready state
    if(!jQuery.isDesktop() && jQuery(window).width() <=1024 ){
        window.addEventListener('orientationchange', function() {
            jQuery.adjustSlider();
        }, false);
    }

    //init.
    //jQuery.getInitData();

    //init arrow included into adjustSlider()
    //initArrow();
    jQuery.adjustSlider();
   // jQuery(document).ready(function(){
   //     jQuery(window).trigger('resize');
   // });
});