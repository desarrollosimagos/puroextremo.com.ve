/* SHARED VARS */
var touch = false;

// handles Animate
function dataAnimate(){
  $('[data-animate]').each(function(){
    
    var $toAnimateElement = $(this);
    
    var toAnimateDelay = $(this).attr('data-delay');
    
    var toAnimateDelayTime = 0;
    
    if( toAnimateDelay ) { toAnimateDelayTime = Number( toAnimateDelay ); } else { toAnimateDelayTime = 200; }
    
    if( !$toAnimateElement.hasClass('animated') ) {
      
      $toAnimateElement.addClass('not-animated');
      
      var elementAnimation = $toAnimateElement.attr('data-animate');
      
      $toAnimateElement.appear(function () {
        
        setTimeout(function() {
          $toAnimateElement.removeClass('not-animated').addClass( elementAnimation + ' animated');
        }, toAnimateDelayTime);
        
      },{accX: 0, accY: -80},'easeInCubic');
      
    }
    
  });
}
   
jQuery(document).ready(function($) {
  
  /* DETECT PLATFORM */
  $.support.touch = 'ontouchend' in document;
  
  if ($.support.touch) {
    touch = true;
    $('body').addClass('touch');
  }
  else{
	$('body').addClass('notouch');
  }
  
  /* Handle Animate */
  if(touch == false){
    dataAnimate();
  }
});