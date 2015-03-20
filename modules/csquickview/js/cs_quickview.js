	/*

	*

	*

	*/

/*function closemyiFrame()

{

	jQuery.fancybox.close();

}*/

	// base function

	

	//get IE version

	function ieVersion(){

		var rv = -1; // Return value assumes failure.

		if (navigator.appName == 'Microsoft Internet Explorer'){

			var ua = navigator.userAgent;

			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

			if (re.exec(ua) != null)

				rv = parseFloat( RegExp.$1 );

		}

		return rv;

	}



	//read href attr in a tag

	function readHref(){

		var mypath = arguments[0].replace("index.php/","");

		var baseurl	=	CS.QuickView.BASE_URL;

		var newpath	=	mypath.replace(baseurl,"");

		newpath		=	newpath.replace(/\//gi,"_!_");

		return newpath;

	}



	//string trim

	function strTrim(){

		return arguments[0].replace(/^\s+|\s+$/g,"");

	}



	function _csJnit(IS,n){

		if(n>0)

		{

			var _qsHref = "<a id=\"cs_quickview_handler\" href=\"#\" style=\"visibility:hidden;position:absolute;top:0;left:0;z-index:9999\"><span>+&nbsp;Ver producto</span></a>";

			$(document.body).append(_qsHref);

			for(var i=0;i<n;i++)

			{

				

				var selectorObj = IS[i];

					//selector chon tat ca cac li chua san pham tren luoi

				if (selectorObj && selectorObj.itemClass) 

					var listprod = $(selectorObj.itemClass);

				else

					var listprod = $("a.product_img_link");

				var qsImg;

				var mypath = 'index.php?fc=module&module=csquickview&controller=csproduct';

				if(CS.QuickView.BASE_URL.indexOf('index.php') == -1){

					mypath = '?fc=module&module=csquickview&controller=csproduct';

				}

				var baseUrl = CS.QuickView.BASE_URL + mypath;

				

				var qsHandlerImg = $('#cs_quickview_handler span');



				$.each(listprod, function(index, value) { 

					var reloadurl = baseUrl;

					//get reload url

				var myhref;	

					myhref = $(value);

				if(myhref.attr('href').indexOf(".html")>0)

				{

					if (!myhref || myhref.length == 0) return;

					var prodHref = readHref(myhref.attr('href'));

					var arrProdHref=prodHref.split('_!_');

					var n=arrProdHref.length;

						prodHref=arrProdHref[n-1];

					var arrProdHref=prodHref.split('-');

					var product_id=arrProdHref[0];

					reloadurl=reloadurl+"&id_product="+product_id;

				}

				else

				{

				

					if (!myhref || myhref.length == 0) return;

					var prodHref = readHref(myhref.attr('href'));

					var product_id=getProductId(prodHref,"id_product");

					prodHref[0] == "\/" ? prodHref = prodHref.substring(1,prodHref.length) : prodHref;

					prodHref=strTrim(prodHref);

					var prodHref	=	prodHref.replace("index.php","");			

					var prodHref	=	prodHref.replace("&controller=product","");			

					var prodHref	=	prodHref.replace("?id_product","&id_product");			

					prodHref=strTrim(prodHref);

					reloadurl=reloadurl+prodHref;

					

				}

					//end reload url

					

					$('img', this).bind('mouseover', function() {

						$(this).addClass("prod_added_"+product_id);	

						

						if($(this).parent("a").parent(".cs_newarrival_p").length>0)

							var o = $(this).parent("a").parent(".cs_newarrival_p").offset();

						else

							var o = $(this).parent("a").offset();

							

						$('#cs_quickview_handler').attr('href',reloadurl).show()

							.css({

								'top': o.top+'px',

								'left': o.left+($(this).width() - qsHandlerImg.width()-29)+'px',

								'visibility': 'visible'

							});

					});

					

					$(value).bind('mouseout', function() {

						$(this).removeClass("prod_added_"+product_id);

						$('#cs_quickview_handler').hide();

					});

				});



				/*fix bug image disapper when hover*/

				/*Add class active when hover*/

				$('#cs_quickview_handler')

					.bind('mouseover', function() {

						var hrefNow=$(this).attr('href');

						var product_id=idFromhref(hrefNow);

						if($(".prod_added_"+product_id).parent("a.product_image").parent(".cs_newarrival_p").length>0)

						{

							$(".prod_added_"+product_id).parent("a.product_image").parent(".cs_newarrival_p").addClass("prod_hover_active")

						}

						else

						{

							$(".prod_added_"+product_id).parent("a").addClass("prod_hover_active");

						}	

						$(this).show();

					}).bind('mouseout', function() {

						var hrefNow=$(this).attr('href');

						var product_id=idFromhref(hrefNow);

						if($(".prod_added_"+product_id).parent("a.product_image").parent(".cs_newarrival_p").length>0)

						{

							$(".prod_added_"+product_id).parent("a.product_image").parent(".cs_newarrival_p").removeClass("prod_hover_active")

						}

						else

							$(".prod_added_"+product_id).parent("a").removeClass("prod_hover_active");

						$(this).hide();

					})

					.bind('click', function() {

						$(this).hide();

					});

				//insert quickshop popup

				$('#cs_quickview_handler').fancybox({

						'width'				: CS.QuickView.QS_FRM_WIDTH,

						'height'			: CS.QuickView.QS_FRM_HEIGHT,

						'autoScale'			: true,

						'padding'			: 0,

						'margin'			: 20,

						'type'				: 'iframe',

						onComplete: function() { 

							$.fancybox.showActivity();

							$('#fancybox-frame').unbind('load');

							$('#fancybox-frame').bind('load', function() {

								$.fancybox.hideActivity();

							});

						}

				});

			}

		}

	}

	

	function idFromhref(hrefNow)

	{

		var arrHrefNow=hrefNow.split('&');

		var n=arrHrefNow.length;

		var strIdProd="id_product=1";

		var product_id=1;

		$.each(arrHrefNow, function(index, value) {

			if(value.indexOf('id_product')!=-1)

				strIdProd=arrHrefNow[index];

		});

		arrHrefNow=strIdProd.split('=');

		product_id=arrHrefNow[1];	

		return product_id;	

	}

	

	function getProductId(strhref,searstr){

		var pointstart=strhref.indexOf(searstr);

		var newstr=strhref.substring(pointstart);

		var result=newstr;

		while(newstr.indexOf("&")!=-1)

		{

			var pointfinish=newstr.indexOf("&");

			var newstr=newstr.substring(0,pointfinish);

			result=newstr;

		}

		var resultArr=result.split('=');

		return resultArr[1];	

	}

	//end base function

	function updateBlockCart()

	{

		setTimeout(function () {

			$('#shopping_cart a').css('border-radius', '3px');

			$("#cart_block").stop(true, true).slideDown(100);

			$("html, body").animate({ scrollTop: 0 },300);		

        },500);

		setTimeout(function () {

			$("#cart_block").stop(true, true).slideUp(500);

        },5000);

		

	}

	

	function isMobile() {

		if(navigator.userAgent.match(/Android/i) ||

			navigator.userAgent.match(/webOS/i) ||

			navigator.userAgent.match(/iPad/i) ||

			navigator.userAgent.match(/iPhone/i) ||

			navigator.userAgent.match(/iPod/i)

			){

				return true;

		}

		else return false;

	}

$(window).ready(function(){

	if(!isMobile())

	{

		_csJnit(IS,n);

	}

});







