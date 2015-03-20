$(document).ready(function(){
	var firstImage = $('#manufacturer_products_slider').find('img').filter(':first');
	if(firstImage.length > 0){
	checkforloadedmps = setInterval(function() {
		var image = firstImage.get(0);
		if (image.complete || image.readyState == 'complete' || image.readyState == 4) {
			clearInterval(checkforloadedmps);
			$('#manufacturer_products_slider').flexslider({
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

		}

	}, 20);
	}

	$(window).resize(function() {
		try {
			$('#manufacturer_products_slider').flexslider(0);
			if($('#center_column').width()<=280){ $('#manufacturer_products_slider').data('flexslider').setOpts({minItems: 1, maxItems: 1});
		}
		else if($('#center_column').width()<=440){ $('#manufacturer_products_slider').data('flexslider').setOpts({minItems: grid_size_ms, maxItems: grid_size_ms});}
		else if($('#center_column').width()<963){ $('#manufacturer_products_slider').data('flexslider').setOpts({minItems: grid_size_sm, maxItems: grid_size_sm});}
		else if($('#center_column').width()>=1240){ $('#manufacturer_products_slider').data('flexslider').setOpts({minItems: grid_size_lg, maxItems: grid_size_lg});}
		else if($('#center_column').width()>=963){ $('#manufacturer_products_slider').data('flexslider').setOpts({minItems: grid_size_md, maxItems: grid_size_md});}
		
	} catch(e) {
            // handle all your exceptions here
        }

    });
});
