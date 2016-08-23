require(['jquery','jquery.fancybox', 'magestore/flexslider'], function($) {


    //
    // init function : flex slider
    //--------------------------------------------------
    $('.flexslider').flexslider({
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
    $(".fancybox-youtube-media").fancybox({
        autoScale: true,
        type: 'iframe',
        padding: 0,
        beforeLoad: function () {
            var url = $(this.element).data("href");
            this.href = url
        },afterShow: function(){

        }
    });



    //
    // Adjust Home page top banner, append loading icon
    //--------------------------------------------------
    /*$(window).resize(function(){
     //    adjustHomeVideoSlider();
     })

     var adjustHomeVideoSlider = function(){

     slider = $('.video-playlist');
     sliderSlide = $('.video-playlist ul.slides');
     sliderWidth = $('.promo-grid .grid-item:nth-child(2)').width();

     ratio = 650/960;

     winwidth = $(window).width();

     if(winwidth >= (768-17)){
     slider.removeAttr('style');
     //slider.find('ul.slides').css({'width':winwidth*30/2});
     }else{
     slider.css({'width':winwidth});
     slider.find('ul.slides').css({'width':winwidth*30});
     }
     }*/
})
