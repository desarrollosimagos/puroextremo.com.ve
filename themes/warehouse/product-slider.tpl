	{if isset($products) && $products}
	<div class="block_content">
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbLi' value=$products|@count}

		<div {if isset($id) && $id} id="{$id}"{/if}  class="flexslider_carousel">
			<ul class="slides">
				{foreach from=$products item=product name=homeFeaturedProducts}
				<li class="ajax_block_product {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{else}item{/if} {if $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 1} {/if} {if $smarty.foreach.homeFeaturedProducts.iteration > ($smarty.foreach.homeFeaturedProducts.total - ($smarty.foreach.homeFeaturedProducts.total % $nbItemsPerLine))}last_line{/if}">
					<div class="product-container" itemscope itemtype="http://schema.org/Product">
						
						<div class="product-image-container">
						<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							{if isset($productimg[$product.id_product]) && !empty($productimg[$product.id_product])}
							<img class="replace-2x img-responsive img_0" src="{$link->getImageLink($product.link_rewrite,$product.id_product|cat:"-"|cat:$productimg[$product.id_product].0.id_image, 'home_default')|escape:'html':'UTF-8'}
							" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />	
							{if isset($productimg[$product.id_product].1) && !empty($productimg[$product.id_product].1)}
							<img class="replace-2x img-responsive img_1" src="{$link->getImageLink($product.link_rewrite,$product.id_product|cat:"-"|cat:$productimg[$product.id_product].1.id_image, 'home_default')|escape:'html':'UTF-8'}
							" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if}  />	
							{/if} {else}
							<img class="replace-2x img-responsive img_0" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />{/if}
						</a>
						<div class="product-flags">
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							{if isset($product.online_only) && $product.online_only}
								<span class="online-label {if isset($product.new) && $product.new == 1}online-label2{/if}">{l s='Online only'}</span>
							{/if}
						{/if}
						{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
							{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
								<span class="sale-label">{l s='Reduced price!'}</span>
							{/if}
						{if isset($product.new) && $product.new == 1}
								<span class="new-label">{l s='New'}</span>
						{/if}
						{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
								<span class="sale-label">{l s='Sale!'}</span>
						{/if}
					</div>
					<div class="functional-buttons functional-buttons-grid clearfix">
						{if isset($quick_view) && $quick_view}
						<div class="quickview col-xs-6">
							<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}">
								{l s='Quick view'}
							</a>
							</div>
						{/if}
						{hook h='displayProductListFunctionalButtons' product=$product}
						{if isset($comparator_max_item) && $comparator_max_item}
							<div class="compare col-xs-3">
								<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to Compare'}">{l s='Add to Compare'}</a>
							</div>
						{/if}	
					</div>
					{if ($id!='crossseling_products_slider') AND ($id!='crossseling_popup_products_slider') }
					{if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
							<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="availability availability-slidein">
								{if ($product.allow_oosp || $product.quantity > 0)}
										<link itemprop="availability" href="http://schema.org/InStock" />
								{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
									<span class="out-of-stock available-dif">
										<link itemprop="availability" href="http://schema.org/LimitedAvailability" />{l s='Product available with different options'}
									</span>
								{else}
									<span class="out-of-stock">
										<link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock'}
									</span>
								{/if}
							</span>
						{/if}
					{/if}{/if}
					</div><!-- .product-image-container> -->
					{hook h="displayProductDeliveryTime" product=$product}
					{hook h="displayProductPriceBlock" product=$product type="weight"}
					<h5 itemprop="name" class="product-name-container">
						{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
					<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							{$product.name|truncate:50:'...'|escape:'html':'UTF-8'}
						</a>
					</h5>
					{if isset($product.reference)}<span class="product-reference">{$product.reference}</span>{/if}
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price">
						{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
							<span itemprop="price" class="price product-price">
								{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
							</span>
							<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
							{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
								{hook h="displayProductPriceBlock" product=$product type="old_price"}
								<span class="old-price product-price">
									{displayWtPrice p=$product.price_without_reduction}
								</span>
								{if $product.specific_prices.reduction_type == 'percentage'}
									<span class="price-percent-reduction small">-{$product.specific_prices.reduction * 100}%</span>
								{/if}
								{if $product.specific_prices.reduction_type == 'amount'}
									<span class="price-percent-reduction small">-{convertPrice price=$product.specific_prices.reduction}</span>
								{/if}
							{/if}
									{hook h="displayProductPriceBlock" product=$product type="price"}
									{hook h="displayProductPriceBlock" product=$product type="unit_price"}
						{/if}
					</div>
					{elseif $PS_CATALOG_MODE}
					{else}<div class="content_price">&nbsp;</div>
					{/if}
					{hook h='displayProductListReviews' product=$product}

					{if isset($warehouse_vars.yotpo_stars)  && $warehouse_vars.yotpo_stars == 1}
					<div class="yotpo bottomLine" 
					data-appkey="{$yotpoAppkey}"
					data-domain="{$yotpoDomain}"
					data-product-id="{$product.id_product}"
					data-product-models=""
					data-name="{$product.name|escape:'htmlall':'UTF-8'}" 
					data-url="{$product.link|escape:'htmlall':'UTF-8'}" 
					data-image-url="{$link->getImageLink($product.link_rewrite, $product.id_image, '')}" 
					data-bread-crumbs="">
				</div> 
				{/if}
				
					{if ($id!='crossseling_products_slider') AND ($id!='crossseling_popup_products_slider') AND ($id!='manufacturer_products_slider')   AND ($id!='accessories_slider') AND ($id!='category_products_slider') }
					<div class="button-container">
						{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if ($product.allow_oosp || $product.quantity > 0)}
								{if isset($static_token)}
									<a class="button ajax_add_to_cart_button btn" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
										<span>{l s='Add to cart'}</span>
									</a>
								{else}
									<a class="button ajax_add_to_cart_button btn " href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
										<span>{l s='Add to cart'}</span>
									</a>
								{/if}						
							{else}
								<a itemprop="url" class="button lnk_view btn" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}">
							<span>{l s='More'}</span>
						</a>
							{/if}
							{else}
								<a itemprop="url" class="button lnk_view btn" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}">
							<span>{l s='More'}</span>
						</a>
						{/if}
						
					</div>
					{/if}
					</div><!-- .product-container> -->


					</li>
					{/foreach}
				</ul>
			</div>
		</div>

{/if}