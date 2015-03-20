$(window).load(function() {
	$('#crossseling_products_slider').flexslider({
		namespace: "",
		animation: "slide",
		easing: "easeInQuart",
		slideshow: false,
		animationLoop: false,
		animationSpeed: 700,
		pauseOnHover: true,
		controlNav: false,
		itemWidth:  238, 
		minItems: flexmin,                    
		maxItems: flexmax, 
		move: 0		});	

	$(window).resize(function() {
		try {
			$('#crossseling_products_slider').flexslider(0);
			if($('#center_column').width()<=280){ $('#crossseling_products_slider').data('flexslider').setOpts({minItems: 1, maxItems: 1});
		}
		else if($('#center_column').width()<=440){ $('#crossseling_products_slider').data('flexslider').setOpts({minItems: 2, maxItems: 2});}
		else if($('#center_column').width()<980){ $('#crossseling_products_slider').data('flexslider').setOpts({minItems: 3, maxItems: 3});}
		else if($('#center_column').width()>=1240){ $('#crossseling_products_slider').data('flexslider').setOpts({minItems: 1, maxItems: 5});}
		else if($('#center_column').width()>=990){ $('#crossseling_products_slider').data('flexslider').setOpts({minItems: 1, maxItems: 4});}
		
	} catch(e) {
            // handle all your exceptions here
        }

    });
});

