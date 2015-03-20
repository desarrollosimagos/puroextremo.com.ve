$.fn.equalHeights = function(px) {
	$(this).each(function(){
		var currentTallest = 0;
		$(this).children().each(function(i){
			if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
		});
    if (!px && Number.prototype.pxToEm) currentTallest = currentTallest.pxToEm(); //use ems unless px is specified
		// for ie6, set height since min-height isn't supported
		if ($.browser.msie && $.browser.version == 6.0) { $(this).children().css({'height': currentTallest}); }
		$(this).children().css({'min-height': currentTallest}); 
	});
	return this;
};
	
	
	(function($) {
	$.fn.equalHeights2 = function(minHeight, maxHeight) {
		tallest = (minHeight) ? minHeight : 0;
		this.each(function() {
			if($(this).height() > tallest) {
				tallest = $(this).height();
			}
		});
		if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
		return this.each(function() {
			$(this).height(tallest);
		});
	}
})(jQuery);
	
	function setHeight(column) {	
	
	//nothing
}


	function setHeight2(column) {
	maxHeight = 0;
    //Get all the element with class = col
    column = $(column);
    //Loop all the column
    column.each(function() {       
        //Store the highest value
        if($(this).height() > maxHeight) {
            maxHeight = $(this).height();
        }
    });
    //Set the height
    column.height(maxHeight);
		
}



	
	

/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */
/*! NOTE: If you're already including a window.matchMedia polyfill via Modernizr or otherwise, you don't need this part */
window.matchMedia=window.matchMedia||(function(e,f){var c,a=e.documentElement,b=a.firstElementChild||a.firstChild,d=e.createElement("body"),g=e.createElement("div");g.id="mq-test-1";g.style.cssText="position:absolute;top:-100em";d.style.background="none";d.appendChild(g);return function(h){g.innerHTML='&shy;<style media="'+h+'"> #mq-test-1 { width: 42px; }</style>';a.insertBefore(d,b);c=g.offsetWidth==42;a.removeChild(d);return{matches:c,media:h}}})(document);

