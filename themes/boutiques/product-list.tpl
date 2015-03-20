{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products)}
	<ul id="product_list" class="clear {if isset($smarty.cookies.display_class)}{$smarty.cookies.display_class}{/if}">
	
	{foreach from=$products item=product name=products}
		<li class="not-animated {if isset($grid_product)}{$grid_product}{elseif isset($smarty.cookies.grid_product)}{$smarty.cookies.grid_product}{else}grid_5{/if} ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if} clearfix" data-animate="fadeInDown" data-delay="{$smarty.foreach.products.index*50}">
			<div class="center_block">				
				<div class="image"><a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
					{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale"><span>{l s='venta de descuento'}</span></span>{/if}
				</a>
				
				</div>
				<div class="name_product"><h3><a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'|truncate:45:'...'}</a></h3></div>
				{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}{if ($product.allow_oosp || $product.quantity > 0)}
					<span class="availability">{l s='Disponible'}</span>{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}<span class="availability">{l s='Producto disponible con otras opciones'}</span>{else}
					<span class="cs_out_of_stock">{l s='Agotado'}</span>{/if}
				{/if}
				{if isset($product.online_only) && $product.online_only}
					<span class="online_only">{l s='¡Solo por Internet!'}</span>
				{/if}
				
				<p class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:90:'...'}</p>
				{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
				<div class="content_price">
					{if $product.reduction}<span class="price-discount">{convertPrice price=$product.price_without_reduction}</span>{/if}
					{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}<span class="price{if $product.reduction} old{/if}" style="display: inline;">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{/if}
					
				</div>
				
				{/if}
				
			{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
					{if ($product.allow_oosp || $product.quantity > 0)}
						{if isset($static_token)}
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Añadir a la cesta'}">{l s='Añadir a la cesta'}</a>
						{else}
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}", false)}" title="{l s='Añadir a la cesta'}">{l s='Añadir a la cesta'}</a>
						{/if}						
					{else}
						<span class="exclusive">{l s='Agotado'}</span>
					{/if}
				{/if}	
				{if isset($comparator_max_item) && $comparator_max_item}
					<p class="compare">
						<input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
						<label for="comparator_item_{$product.id_product}">{l s='Marca y compara'}</label>
					</p>
				{/if}
			</div>
		</li>
	{/foreach}
	</ul>
	<!-- /Products list -->
	
{/if}
