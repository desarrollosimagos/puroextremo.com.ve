  $(document).ready(function(){
	var firstImage = $('#productpageadverts').find('img').filter(':first');
	if(firstImage.length > 0){
	checkforloadedssppa = setInterval(function() {
		var image = firstImage.get(0);
		if (image.complete || image.readyState == 'complete' || image.readyState == 4) {
			clearInterval(checkforloadedssppa);
			$('#productpageadverts').flexslider(
			{
				start: function(slider) { slider.removeClass('loading_mainslider');},
				pauseOnHover: true 	
			});

		}

	}, 20);
	}
});