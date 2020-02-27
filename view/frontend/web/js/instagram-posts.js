define([
    'jquery',
    'slick'
], function($) {
    "use strict";
    

    $.widget('Winkel_InstagramPost.instagramPost', {

      _create: function() {      
        this.element.on('init', function (event, slick) { 
            $('.slick-slide').height($('.slick-slide').width())
        });          
        this.element.slick({
          slidesToShow: 6,
          slidesToScroll: 2,
          centerMode: true,
          arrows: true
        })
      }

    });

    return $.Winkel_InstagramPost.instagramPost;
});