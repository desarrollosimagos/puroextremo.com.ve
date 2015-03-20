	<!-- MODULE manufacturer Products -->
<section class="page-product-box flexslider_carousel_block blockproductscategory">
	<h3 class="productscategory_h3 page-product-heading">{l s='Products from the same manufacturer' mod='productsmanufacturer'}</h3>
		{if isset($manufacturer_products) AND $manufacturer_products}
			{include file="$tpl_dir./product-slider.tpl" products=$manufacturer_products id='manufacturer_products_slider'}
		{else}
		<p>
			{l s='No featured products' mod='productsmanufacturer'}
		</p>
		{/if}
	</section>
	<!-- /MODULE manufacturer Products -->