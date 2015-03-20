$(window).load(function(){
	resizeWidth();
});
(function($,sr){
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null; 
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100); 
      };
  }
// smartresize 
 jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');

var TO = false;
$(window).resize(function(){
 if(TO !== false)
    clearTimeout(TO);
 TO = setTimeout(resizeWidth, 400); //400 is time in miliseconds
 dropdownMenu();
});

function resizeWidth()
{
	var menuWidth = 1180;
	var numColumn = 5;
	var currentWidth = $("#menu").outerWidth()-2;
	if (currentWidth < menuWidth) {
	
		new_width_column = currentWidth / numColumn;
		$('#menu div.options_list').each(function(index, element) { 
			var options_list = $(this).next();
			$(this).width(parseFloat(options_list.css("width"))/menuWidth*numColumn * new_width_column); 		
		});
		$('#menu div.option').each(function(index, element) {
			var option = $(this).next();
		$(this).width(parseFloat(option.css("width"))/menuWidth*numColumn * new_width_column);
		$("ul", this).width(parseFloat(option.css("width"))/menuWidth*numColumn * new_width_column);
		
		});
		$('#menu ul.column').each(function(index, element) {
			var column = $(this).next();
		$(this).width(parseFloat(column.css("width"))/menuWidth*numColumn * new_width_column);
		});
	}
	
	$('#menu ul > li > a + div').each(function(index, element) {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();
		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());
		if (i > 0) {
			$(this).css('margin-left', '-' + i + 'px');
		}
		else
			$(this).css('margin-left', '0px');
	});
}
$('document').ready(function(){
	$('#megamenu-responsive-root li.parent').prepend('<p>+</p>');
			
		$('.menu-toggle').click(function(){
			$('.root').toggleClass('open');
		});
		
		$('#megamenu-responsive-root li.parent > p').click(function(){

			if ($(this).text() == '+'){
				$(this).parent('li').children('ul').slideDown(300);
				$(this).text('-');
			}else{
				$(this).parent('li').children('ul').slideUp(300);
				$(this).text('+');
			}  
			
		});
		if(moreInsert1>0 &&moreInsert2>0)
		{
			addMoreOnLoad(moreInsert1,moreInsert2,numLiItem,htmlLiHide1,htmlLiHide2);
			addMoreResponsive(moreInsert1,moreInsert2,numLiItem,htmlLiHide1,htmlLiHide2);
		}
	/*---------*/
	dropdownMenu();
	$(window).resize(function(){
		dropdownMenu();
	});
});

function dropdownMenu()
{
	$("li a.title_menu_parent").hover(
  function () {
   $(this).next().slideDown('fast');
  },function () {
	$(this).parent().mouseleave(function() {
		$(".options_list").slideUp('fast');
	});
  });
}



