require(['jquery', 'jquery.mCustomScrollbar'], function($) {
    //
    // Header Nav: Fixed top while scrolling
    //--------------------------------------------------

    $(window).scroll(function () {
        //if you hard code, then use console
        //.log to determine when you want the 
        //nav bar to stick.  
        if ($(window).scrollTop() > 280) {
            if(!$('header.page-header').hasClass('nav-fixed')){
                
                $('header.page-header').addClass('nav-fixed');
                $('.nav-sections').addClass('nav-fixed');
            }
        }
        if ($(window).scrollTop() < 150) {

            $('header.page-header').removeClass('nav-fixed');
            $('.nav-sections').removeClass('nav-fixed');
        }
    });
    
    //
    // Mobile : Nav Menu - Disable Submenu and Active Parent List item link 
    //--------------------------------------------------
    if ($(window).width() < 768){
        $("nav.navigation .level0 a").click(function(e){
            e.preventDefault();
            window.location = $(this).attr('href');
        });
    }

    //
    // Header : Show user drop-down 
    //--------------------------------------------------
    $(".top-links.block-user a.action").click(function(){
        $(".top-links.block-user .block-content").toggle();
        $(".top-links.block-user a.action").toggleClass("active");
    })

    //
    // Header : Show Search input box
    //--------------------------------------------------
    $(".top-links.block-search a.action").click(function() {
        if ($(window).width() < 768){
            $(".top-links.block-search .block-content").toggle();
            $(".top-links.block-search .block-content label.label").toggleClass("active");
        }else{
            $(".top-links.block-search .block-content").toggle("fast");
        }
        $(".top-links.block-search a.action").toggleClass("active");
    })
    
    //
    // Header : Hide User / Search Content
    //--------------------------------------------------
    $(document).click(function(event){
        if($(event.target).parents(".top-links.block-user").length==0 && $(".top-links.block-user a.action").hasClass('active')){
            $(".top-links.block-user .block-content").hide();
            $(".top-links.block-user a.action").removeClass("active");
        }
        if($(event.target).parents(".top-links.block-search").length==0 && $(".top-links.block-search a.action").hasClass('active')){
            if ($(window).width() < 768){
                $(".top-links.block-search .block-content").hide();
                $(".top-links.block-search .block-content label.label").removeClass("active");
            }else{
                $(".top-links.block-search .block-content").hide('fast');
            }
            $(".top-links.block-search a.action").removeClass("active");
        }        
    })


});