	$(window).load(function() {

		var flexoptions = {
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
	move: 0		};

		$('#featured_products_tab_slider').flexslider(flexoptions);	
		$('#new_products_tab_slider').flexslider(flexoptions);	
		$('#special_products_tab_slider').flexslider(flexoptions);	

	});

	$(window).resize(function() {

		try {
			$('#featured_products_tab_slider').flexslider(0);
			if($('#center_column').width()<=280){ $('#featured_products_tab_slider').data('flexslider').setOpts({minItems: 1, maxItems: 1});
		}
		else if($('#center_column').width()<=440){ $('#featured_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_ms, maxItems: grid_size_ms});}
		else if($('#center_column').width()<963){ $('#featured_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_sm, maxItems: grid_size_sm});}
		else if($('#center_column').width()>=1240){ $('#featured_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_lg, maxItems: grid_size_lg});}
		else if($('#center_column').width()>=963){ $('#featured_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_md, maxItems: grid_size_md});}

	} catch(e) {
// handle all your exceptions here
}


try {
	$('#new_products_tab_slider').flexslider(0);
	if($('#center_column').width()<=280){ $('#new_products_tab_slider').data('flexslider').setOpts({minItems: 1, maxItems: 1});
}
else if($('#center_column').width()<=440){ $('#new_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_ms, maxItems: grid_size_ms});}
else if($('#center_column').width()<963){ $('#new_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_sm, maxItems: grid_size_sm});}
else if($('#center_column').width()>=1240){ $('#new_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_lg, maxItems: grid_size_lg});}
else if($('#center_column').width()>=963){ $('#new_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_md, maxItems: grid_size_md});}

} catch(e) {
// handle all your exceptions here
}

try {
	$('#special_products_tab_slider').flexslider(0);
	if($('#center_column').width()<=280){ $('#special_products_tab_slider').data('flexslider').setOpts({minItems: 1, maxItems: 1});
}
else if($('#center_column').width()<=440){ $('#special_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_ms, maxItems: grid_size_ms});}
else if($('#center_column').width()<963){ $('#special_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_sm, maxItems: grid_size_sm});}
else if($('#center_column').width()>=1240){ $('#special_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_lg, maxItems: grid_size_lg});}
else if($('#center_column').width()>=963){ $('#special_products_tab_slider').data('flexslider').setOpts({minItems: grid_size_md, maxItems: grid_size_md});}

} catch(e) {
// handle all your exceptions here
}

});

function createCategoryTabSlider(selector)
{
	$(window).load(function() {
		var flexoptions = {
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
	move: 0		};
		$(selector).flexslider(flexoptions);	
	});

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

}

$(document).ready(function(){
	$(".homepagetabs_module_slider .nav-tabs a").click(function() {
		$($(this).attr("href")).find(".flexslider_carousel").data('flexslider').setup(); 
	});

});
