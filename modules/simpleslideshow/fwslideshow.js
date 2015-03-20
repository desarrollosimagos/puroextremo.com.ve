  $(window).load(function() {
    $('#ei-slider-fw').flexslider(
	{
	animation: "slide",
	useCSS: false, 
	minItems: 1,                   
	maxItems: 1,
	smoothHeight: true, 
	animationLoop: true,
	before: function(slider){  

	 if ((slider.currentSlide + 1) == slider.count) {
		
         slider.flexslider("prev"); 
		//  $("#ei-slider-fw .slides li").not('.clone').first().find('img').fadeTo(250, 1.00);
	}
		 if ((slider.currentSlide + 1) == 1) {
         slider.flexslider("next");
            }	
			
			}   ,
			

	start: function(slider) {
                slider.removeClass('loading_mainslider');
           },
	pauseOnHover: true 		}
	
	);
  });