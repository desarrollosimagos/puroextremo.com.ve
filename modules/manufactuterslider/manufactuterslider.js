				$(window).load(function() {
  $('#manufacturers_logo_slider').flexslider({
			namespace: "",
			animation: "slide",
			easing: "easeInQuart",
			slideshow: false,
			animationLoop: false,
			animationSpeed: 700,
			pauseOnHover: true,
			controlNav: false, 
			itemWidth:  128, 
			minItems: manFlexMin,                    //{NEW} Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
maxItems: manFlexmMax , 
			move: 0		});	

$(window).resize(function() {
	
	    try {
            $('#manufacturers_logo_slider').flexslider(0);
if($('#center_column').width()<=280){ $('#manufacturers_logo_slider').data('flexslider').setOpts({minItems: 2, maxItems: 2});}
else if($('#center_column').width()<=440){ $('#manufacturers_logo_slider').data('flexslider').setOpts({minItems: 3, maxItems: 3});}
else if($('#center_column').width()<980){ $('#manufacturers_logo_slider').data('flexslider').setOpts({minItems: 5, maxItems: 5});}
else if($('#center_column').width()>=1240){ $('#manufacturers_logo_slider').data('flexslider').setOpts({minItems: 2, maxItems: 9});}
else if($('#center_column').width()>=990){ $('#manufacturers_logo_slider').data('flexslider').setOpts({minItems: 2, maxItems: 7});}
        } catch(e) {
            // handle all your exceptions here
        }

});
});