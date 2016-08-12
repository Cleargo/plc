require(['jquery'], function($) {
    var isChrome = navigator.userAgent.indexOf('Chrome') > -1;
    var isIE = navigator.userAgent.indexOf('MSIE') > -1;
    var isFirefox = navigator.userAgent.indexOf('Firefox') > -1;
    var isSafari = navigator.userAgent.indexOf("Safari") > -1;
    var isOpera = navigator.userAgent.indexOf("Presto") > -1;
    var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
    var isiPad = navigator.userAgent.toLowerCase().indexOf("ipad");
    var isiPod = navigator.userAgent.toLowerCase().indexOf("ipod");
    if ((isChrome) && (isSafari)) {
        isSafari = false;
    }

    //---- Detect Mobile / Tablet ----------------
    jQuery.isDesktop = function () {
        if (navigator.userAgent.match(/Android/i)
            || navigator.userAgent.match(/webOS/i)
            || navigator.userAgent.match(/iPhone/i)
            || navigator.userAgent.match(/iPad/i)
            || navigator.userAgent.match(/iPod/i)
            || navigator.userAgent.match(/BlackBerry/i)
            || navigator.userAgent.match(/Windows Phone/i)) {
            return false;
        } else {
            return true;
        }
    }
})


require(['jquery','jquery.fancybox', 'jquery.mCustomScrollbar','magestore/flexslider'], function($) {
    
    //
    // Header Nav: Fixed top while scrolling
    //--------------------------------------------------

    jQuery(window).scroll(function () {
        //if you hard code, then use console
        //.log to determine when you want the 
        //nav bar to stick.  
        if (jQuery(window).scrollTop() > 280) {
            if(!jQuery('header.page-header').hasClass('nav-fixed')){
                
                jQuery('header.page-header').addClass('nav-fixed');
                jQuery('.nav-sections').addClass('nav-fixed');
            }
        }
        if (jQuery(window).scrollTop() < 150) {

            jQuery('header.page-header').removeClass('nav-fixed');
            jQuery('.nav-sections').removeClass('nav-fixed');
        }
    });
    
    //
    // Mobile : Nav Menu - Disable Submenu and Active Parent List item link 
    //--------------------------------------------------
    if (jQuery(window).width() < 768){
        jQuery("#topnav .level0 a").click(function(e){
            e.preventDefault();
            window.location = jQuery(this).attr('href');
        });
    }

    //
    // Header : Show user drop-down 
    //--------------------------------------------------
    jQuery(".top-links.block-user a.action").click(function(){
        jQuery(".top-links.block-user .block-content").toggle();
        jQuery(".top-links.block-user a.action").toggleClass("active");
    })

    //
    // Header : Show Search input box
    //--------------------------------------------------
    jQuery(".top-links.block-search a.action").click(function() {
        if (jQuery(window).width() < 768){
            jQuery(".top-links.block-search .block-content").toggle();
            jQuery(".top-links.block-search .block-content label.label").toggleClass("active");
        }else{
            jQuery(".top-links.block-search .block-content").toggle("fast");
        }
        jQuery(".top-links.block-search a.action").toggleClass("active");
    })
    
    //
    // Header : Hide User / Search Content
    //--------------------------------------------------
    jQuery(document).click(function(event){
        if(jQuery(event.target).parents(".top-links.block-user").length==0 && jQuery(".top-links.block-user a.action").hasClass('active')){
            jQuery(".top-links.block-user .block-content").hide();
            jQuery(".top-links.block-user a.action").removeClass("active");
        }
        if(jQuery(event.target).parents(".top-links.block-search").length==0 && jQuery(".top-links.block-search a.action").hasClass('active')){
            if (jQuery(window).width() < 768){
                jQuery(".top-links.block-search .block-content").hide();
                jQuery(".top-links.block-search .block-content label.label").removeClass("active");
            }else{
                jQuery(".top-links.block-search .block-content").hide('fast');
            }
            jQuery(".top-links.block-search a.action").removeClass("active");
        }        
    })


    //
    // init function : flex slider
    //--------------------------------------------------
    jQuery('.flexslider').flexslider({
        animation: 'slide',
        slideshowSpeed: 4000,
        start: function (slider) {
            // fitImageSlider(slider);
            // adjustHomeBanner();
        }
    });

    //
    // init function : Fancybox Viode Popup
    //--------------------------------------------------    
    jQuery(".fancybox-youtube-media").fancybox({
        autoScale: true,
        type: 'iframe',
        padding: 0,
        beforeLoad: function () {
            var url = jQuery(this.element).data("href");
            this.href = url
        },afterShow: function(){
            
        }
    });
    
    
    
    //
    // Adjust Home page top banner, append loading icon
    //--------------------------------------------------
    jQuery(window).resize(function(){
        //    adjustHomeVideoSlider();
    })
    
    var adjustHomeVideoSlider = function(){

        slider = jQuery('.video-playlist');
        sliderSlide = jQuery('.video-playlist ul.slides');
        sliderWidth = jQuery('.promo-grid .grid-item:nth-child(2)').width();

            ratio = 650/960;

            winwidth = jQuery(window).width();

            if(winwidth >= (768-17)){
               slider.removeAttr('style');
                //slider.find('ul.slides').css({'width':winwidth*30/2});
            }else{
                slider.css({'width':winwidth});
                slider.find('ul.slides').css({'width':winwidth*30});
            }
    };
    
    //
    // mCustomScrollbar
    // -------------------------------------------------
    jQuery(document).ready(function(){
        jQuery(".minicart-items-wrapper").mCustomScrollbar({
            axis:"y",
            scrollInertia:550,
            scrollbarPosition:"outside"
        })
    })
})
