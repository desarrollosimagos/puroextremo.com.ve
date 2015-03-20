<!-- CS Home Tab module -->
{if count($tabs) > 0}
<div class="home_top_tab">
<div id="tabs">
	<ul id="ul_cs_tab">
		{foreach from=$tabs item=tab name=tabs}
			<li class="{if $smarty.foreach.tabs.last}last{/if} refreshCarousel">
				<h4 class="title_block"><span><a class="g_bm1_t" href="#tabs-{$smarty.foreach.tabs.iteration}">{$tab->title[(int)$cookie->id_lang]}</a></span></h4>
			</li>
		{/foreach}
	</ul>
	{foreach from=$tabs item=tab name=tabs}
		<div class="title_tab_hide_show" style="display:none">
			{$tab->title[(int)$cookie->id_lang]}
		</div>
	<div class="tabs-carousel" id="tabs-{$smarty.foreach.tabs.iteration}">
		<div class="cycleElementsContainer" id="cycle-{$smarty.foreach.tabs.iteration}">	
			<div id="elements-{$smarty.foreach.tabs.iteration}">
				{if $tab->product_list && count($tab->product_list)>0}
				<div class="list_carousel responsive">
					<ul id="carousel{$smarty.foreach.tabs.iteration}" class="product-list">
					{$i=0}
					{foreach from=$tab->product_list item=product name=product_list}
						
						{$i=$i+1}
						{if $i%2==1}
						<li class="not-animated ajax_block_product {if $smarty.foreach.product_list.first}first_item{/if}{if ($i+1)==count($tab->product_list)}last_item{/if}{if ($i+1)%($option->show*2)==0} last_item_of_line{/if} item" style="{if $option->scrollPanel !='true'}width:220px{/if}" data-animate="fadeInDown" data-delay="{$i*50}">
						{/if}
							{if $i%2==1}
								{if isset($product.id_product)}
								<div class="cs_newarrival_p product-1">
									<a href="{$product.link}" title="" class="product_image outside"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'p_slider_home')}" alt="{$product.name|escape:html:'UTF-8'}" />
									</a>
									<div class="cs_hide">								
										<div class="cs_new_bottom">
										<div class="name_product">
											<h3><a href="{$product.link}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h3>
										</div>	
										{if $product.ratting>0}	
											<div class="star_content clearfix">
												{section name="i" start=0 loop=5 step=1}
													{if $product.ratting le $smarty.section.i.index}
														<div class="star"></div>
													{else}
														<div class="star star_on"></div>
													{/if}
												{/section}
											</div>
										{/if}
											<div class="products_list_price">
												{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
												
													{if $priceDisplay && $product.reduction}
														{if $product.specific_prices}
															<span class="price-discount">
																{convertPrice price=$product.price_without_reduction}
															</span>
														{/if}
													{/if}
													
													<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
												{/if}
											</div>	
										</div>											
									</div>
								</div>
								{/if}
							{/if}
							{if $i%2==0}
								{if isset($product.id_product)}
								<div class="cs_newarrival_p product-2">
									<a href="{$product.link}" title="" class="product_image outside"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'p_slider_home')}" alt="{$product.name|escape:html:'UTF-8'}" />
									</a>
									<div class="cs_hide">										
										<div class="cs_new_bottom">
										<div class="name_product">
											<h3><a href="{$product.link}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h3>
										</div>
											{if $product.ratting>0}
											<div class="star_content clearfix">
												{section name="i" start=0 loop=5 step=1}
													{if $product.ratting le $smarty.section.i.index}
														<div class="star"></div>
													{else}
														<div class="star star_on"></div>
													{/if}
												{/section}
											</div>
											{/if}
											<div class="products_list_price">
												{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
												
													{if $priceDisplay && $product.reduction}
														{if $product.specific_prices}
															<span class="price-discount">
																{convertPrice price=$product.price_without_reduction}
															</span>
														{/if}
													{/if}
													
													<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
												{/if}
											</div>
										</div>
									</div>
								</div>
								{/if}
							{/if}
						{if $i%2==0 || $i==count($tab->product_list)}
						</li>
						{/if}
						
					{/foreach}
					</ul>
					<div class="cclearfix"></div>		
					<a id="prev{$smarty.foreach.tabs.iteration}" class="bt prev" href="#">&lt;</a>
					<a id="next{$smarty.foreach.tabs.iteration}" class="bt next" href="#">&gt;</a>
				</div>
				{/if}
			</div>
		</div>
	</div>
	{/foreach}
</div>
<script type="text/javascript">
	$(document).ready(function() {
		initCarousel();
		cs_resize();
	});

	$(window).resize(function() {
		if(!isMobile())
		{
			cs_resize();
		}
	});
	
	function cs_resize(){
		if(getWidthBrowser() < 767){
			$('#tabs').tabs('destroy');
			$('#ul_cs_tab').hide();
			$('#tabs div.title_tab_hide_show').show();
			
		} else {		
			$('#tabs').tabs({ fx: { opacity: 'toggle' }});	
			$('.tabs-carousel').show();
			$('#ul_cs_tab').show();
			$('#tabs div.title_tab_hide_show').hide();
		}
	}
	function initCarousel() {
			{foreach from=$tabs item=tab name=tabs}
			//	Responsive layout, resizing the items
			$('#carousel{$smarty.foreach.tabs.iteration}').carouFredSel({
				responsive: true,
				width: '100%',
				height: 'variable',
				onWindowResize: 'debounce',
				prev: '#prev{$smarty.foreach.tabs.iteration}',
				next: '#next{$smarty.foreach.tabs.iteration}',
				auto: false,
				swipe: {
					onTouch : true
				},
				items: {
					width:290,
					height: 'auto',	//	optionally resize item-height
					visible: {
						min: 1,
						max: 5
					}
				},
				scroll: {
					items:2,
					direction : 'left',    
					duration  : 500 , 
					onBefore: function(data) {
						 if(touch == false){
							var that = $("#carousel{$smarty.foreach.tabs.iteration}");
							var items = that.find('.not-animated');
							items.removeClass('not-animated').unbind('appear');
							items = that.find('.animated');
							items.removeClass('animated').unbind('appear');
						  }
					}
				}
			});
			{/foreach}
	}

	function isMobile() 
	{
		if(navigator.userAgent.match(/(iPhone)|(iPod)/i)){
				return true;
		}
		else
		{
			return false;
		}
		
	}
	function isIpad() 
	{
		if(navigator.userAgent.match(/iPad/i)){
				return true;
		}
		else
		{
			return false;
		}
		
	}
	function unAnimated() 
	{
		{foreach from=$tabs item=tab name=tabs}
		 if(touch == false){
			var that = $("#carousel{$smarty.foreach.tabs.iteration}");
			var items = that.find('.not-animated');
			items.removeClass('not-animated').unbind('appear');
		  }
		{/foreach}
	}
	
</script>
</div>
{/if}

<!-- /CS Home Tab module -->
