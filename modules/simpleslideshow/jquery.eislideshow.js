$(document).ready(function(){
	var firstImage = $('#ei-slider').find('img').filter(':first');
	if(firstImage.length > 0){
	checkforloadedssim = setInterval(function() {
		var image = firstImage.get(0);
		if (image.complete || image.readyState == 'complete' || image.readyState == 4) {
			clearInterval(checkforloadedssim);
			$('#ei-slider').flexslider(
			{
				start: function(slider) { slider.removeClass('loading_mainslider');},
				pauseOnHover: true 	
			});

		}

	}, 20);
	}
});


	