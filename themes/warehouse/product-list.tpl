{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($products) && $products}
	{*define numbers of product per line in other page for desktop*}






	{capture name="nbItemsPerLineLarge"}{hook h='calculateGrid' size='large'}{/capture}
	{capture name="nbItemsPerLine"}{hook h='calculateGrid' size='medium'}{/capture}
	{capture name="nbItemsPerLineTablet"}{hook h='calculateGrid' size='small'}{/capture}
	{capture name="nbItemsPerLineMobile"}{hook h='calculateGrid' size='mediumsmall'}{/capture}

	

	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$smarty.capture.nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$smarty.capture.nbItemsPerLineTablet assign=nbLinesTablet}

	{if (isset($yotpo_stars_pl)  && $yotpo_stars_pl == 1) || (isset($warehouse_vars.yotpo_stars)  && $warehouse_vars.yotpo_stars == 1)}
<script src="https://www.yotpo.com/js/yQuery.js"></script>

<script type="text/javascript">
{literal}
	$(document).ready(function()
{
	yQuery('.yotpo').yotpo(); 
});
{/literal}
</script>
{/if}
	<!-- Products list -->
	<ul{if isset($id) && $id} id="{$id}"{/if} class="product_list grid row{if isset($class) && $class} {$class}{/if}">
	{foreach from=$products item=product name=products}
		{math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$smarty.capture.nbItemsPerLine assign=totModulo}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$smarty.capture.nbItemsPerLineTablet assign=totModuloTablet}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$smarty.capture.nbItemsPerLineMobile assign=totModuloMobile}
		{if $totModulo == 0}{assign var='totModulo' value=$smarty.capture.nbItemsPerLine}{/if}
		{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$smarty.capture.nbItemsPerLineTablet}{/if}
		{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$smarty.capture.nbItemsPerLineMobile}{/if}
		<li class="ajax_block_product col-xs-12 col-ms-{$smarty.capture.nbItemsPerLineMobile} col-sm-{$smarty.capture.nbItemsPerLineTablet} col-md-{$smarty.capture.nbItemsPerLine} col-lg-{$smarty.capture.nbItemsPerLineLarge} {if $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$smarty.capture.nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}">
			<div class="product-container" itemscope itemtype="http://schema.org/Product">
				<div class="left-block">
					<div class="product-image-container">
						<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							{if isset($productimg[$product.id_product]) && !empty($productimg[$product.id_product])}
							<img class="replace-2x img-responsive img_0" src="{$link->getImageLink($product.link_rewrite,$product.id_product|cat:"-"|cat:$productimg[$product.id_product].0.id_image, 'home_default')|escape:'html':'UTF-8'}
							" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />	
							{if isset($productimg[$product.id_product].1) && !empty($productimg[$product.id_product].1)}
							<img class="replace-2x img-responsive img_1" src="{$link->getImageLink($product.link_rewrite,$product.id_product|cat:"-"|cat:$productimg[$product.id_product].1.id_image, 'home_default')|escape:'html':'UTF-8'}
							" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if}  />	
							{/if} {else}
							<img class="replace-2x img-responsive img_0" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
						{/if}
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
					{if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
							<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="availability availability-slidein">
								{if ($product.allow_oosp || $product.quantity > 0)}
										<link itemprop="availability" href="http://schema.org/InStock" />
								{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
									<span class="available-dif">
										<link itemprop="availability" href="http://schema.org/LimitedAvailability" />{l s='Product available with different options'}
									</span>
								{else}
									<span class="out-of-stock">
										<link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock'}
									</span>
								{/if}
								
							</span>
						{/if}
					{/if}
			

					</div>
					{hook h="displayProductDeliveryTime" product=$product}
					{hook h="displayProductPriceBlock" product=$product type="weight"}
				</div>
				<div class="right-block">
					<h5 itemprop="name" class="product-name-container">
						{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
						<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
							{$product.name|truncate:50:'...'|escape:'html':'UTF-8'}
						</a>
					</h5>
					{if isset($product.reference)}<span class="product-reference">{$product.reference}</span>{/if}
					<p class="product-desc" itemprop="description">
						{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
					</p>
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

					{if (isset($yotpo_stars_pl)  && $yotpo_stars_pl == 1) || (isset($warehouse_vars.yotpo_stars)  && $warehouse_vars.yotpo_stars == 1)}
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
					{if isset($product.color_list)}
						<div class="color-list-container">{$product.color_list} </div>
					{/if}
				</div>
			</div><!-- .product-container> -->
		</li>
	{/foreach}
	</ul>

{/if}