
	<!-- MODULE Home Specials Products -->

	{if isset($special)}
	<section id="specials-products_block_center_mod" class="block products_block flexslider_carousel_block clearfix">
		<h4>{l s='Price drops' mod='specialslider'}</h4>
	
			{include file="$tpl_dir./product-slider.tpl" products=$special id='specials_products_slider'}
		
	</section>
	{/if}

	<!-- /MODULE specials -->