//Flexslider
(function(a){a.flexslider=function(f,q){var c=a(f);c.vars=a.extend({},a.flexslider.defaults,q);var j=c.vars.namespace,e=window.navigator&&window.navigator.msPointerEnabled&&window.MSGesture,k=(("ontouchstart" in window)||e||window.DocumentTouch&&document instanceof DocumentTouch)&&c.vars.touch,d="click touchend MSPointerUp",b="",p,i=c.vars.direction==="vertical",l=c.vars.reverse,o=(c.vars.itemWidth>0),h=c.vars.animation==="fade",m=c.vars.asNavFor!=="",g={},n=true;a.data(f,"flexslider",c);g={init:function(){c.animating=false;c.currentSlide=parseInt((c.vars.startAt?c.vars.startAt:0),10);if(isNaN(c.currentSlide)){c.currentSlide=0}c.animatingTo=c.currentSlide;c.atEnd=(c.currentSlide===0||c.currentSlide===c.last);c.containerSelector=c.vars.selector.substr(0,c.vars.selector.search(" "));c.slides=a(c.vars.selector,c);c.container=a(c.containerSelector,c);c.count=c.slides.length;c.syncExists=a(c.vars.sync).length>0;if(c.vars.animation==="slide"){c.vars.animation="swing"}c.prop=(i)?"top":"marginLeft";c.args={};c.manualPause=false;c.stopped=false;c.started=false;c.startTimeout=null;c.transitions=!c.vars.video&&!h&&c.vars.useCSS&&(function(){var t=document.createElement("div"),s=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"];for(var r in s){if(t.style[s[r]]!==undefined){c.pfx=s[r].replace("Perspective","").toLowerCase();c.prop="-"+c.pfx+"-transform";return true}}return false}());if(c.vars.controlsContainer!==""){c.controlsContainer=a(c.vars.controlsContainer).length>0&&a(c.vars.controlsContainer)}if(c.vars.manualControls!==""){c.manualControls=a(c.vars.manualControls).length>0&&a(c.vars.manualControls)}if(c.vars.randomize){c.slides.sort(function(){return(Math.round(Math.random())-0.5)});c.container.empty().append(c.slides)}c.doMath();c.setup("init");if(c.vars.controlNav){g.controlNav.setup()}if(c.vars.directionNav){g.directionNav.setup()}if(c.vars.keyboard&&(a(c.containerSelector).length===1||c.vars.multipleKeyboard)){a(document).bind("keyup",function(s){var r=s.keyCode;if(!c.animating&&(r===39||r===37)){var t=(r===39)?c.getTarget("next"):(r===37)?c.getTarget("prev"):false;c.flexAnimate(t,c.vars.pauseOnAction)}})}if(c.vars.mousewheel){c.bind("mousewheel",function(t,v,s,r){t.preventDefault();var u=(v<0)?c.getTarget("next"):c.getTarget("prev");c.flexAnimate(u,c.vars.pauseOnAction)})}if(c.vars.pausePlay){g.pausePlay.setup()}if(c.vars.slideshow&&c.vars.pauseInvisible){g.pauseInvisible.init()}if(c.vars.slideshow){if(c.vars.pauseOnHover){c.hover(function(){if(!c.manualPlay&&!c.manualPause){c.pause()}},function(){if(!c.manualPause&&!c.manualPlay&&!c.stopped){c.play()}})}if(!c.vars.pauseInvisible||!g.pauseInvisible.isHidden()){(c.vars.initDelay>0)?c.startTimeout=setTimeout(c.play,c.vars.initDelay):c.play()}}if(m){g.asNav.setup()}if(k&&c.vars.touch){g.touch()}if(!h||(h&&c.vars.smoothHeight)){a(window).bind("resize orientationchange focus",g.resize)}c.find("img").attr("draggable","false");setTimeout(function(){c.vars.start(c)},200)},asNav:{setup:function(){c.asNav=true;c.animatingTo=Math.floor(c.currentSlide/c.move);c.currentItem=c.currentSlide;c.slides.removeClass(j+"active-slide").eq(c.currentItem).addClass(j+"active-slide");if(!e){c.slides.on(d,function(t){t.preventDefault();var s=a(this),r=s.index();var u=s.offset().left-a(c).scrollLeft();if(u<=0&&s.hasClass(j+"active-slide")){c.flexAnimate(c.getTarget("prev"),true)}else{if(!a(c.vars.asNavFor).data("flexslider").animating&&!s.hasClass(j+"active-slide")){c.direction=(c.currentItem<r)?"next":"prev";c.flexAnimate(r,c.vars.pauseOnAction,false,true,true)}}})}else{f._slider=c;c.slides.each(function(){var r=this;r._gesture=new MSGesture();r._gesture.target=r;r.addEventListener("MSPointerDown",function(s){s.preventDefault();if(s.currentTarget._gesture){s.currentTarget._gesture.addPointer(s.pointerId)}},false);r.addEventListener("MSGestureTap",function(u){u.preventDefault();var t=a(this),s=t.index();if(!a(c.vars.asNavFor).data("flexslider").animating&&!t.hasClass("active")){c.direction=(c.currentItem<s)?"next":"prev";c.flexAnimate(s,c.vars.pauseOnAction,false,true,true)}})})}}},controlNav:{setup:function(){if(!c.manualControls){g.controlNav.setupPaging()}else{g.controlNav.setupManual()}},setupPaging:function(){var u=(c.vars.controlNav==="thumbnails")?"control-thumbs":"control-paging",s=1,v,r;c.controlNavScaffold=a('<ol class="'+j+"control-nav "+j+u+'"></ol>');if(c.pagingCount>1){for(var t=0;t<c.pagingCount;t++){r=c.slides.eq(t);v=(c.vars.controlNav==="thumbnails")?'<img src="'+r.attr("data-thumb")+'"/>':"<a>"+s+"</a>";if("thumbnails"===c.vars.controlNav&&true===c.vars.thumbCaptions){var w=r.attr("data-thumbcaption");if(""!=w&&undefined!=w){v+='<span class="'+j+'caption">'+w+"</span>"}}c.controlNavScaffold.append("<li>"+v+"</li>");s++}}(c.controlsContainer)?a(c.controlsContainer).append(c.controlNavScaffold):c.append(c.controlNavScaffold);g.controlNav.set();g.controlNav.active();c.controlNavScaffold.delegate("a, img",d,function(x){x.preventDefault();if(b===""||b===x.type){var z=a(this),y=c.controlNav.index(z);if(!z.hasClass(j+"active")){c.direction=(y>c.currentSlide)?"next":"prev";c.flexAnimate(y,c.vars.pauseOnAction)}}if(b===""){b=x.type}g.setToClearWatchedEvent()})},setupManual:function(){c.controlNav=c.manualControls;g.controlNav.active();c.controlNav.bind(d,function(r){r.preventDefault();if(b===""||b===r.type){var t=a(this),s=c.controlNav.index(t);if(!t.hasClass(j+"active")){(s>c.currentSlide)?c.direction="next":c.direction="prev";c.flexAnimate(s,c.vars.pauseOnAction)}}if(b===""){b=r.type}g.setToClearWatchedEvent()})},set:function(){var r=(c.vars.controlNav==="thumbnails")?"img":"a";c.controlNav=a("."+j+"control-nav li "+r,(c.controlsContainer)?c.controlsContainer:c)},active:function(){c.controlNav.removeClass(j+"active").eq(c.animatingTo).addClass(j+"active")},update:function(r,s){if(c.pagingCount>1&&r==="add"){c.controlNavScaffold.append(a("<li><a>"+c.count+"</a></li>"))}else{if(c.pagingCount===1){c.controlNavScaffold.find("li").remove()}else{c.controlNav.eq(s).closest("li").remove()}}g.controlNav.set();(c.pagingCount>1&&c.pagingCount!==c.controlNav.length)?c.update(s,r):g.controlNav.active()}},directionNav:{setup:function(){var r=a('<ul class="'+j+'direction-nav"><li><a class="'+j+'prev" href="#">'+c.vars.prevText+'</a></li><li><a class="'+j+'next" href="#">'+c.vars.nextText+"</a></li></ul>");if(c.controlsContainer){a(c.controlsContainer).append(r);c.directionNav=a("."+j+"direction-nav li a",c.controlsContainer)}else{c.append(r);c.directionNav=a("."+j+"direction-nav li a",c)}g.directionNav.update();c.directionNav.bind(d,function(s){s.preventDefault();var t;if(b===""||b===s.type){t=(a(this).hasClass(j+"next"))?c.getTarget("next"):c.getTarget("prev");c.flexAnimate(t,c.vars.pauseOnAction)}if(b===""){b=s.type}g.setToClearWatchedEvent()})},update:function(){var r=j+"disabled";if(c.pagingCount===1){c.directionNav.addClass(r).attr("tabindex","-1")}else{if(!c.vars.animationLoop){if(c.animatingTo===0){c.directionNav.removeClass(r).filter("."+j+"prev").addClass(r).attr("tabindex","-1")}else{if(c.animatingTo===c.last){c.directionNav.removeClass(r).filter("."+j+"next").addClass(r).attr("tabindex","-1")}else{c.directionNav.removeClass(r).removeAttr("tabindex")}}}else{c.directionNav.removeClass(r).removeAttr("tabindex")}}}},pausePlay:{setup:function(){var r=a('<div class="'+j+'pauseplay"><a></a></div>');if(c.controlsContainer){c.controlsContainer.append(r);c.pausePlay=a("."+j+"pauseplay a",c.controlsContainer)}else{c.append(r);c.pausePlay=a("."+j+"pauseplay a",c)}g.pausePlay.update((c.vars.slideshow)?j+"pause":j+"play");c.pausePlay.bind(d,function(s){s.preventDefault();if(b===""||b===s.type){if(a(this).hasClass(j+"pause")){c.manualPause=true;c.manualPlay=false;c.pause()}else{c.manualPause=false;c.manualPlay=true;c.play()}}if(b===""){b=s.type}g.setToClearWatchedEvent()})},update:function(r){(r==="play")?c.pausePlay.removeClass(j+"pause").addClass(j+"play").html(c.vars.playText):c.pausePlay.removeClass(j+"play").addClass(j+"pause").html(c.vars.pauseText)}},touch:function(){var C,z,x,D,G,E,B=false,u=0,t=0,w=0;if(!e){f.addEventListener("touchstart",y,false);function y(H){if(c.animating){H.preventDefault()}else{if((window.navigator.msPointerEnabled)||H.touches.length===1){c.pause();D=(i)?c.h:c.w;E=Number(new Date());u=H.touches[0].pageX;t=H.touches[0].pageY;x=(o&&l&&c.animatingTo===c.last)?0:(o&&l)?c.limit-(((c.itemW+c.vars.itemMargin)*c.move)*c.animatingTo):(o&&c.currentSlide===c.last)?c.limit:(o)?((c.itemW+c.vars.itemMargin)*c.move)*c.currentSlide:(l)?(c.last-c.currentSlide+c.cloneOffset)*D:(c.currentSlide+c.cloneOffset)*D;C=(i)?t:u;z=(i)?u:t;f.addEventListener("touchmove",s,false);f.addEventListener("touchend",F,false)}}}function s(H){u=H.touches[0].pageX;t=H.touches[0].pageY;G=(i)?C-t:C-u;B=(i)?(Math.abs(G)<Math.abs(u-z)):(Math.abs(G)<Math.abs(t-z));var I=500;if(!B||Number(new Date())-E>I){H.preventDefault();if(!h&&c.transitions){if(!c.vars.animationLoop){G=G/((c.currentSlide===0&&G<0||c.currentSlide===c.last&&G>0)?(Math.abs(G)/D+2):1)}c.setProps(x+G,"setTouch")}}}function F(J){f.removeEventListener("touchmove",s,false);if(c.animatingTo===c.currentSlide&&!B&&!(G===null)){var I=(l)?-G:G,H=(I>0)?c.getTarget("next"):c.getTarget("prev");if(c.canAdvance(H)&&(Number(new Date())-E<550&&Math.abs(I)>50||Math.abs(I)>D/2)){c.flexAnimate(H,c.vars.pauseOnAction)}else{if(!h){c.flexAnimate(c.currentSlide,c.vars.pauseOnAction,true)}}}f.removeEventListener("touchend",F,false);C=null;z=null;G=null;x=null}}else{f.style.msTouchAction="none";f._gesture=new MSGesture();f._gesture.target=f;f.addEventListener("MSPointerDown",r,false);f._slider=c;f.addEventListener("MSGestureChange",A,false);f.addEventListener("MSGestureEnd",v,false);function r(H){H.stopPropagation();if(c.animating){H.preventDefault()}else{c.pause();f._gesture.addPointer(H.pointerId);w=0;D=(i)?c.h:c.w;E=Number(new Date());x=(o&&l&&c.animatingTo===c.last)?0:(o&&l)?c.limit-(((c.itemW+c.vars.itemMargin)*c.move)*c.animatingTo):(o&&c.currentSlide===c.last)?c.limit:(o)?((c.itemW+c.vars.itemMargin)*c.move)*c.currentSlide:(l)?(c.last-c.currentSlide+c.cloneOffset)*D:(c.currentSlide+c.cloneOffset)*D}}function A(K){K.stopPropagation();var J=K.target._slider;if(!J){return}var I=-K.translationX,H=-K.translationY;w=w+((i)?H:I);G=w;B=(i)?(Math.abs(w)<Math.abs(-I)):(Math.abs(w)<Math.abs(-H));if(K.detail===K.MSGESTURE_FLAG_INERTIA){setImmediate(function(){f._gesture.stop()});return}if(!B||Number(new Date())-E>500){K.preventDefault();if(!h&&J.transitions){if(!J.vars.animationLoop){G=w/((J.currentSlide===0&&w<0||J.currentSlide===J.last&&w>0)?(Math.abs(w)/D+2):1)}J.setProps(x+G,"setTouch")}}}function v(K){K.stopPropagation();var H=K.target._slider;if(!H){return}if(H.animatingTo===H.currentSlide&&!B&&!(G===null)){var J=(l)?-G:G,I=(J>0)?H.getTarget("next"):H.getTarget("prev");if(H.canAdvance(I)&&(Number(new Date())-E<550&&Math.abs(J)>50||Math.abs(J)>D/2)){H.flexAnimate(I,H.vars.pauseOnAction)}else{if(!h){H.flexAnimate(H.currentSlide,H.vars.pauseOnAction,true)}}}C=null;z=null;G=null;x=null;w=0}}},resize:function(){if(!c.animating&&c.is(":visible")){if(!o){c.doMath()}if(h){g.smoothHeight()}else{if(o){c.slides.width(c.computedW);c.update(c.pagingCount);c.setProps()}else{if(i){c.viewport.height(c.h);c.setProps(c.h,"setTotal")}else{if(c.vars.smoothHeight){g.smoothHeight()}c.newSlides.width(c.computedW);c.setProps(c.computedW,"setTotal")}}}}},smoothHeight:function(r){if(!i||h){var s=(h)?c:c.viewport;(r)?s.animate({height:c.slides.eq(c.animatingTo).height()},r):s.height(c.slides.eq(c.animatingTo).height())}},sync:function(r){var t=a(c.vars.sync).data("flexslider"),s=c.animatingTo;switch(r){case"animate":t.flexAnimate(s,c.vars.pauseOnAction,false,true);break;case"play":if(!t.playing&&!t.asNav){t.play()}break;case"pause":t.pause();break}},uniqueID:function(r){r.find("[id]").each(function(){var s=a(this);s.attr("id",s.attr("id")+"_clone")});return r},pauseInvisible:{visProp:null,init:function(){var t=["webkit","moz","ms","o"];if("hidden" in document){return"hidden"}for(var s=0;s<t.length;s++){if((t[s]+"Hidden") in document){g.pauseInvisible.visProp=t[s]+"Hidden"}}if(g.pauseInvisible.visProp){var r=g.pauseInvisible.visProp.replace(/[H|h]idden/,"")+"visibilitychange";document.addEventListener(r,function(){if(g.pauseInvisible.isHidden()){if(c.startTimeout){clearTimeout(c.startTimeout)}else{c.pause()}}else{if(c.started){c.play()}else{(c.vars.initDelay>0)?setTimeout(c.play,c.vars.initDelay):c.play()}}})}},isHidden:function(){return document[g.pauseInvisible.visProp]||false}},setToClearWatchedEvent:function(){clearTimeout(p);p=setTimeout(function(){b=""},3000)}};c.flexAnimate=function(z,A,t,v,w){if(!c.vars.animationLoop&&z!==c.currentSlide){c.direction=(z>c.currentSlide)?"next":"prev"}if(m&&c.pagingCount===1){c.direction=(c.currentItem<z)?"next":"prev"}if(!c.animating&&(c.canAdvance(z,w)||t)&&c.is(":visible")){if(m&&v){var s=a(c.vars.asNavFor).data("flexslider");c.atEnd=z===0||z===c.count-1;s.flexAnimate(z,true,false,true,w);c.direction=(c.currentItem<z)?"next":"prev";s.direction=c.direction;if(Math.ceil((z+1)/c.visible)-1!==c.currentSlide&&z!==0){c.currentItem=z;c.slides.removeClass(j+"active-slide").eq(z).addClass(j+"active-slide");z=Math.floor(z/c.visible)}else{c.currentItem=z;c.slides.removeClass(j+"active-slide").eq(z).addClass(j+"active-slide");return false}}c.animating=true;c.animatingTo=z;if(A){c.pause()}c.vars.before(c);if(c.syncExists&&!w){g.sync("animate")}if(c.vars.controlNav){g.controlNav.active()}if(!o){c.slides.removeClass(j+"active-slide").eq(z).addClass(j+"active-slide")}c.atEnd=z===0||z===c.last;if(c.vars.directionNav){g.directionNav.update()}if(z===c.last){c.vars.end(c);if(!c.vars.animationLoop){c.pause()}}if(!h){var y=(i)?c.slides.filter(":first").height():c.computedW,x,u,r;if(o){x=c.vars.itemMargin;r=((c.itemW+x)*c.move)*c.animatingTo;u=(r>c.limit&&c.visible!==1)?c.limit:r}else{if(c.currentSlide===0&&z===c.count-1&&c.vars.animationLoop&&c.direction!=="next"){u=(l)?(c.count+c.cloneOffset)*y:0}else{if(c.currentSlide===c.last&&z===0&&c.vars.animationLoop&&c.direction!=="prev"){u=(l)?0:(c.count+1)*y}else{u=(l)?((c.count-1)-z+c.cloneOffset)*y:(z+c.cloneOffset)*y}}}c.setProps(u,"",c.vars.animationSpeed);if(c.transitions){if(!c.vars.animationLoop||!c.atEnd){c.animating=false;c.currentSlide=c.animatingTo}c.container.unbind("webkitTransitionEnd transitionend");c.container.bind("webkitTransitionEnd transitionend",function(){c.wrapup(y)})}else{c.container.animate(c.args,c.vars.animationSpeed,c.vars.easing,function(){c.wrapup(y)})}}else{if(!k){c.slides.eq(c.currentSlide).css({zIndex:1}).animate({opacity:0},c.vars.animationSpeed,c.vars.easing);c.slides.eq(z).css({zIndex:2}).animate({opacity:1},c.vars.animationSpeed,c.vars.easing,c.wrapup)}else{c.slides.eq(c.currentSlide).css({opacity:0,zIndex:1});c.slides.eq(z).css({opacity:1,zIndex:2});c.wrapup(y)}}if(c.vars.smoothHeight){g.smoothHeight(c.vars.animationSpeed)}}};c.wrapup=function(r){if(!h&&!o){if(c.currentSlide===0&&c.animatingTo===c.last&&c.vars.animationLoop){c.setProps(r,"jumpEnd")}else{if(c.currentSlide===c.last&&c.animatingTo===0&&c.vars.animationLoop){c.setProps(r,"jumpStart")}}}c.animating=false;c.currentSlide=c.animatingTo;c.vars.after(c)};c.animateSlides=function(){if(!c.animating&&n){c.flexAnimate(c.getTarget("next"))}};c.pause=function(){clearInterval(c.animatedSlides);c.animatedSlides=null;c.playing=false;if(c.vars.pausePlay){g.pausePlay.update("play")}if(c.syncExists){g.sync("pause")}};c.play=function(){if(c.playing){clearInterval(c.animatedSlides)}c.animatedSlides=c.animatedSlides||setInterval(c.animateSlides,c.vars.slideshowSpeed);c.started=c.playing=true;if(c.vars.pausePlay){g.pausePlay.update("pause")}if(c.syncExists){g.sync("play")}};c.stop=function(){c.pause();c.stopped=true};c.canAdvance=function(t,r){var s=(m)?c.pagingCount-1:c.last;return(r)?true:(m&&c.currentItem===c.count-1&&t===0&&c.direction==="prev")?true:(m&&c.currentItem===0&&t===c.pagingCount-1&&c.direction!=="next")?false:(t===c.currentSlide&&!m)?false:(c.vars.animationLoop)?true:(c.atEnd&&c.currentSlide===0&&t===s&&c.direction!=="next")?false:(c.atEnd&&c.currentSlide===s&&t===0&&c.direction==="next")?false:true};c.getTarget=function(r){c.direction=r;if(r==="next"){return(c.currentSlide===c.last)?0:c.currentSlide+1}else{return(c.currentSlide===0)?c.last:c.currentSlide-1}};c.setProps=function(u,r,s){var t=(function(){var v=(u)?u:((c.itemW+c.vars.itemMargin)*c.move)*c.animatingTo,w=(function(){if(o){return(r==="setTouch")?u:(l&&c.animatingTo===c.last)?0:(l)?c.limit-(((c.itemW+c.vars.itemMargin)*c.move)*c.animatingTo):(c.animatingTo===c.last)?c.limit:v}else{switch(r){case"setTotal":return(l)?((c.count-1)-c.currentSlide+c.cloneOffset)*u:(c.currentSlide+c.cloneOffset)*u;case"setTouch":return(l)?u:u;case"jumpEnd":return(l)?u:c.count*u;case"jumpStart":return(l)?c.count*u:u;default:return u}}}());return Math.floor(w*-1)+"px"}());if(c.transitions){t=(i)?"translate3d(0,"+t+",0)":"translate3d("+t+",0,0)";s=(s!==undefined)?(s/1000)+"s":"0s";c.container.css("-"+c.pfx+"-transition-duration",s);c.container.css("transition-duration",s)}c.args[c.prop]=t;if(c.transitions||s===undefined){c.container.css(c.args)}c.container.css("transform",t)};c.setup=function(s){if(!h){var t,r;if(s==="init"){c.viewport=a('<div class="'+j+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(c).append(c.container);c.cloneCount=0;c.cloneOffset=0;if(l){r=a.makeArray(c.slides).reverse();c.slides=a(r);c.container.empty().append(c.slides)}}if(c.vars.animationLoop&&!o){c.cloneCount=2;c.cloneOffset=1;if(s!=="init"){c.container.find(".clone").remove()}g.uniqueID(c.slides.first().clone().addClass("clone").attr("aria-hidden","true")).appendTo(c.container);g.uniqueID(c.slides.last().clone().addClass("clone").attr("aria-hidden","true")).prependTo(c.container)}c.newSlides=a(c.vars.selector,c);t=(l)?c.count-1-c.currentSlide+c.cloneOffset:c.currentSlide+c.cloneOffset;if(i&&!o){c.container.height((c.count+c.cloneCount)*200+"%").css("position","absolute").width("100%");setTimeout(function(){c.newSlides.css({display:"block"});c.doMath();c.viewport.height(c.h);c.setProps(t*c.h,"init")},(s==="init")?100:0)}else{c.container.width((c.count+c.cloneCount)*200+"%");c.setProps(t*c.computedW,"init");setTimeout(function(){c.doMath();c.newSlides.css({width:c.computedW,"float":"left",display:"block"});if(c.vars.smoothHeight){g.smoothHeight()}},(s==="init")?100:0)}}else{c.slides.css({width:"100%","float":"left",marginRight:"-100%",position:"relative"});if(s==="init"){if(!k){c.slides.css({opacity:0,display:"block",zIndex:1}).eq(c.currentSlide).css({zIndex:2}).animate({opacity:1},c.vars.animationSpeed,c.vars.easing)}else{c.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+c.vars.animationSpeed/1000+"s ease",zIndex:1}).eq(c.currentSlide).css({opacity:1,zIndex:2})}}if(c.vars.smoothHeight){g.smoothHeight()}}if(!o){c.slides.removeClass(j+"active-slide").eq(c.currentSlide).addClass(j+"active-slide")}c.vars.init(c)};c.doMath=function(){var r=c.slides.first(),u=c.vars.itemMargin,s=c.vars.minItems,t=c.vars.maxItems;boxSizing=r.css("-moz-box-sizing")||r.css("-webkit-box-sizing")||r.css("box-sizing");c.w=(c.viewport===undefined)?c.width():c.viewport.width();c.h=r.height();if(boxSizing=="border-box"){c.boxPadding=0}else{c.boxPadding=r.outerWidth()-r.width()}if(o){c.itemT=c.vars.itemWidth+u;c.minW=(s)?s*c.itemT:c.w;c.maxW=(t)?(t*c.itemT)-u:c.w;c.itemW=(c.minW>c.w)?(c.w-(u*(s-1)))/s:(c.maxW<c.w)?(c.w-(u*(t-1)))/t:(c.vars.itemWidth>c.w)?c.w:c.vars.itemWidth;c.visible=Math.floor(c.w/(c.itemW));c.move=(c.vars.move>0&&c.vars.move<c.visible)?c.vars.move:c.visible;c.pagingCount=Math.ceil(((c.count-c.visible)/c.move)+1);c.last=c.pagingCount-1;c.limit=(c.pagingCount===1)?0:(c.vars.itemWidth>c.w)?(c.itemW*(c.count-1))+(u*(c.count-1)):((c.itemW+u)*c.count)-c.w-u}else{c.itemW=c.w;c.pagingCount=c.count;c.last=c.count-1}c.computedW=c.itemW-c.boxPadding};c.update=function(s,r){c.doMath();if(!o){if(s<c.currentSlide){c.currentSlide+=1}else{if(s<=c.currentSlide&&s!==0){c.currentSlide-=1}}c.animatingTo=c.currentSlide}if(c.vars.controlNav&&!c.manualControls){if((r==="add"&&!o)||c.pagingCount>c.controlNav.length){g.controlNav.update("add")}else{if((r==="remove"&&!o)||c.pagingCount<c.controlNav.length){if(o&&c.currentSlide>c.last){c.currentSlide-=1;c.animatingTo-=1}g.controlNav.update("remove",c.last)}}}if(c.vars.directionNav){g.directionNav.update()}};c.addSlide=function(r,t){var s=a(r);c.count+=1;c.last=c.count-1;if(i&&l){(t!==undefined)?c.slides.eq(c.count-t).after(s):c.container.prepend(s)}else{(t!==undefined)?c.slides.eq(t).before(s):c.container.append(s)}c.update(t,"add");c.slides=a(c.vars.selector+":not(.clone)",c);c.setup();c.vars.added(c)};c.removeSlide=function(r){var s=(isNaN(r))?c.slides.index(a(r)):r;c.count-=1;c.last=c.count-1;if(isNaN(r)){a(r,c.slides).remove()}else{(i&&l)?c.slides.eq(c.last).remove():c.slides.eq(r).remove()}c.doMath();c.update(s,"remove");c.slides=a(c.vars.selector+":not(.clone)",c);c.setup();c.vars.removed(c)};c.setOpts=function(s){for(var r in s){c.vars[r]=s[r]}c.setup()};c.getOpts=function(){return c.vars};g.init()};a(window).blur(function(b){focused=false}).focus(function(b){focused=true});a.flexslider.defaults={namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:false,animationLoop:true,smoothHeight:false,startAt:0,slideshow:true,slideshowSpeed:7000,animationSpeed:600,initDelay:0,randomize:false,thumbCaptions:false,pauseOnAction:true,pauseOnHover:false,pauseInvisible:true,useCSS:true,touch:true,video:false,controlNav:true,directionNav:true,prevText:"",nextText:"",keyboard:true,multipleKeyboard:false,mousewheel:false,pausePlay:false,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",sync:"",asNavFor:"",itemWidth:0,itemMargin:0,minItems:1,maxItems:0,move:0,allowOneSlide:true,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){},init:function(){}};a.fn.flexslider=function(b){if(b===undefined){b={}}if(typeof b==="object"){return this.each(function(){var f=a(this),d=(b.selector)?b.selector:".slides > li",e=f.find(d);if((e.length===1&&b.allowOneSlide===true)||e.length===0){e.fadeIn(400);if(b.start){b.start(f)}}else{if(f.data("flexslider")===undefined){new a.flexslider(this,b)}}})}else{var c=a(this).data("flexslider");switch(b){case"play":c.play();break;case"pause":c.pause();break;case"stop":c.stop();break;case"next":c.flexAnimate(c.getTarget("next"),true);break;case"prev":case"previous":c.flexAnimate(c.getTarget("prev"),true);break;default:if(typeof b==="number"){c.flexAnimate(b,true)}}}}})(jQuery);

//Cookiesjs
(function(a,b,c){function e(a){return a}function f(a){return decodeURIComponent(a.replace(d," "))}var d=/\+/g;var g=a.cookie=function(d,h,i){if(h!==c){i=a.extend({},g.defaults,i);if(h===null){i.expires=-1}if(typeof i.expires==="number"){var j=i.expires,k=i.expires=new Date;k.setDate(k.getDate()+j)}h=g.json?JSON.stringify(h):String(h);return b.cookie=[encodeURIComponent(d),"=",g.raw?h:encodeURIComponent(h),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}var l=g.raw?e:f;var m=b.cookie.split("; ");for(var n=0,o;o=m[n]&&m[n].split("=");n++){if(l(o.shift())===d){var p=l(o.join("="));return g.json?JSON.parse(p):p}}return null};g.defaults={};a.removeCookie=function(b,c){if(a.cookie(b)!==null){a.cookie(b,null,c);return true}return false}})(jQuery,document);

//Touchwipe
(function(a,b,c){b.fn.touchwipe=function(a){var c={min_move_x:20,min_move_y:20,wipeLeft:function(){},wipeRight:function(){},wipeUp:function(){},wipeDown:function(){},preventDefaultEvents:true};if(a)b.extend(c,a);this.each(function(){function e(){this.removeEventListener("touchmove",f);a=null;d=false}function f(f){if(c.preventDefaultEvents){f.preventDefault()}if(d){var g=f.touches[0].pageX;var h=f.touches[0].pageY;var i=a-g;var j=b-h;if(Math.abs(i)>=c.min_move_x){e();if(i>0){c.wipeLeft()}else{c.wipeRight()}}else if(Math.abs(j)>=c.min_move_y){e();if(j>0){c.wipeDown()}else{c.wipeUp()}}}}function g(c){if(c.touches.length==1){a=c.touches[0].pageX;b=c.touches[0].pageY;d=true;this.addEventListener("touchmove",f,false)}}var a;var b;var d=false;if("ontouchstart"in document.documentElement){this.addEventListener("touchstart",g,false)}});return this};b.elastislide=function(a,c){this.$el=b(c);this._init(a)};b.elastislide.defaults={speed:450,easing:"",imageW:190,margin:3,border:2,minItems:1,current:0,onClick:function(){return false}};b.elastislide.prototype={_init:function(a){this.options=b.extend(true,{},b.elastislide.defaults,a);this.$slider=this.$el.find("ul");this.$items=this.$slider.children("li");this.itemsCount=this.$items.length;this.$esCarousel=this.$slider.parent();this._validateOptions();this._configure();this._addControls();this._initEvents();this.$slider.show();this._slideToCurrent(false)},_validateOptions:function(){if(this.options.speed<0)this.options.speed=450;if(this.options.margin<0)this.options.margin=4;if(this.options.border<0)this.options.border=1;if(this.options.minItems<1||this.options.minItems>this.itemsCount)this.options.minItems=1;if(this.options.current>this.itemsCount-1)this.options.current=0},_configure:function(){this.current=this.options.current;this.visibleWidth=this.$esCarousel.width();if(this.visibleWidth<this.options.minItems*(this.options.imageW+2*this.options.border)+(this.options.minItems-1)*this.options.margin){this._setDim((this.visibleWidth-(this.options.minItems-1)*this.options.margin)/this.options.minItems);this._setCurrentValues();this.fitCount=this.options.minItems}else{this._setDim();this._setCurrentValues()}this.$slider.css({width:this.sliderW})},_setDim:function(a){this.$items.css({marginRight:this.options.margin,width:a?a:this.options.imageW+2*this.options.border}).children("a").css({borderWidth:this.options.border})},_setCurrentValues:function(){this.itemW=this.$items.outerWidth(true);this.sliderW=this.itemW*this.itemsCount;this.visibleWidth=this.$esCarousel.width();this.fitCount=Math.floor(this.visibleWidth/this.itemW)},_addControls:function(){this.$navNext=b('<span class="es-nav-next">Next</span>');this.$navPrev=b('<span class="es-nav-prev">Previous</span>');b('<div class="es-nav"/>').append(this.$navPrev).append(this.$navNext).appendTo(this.$el)},_toggleControls:function(a,b){if(a&&b){if(b===1)a==="right"?this.$navNext.show():this.$navPrev.show();else a==="right"?this.$navNext.hide():this.$navPrev.hide()}else if(this.current===this.itemsCount-1||this.fitCount>=this.itemsCount)this.$navNext.hide()},_initEvents:function(){var c=this;b(a).bind("resize.elastislide",function(a){c._setCurrentValues();if(c.visibleWidth<c.options.minItems*(c.options.imageW+2*c.options.border)+(c.options.minItems-1)*c.options.margin){c._setDim((c.visibleWidth-(c.options.minItems-1)*c.options.margin)/c.options.minItems);c._setCurrentValues();c.fitCount=c.options.minItems}else{c._setDim();c._setCurrentValues()}c.$slider.css({width:c.sliderW+10});clearTimeout(c.resetTimeout);c.resetTimeout=setTimeout(function(){c._slideToCurrent()},200)});this.$navNext.bind("click.elastislide",function(a){c._slide("right")});this.$navPrev.bind("click.elastislide",function(a){c._slide("left")});this.$items.bind("click.elastislide",function(a){c.options.onClick(b(this));return false});c.$slider.touchwipe({wipeLeft:function(){c._slide("right")},wipeRight:function(){c._slide("left")}})},_slide:function(a,d,e,f){if(this.$slider.is(":animated"))return false;var g=parseFloat(this.$slider.css("margin-left"));if(d===c){var h=this.fitCount*this.itemW,d;if(h<0)return false;if(a==="right"&&this.sliderW-(Math.abs(g)+h)<this.visibleWidth){h=this.sliderW-(Math.abs(g)+this.visibleWidth)-this.options.margin;this._toggleControls("right",-1);this._toggleControls("left",1)}else if(a==="left"&&Math.abs(g)-h<0){h=Math.abs(g);this._toggleControls("left",-1);this._toggleControls("right",1)}else{var i;a==="right"?i=Math.abs(g)+this.options.margin+Math.abs(h):i=Math.abs(g)-this.options.margin-Math.abs(h);if(i>0)this._toggleControls("left",1);else this._toggleControls("left",-1);if(i<this.sliderW-this.visibleWidth)this._toggleControls("right",1);else this._toggleControls("right",-1)}a==="right"?d="-="+h:d="+="+h}else{var i=Math.abs(d);if(Math.max(this.sliderW,this.visibleWidth)-i<this.visibleWidth){d=-(Math.max(this.sliderW,this.visibleWidth)-this.visibleWidth);if(d!==0)d+=this.options.margin;this._toggleControls("right",-1);i=Math.abs(d)}if(i>0)this._toggleControls("left",1);else this._toggleControls("left",-1);if(Math.max(this.sliderW,this.visibleWidth)-this.visibleWidth>i+this.options.margin)this._toggleControls("right",1);else this._toggleControls("right",-1)}b.fn.applyStyle=e===c?b.fn.animate:b.fn.css;var j={marginLeft:d};var k=this;this.$slider.applyStyle(j,b.extend(true,[],{duration:this.options.speed,easing:this.options.easing,complete:function(){if(f)f.call()}}))},_slideToCurrent:function(a){var b=this.current*this.itemW;this._slide("",-b,a)},add:function(a,b){this.$items=this.$items.add(a);this.itemsCount=this.$items.length;this._setDim();this._setCurrentValues();this.$slider.css({width:this.sliderW});this._slideToCurrent();if(b)b.call(a)},destroy:function(a){this._destroy(a)},_destroy:function(c){this.$el.unbind(".elastislide").removeData("elastislide");b(a).unbind(".elastislide");if(c)c.call()}};var d=function(a){if(this.console){console.error(a)}};b.fn.elastislide=function(a){if(typeof a==="string"){var c=Array.prototype.slice.call(arguments,1);this.each(function(){var e=b.data(this,"elastislide");if(!e){d("cannot call methods on elastislide prior to initialization; "+"attempted to call method '"+a+"'");return}if(!b.isFunction(e[a])||a.charAt(0)==="_"){d("no such method '"+a+"' for elastislide instance");return}e[a].apply(e,c)})}else{this.each(function(){var c=b.data(this,"elastislide");if(!c){b.data(this,"elastislide",new b.elastislide(a,this))}})}return this}})(window,jQuery);

//Pull up
