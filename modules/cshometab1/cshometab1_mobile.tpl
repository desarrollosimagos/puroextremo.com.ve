<!-- CS Home Tab module -->
<div class="home_top_tab">
{if count($tabs) > 0}
<div id="tabs">
	{foreach from=$tabs item=tab name=tabs}
		<div class="title_tab_hide_show">
			{$tab->title[(int)$cookie->id_lang]}
		</div>
	<div class="tabs-carousel" id="tabs-{$smarty.foreach.tabs.iteration}">
		<div class="cycleElementsContainer" id="cycle-{$smarty.foreach.tabs.iteration}">	
			<div id="elements-{$smarty.foreach.tabs.iteration}">
				{if $tab->product_list}
				<div class="list_carousel responsive">
					<ul id="carousel{$smarty.foreach.tabs.iteration}" class="product-list">
					{$i=0}
					{foreach from=$tab->product_list item=product name=product_list}
						{$i=$i+1}
						<li class="ajax_block_product {if $smarty.foreach.product_list.first}first_item{/if}{if ($i+1)==count($tab->product_list)}last_item{/if}{if ($i+1)%($option->show)==0} last_item_of_line{/if} item" style="{if $option->scrollPanel !='true'}width:220px{/if}">
			
							<div class="cs_newarrival_p">
								<a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image outside"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'p_slider_home')}" alt="{$product.name|escape:html:'UTF-8'}" />
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
															{displayWtPrice p=$product.price_without_reduction}
														</span>
													{/if}
												{/if}
												
												<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
											{/if}
										</div>	
									</div>											
								</div>
							</div>
						</li>
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
		cs_resize();
	});

	function cs_resize(){
		if(getWidthBrowser() < 767){
			initCarouselMobile();
		} else {
			initCarousel();
		}
	}
	
	function initCarousel() {
			{foreach from=$tabs item=tab name=tabs}
			//	Responsive layout, resizing the items
			$('#carousel{$smarty.foreach.tabs.iteration}').carouFredSel({
				responsive: true,
				width: '100%',
				height: 'variable',
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
					direction : 'left',    //  The direction of the transition.
					duration  : 500   //  The duration of the transition.
				}
			});
			{/foreach}
	}
	
	function initCarouselMobile() {
			{foreach from=$tabs item=tab name=tabs}
			//	Responsive layout, resizing the items
			$('#carousel{$smarty.foreach.tabs.iteration}').carouFredSel({
				responsive: true,
				width: '100%',
				height: 396,	//	optionally resize item-height
				prev: '#prev{$smarty.foreach.tabs.iteration}',
				next: '#next{$smarty.foreach.tabs.iteration}',
				auto: false,
				swipe: {
					onTouch : true
				},
				items: {
					width: 320,
					height: 396,	//	optionally resize item-height
					visible: {
						min: 1,
						max: 2
					}
				},
				scroll: {
					items:1,
					direction : 'left',    //  The direction of the transition.
					duration  : 400   //  The duration of the transition.
				}
			});
			{/foreach}
	}
</script>
{/if}
</div>
<!-- /CS Home Tab module -->
