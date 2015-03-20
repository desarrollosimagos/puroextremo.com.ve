{if isset($categories) AND $categories}
{foreach from=$categories item=category}

<!-- MODULE Home category Products -->
<section id="categories-products_block_center_mod_{$category.id}" class="block products_block flexslider_carousel_block clearfix">
	<h4>{$category.name}</h4>
	{if isset($category.products) AND $category.products}
	<script type="text/javascript"> createCategorySlider('#categoryslider_slider_{$category.id}'); </script>
	{assign var='tmpCatSlider' value="categoryslider_slider_{$category.id}"}
	{if isset($category.productimg)}
	{include file="$tpl_dir./product-slider.tpl" products=$category.products productimg=$category.productimg id=$tmpCatSlider}
	{else}
	{include file="$tpl_dir./product-slider.tpl" products=$category.products id=$tmpCatSlider}
	{/if}
	{else}
	<p>
		{l s='No products' mod='categoryslider'}
	</p>
	{/if}
</section>
<!-- /MODULE Home category Products -->
{/foreach}
{/if}