	<!-- MODULE Home Featured Products -->
	<section id="featured-products_block_center_mod" class="block products_block flexslider_carousel_block clearfix">
		<h4>{l s='Featured products' mod='homefeatured_mod'}</h4>
		{if isset($products) AND $products}
			{include file="$tpl_dir./product-slider.tpl" products=$products id='featured_products_slider'}
		{else}
		<p>
			{l s='No featured products' mod='homefeatured_mod'}
		</p>
		{/if}
	</section>
	<!-- /MODULE Home Featured Products -->
