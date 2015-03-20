$(document).ready(function() {

    var addMenuHeight = 0;
    if ((typeof isStickMenu != 'undefined') && isStickMenu) {
        addMenuHeight = $("#topmenuContener").outerHeight(); 
    } 

    var s = $("#center-layered-nav");
    var pos = s.offset();                  
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop() + addMenuHeight;
        if (windowpos >= pos.top) {
            s.addClass("stick_layered").css( "top", addMenuHeight + "px" );;
        } else {
            s.removeClass("stick_layered"); 
        }
    });
});