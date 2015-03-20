function createCategorySlider(selector)
{
	$(document).ready(function(){
		var checkforloadedcats = [];
		var firstImage = $(selector).find('img').filter(':first');
		if(firstImage.length > 0){
		checkforloadedcats[selector] = setInterval(function() {
			var image = firstImage.get(0);
			if (image.complete || image.readyState == 'complete' || image.readyState == 4) {
				clearInterval(checkforloadedcats[selector]);
				$(selector).flexslider({
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
				$(selector).flexslider(0);
				if($('#center_column').width()<=280){ $(selector).data('flexslider').setOpts({minItems: 1, maxItems: 1});
			}
			else if($('#center_column').width()<=440){ $(selector).data('flexslider').setOpts({minItems: grid_size_ms, maxItems: grid_size_ms});}
			else if($('#center_column').width()<963){ $(selector).data('flexslider').setOpts({minItems: grid_size_sm, maxItems: grid_size_sm});}
			else if($('#center_column').width()>=1240){ $(selector).data('flexslider').setOpts({minItems: grid_size_lg, maxItems: grid_size_lg});}
			else if($('#center_column').width()>=963){ $(selector).data('flexslider').setOpts({minItems: grid_size_md, maxItems: grid_size_md});}

		} catch(e) {
            // handle all your exceptions here
        }

    });
	});
}
