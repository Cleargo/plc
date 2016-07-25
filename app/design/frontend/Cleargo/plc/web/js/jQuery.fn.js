require(['jquery'], function($) {
    //console.log("test");jQuery(function(){
    if (jQuery(window).width() < 768){
        jQuery("#topnav .level0 a").click(function(e){
            e.preventDefault();
            window.location = jQuery(this).attr('href');
        });
    }
    
    jQuery(".top-links.block-user a.action").click(function(){
        jQuery(".top-links.block-user .block-content").toggle();
        jQuery(".top-links.block-user a.action").toggleClass("active");
    })

    jQuery(".top-links.block-search a.action").click(function() {
        if (jQuery(window).width() < 768){
            jQuery(".top-links.block-search .block-content").toggle();
            jQuery(".top-links.block-search .block-content label.label").toggleClass("active");
        }else{
            jQuery(".top-links.block-search .block-content").toggle("fast");
        }
        jQuery(".top-links.block-search a.action").toggleClass("active");
    })

    jQuery(document).click(function(event){
        if(jQuery(event.target).parents(".top-links.block-user").length==0 && jQuery(".top-links.block-user a.action").hasClass('active')){
            jQuery(".top-links.block-user .block-content").hide();
            jQuery(".top-links.block-user a.action").removeClass("active");
            console.log('outside select');
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

});
