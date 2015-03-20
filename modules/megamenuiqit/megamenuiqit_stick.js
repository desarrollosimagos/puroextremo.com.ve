var isStickMenu = true;
$(document).ready(function() {

    var s = $("#topmenuContener");
    var pos = s.offset();
    var scartp = s.outerHeight()/2-13;


    var scart = $("#shopping_cart_container");                     
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if ( s.length  ){
        if (windowpos >= pos.top) {
            s.addClass("stick");
            scart.addClass("stickCart");
            scart.css({ top: scartp + 'px' });
        } else {
            s.removeClass("stick"); 
            scart.removeClass("stickCart");
            scart.removeAttr("style"); 
        }
    }
    });
});