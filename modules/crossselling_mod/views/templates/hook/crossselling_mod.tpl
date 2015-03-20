{if isset($orderProducts) && count($orderProducts)}
    <section id="crossselling" class="page-product-box flexslider_carousel_block">
    	<h3 class="productscategory_h2 page-product-heading">
            {if $page_name == 'product'}
                {l s='Customers who bought this product also bought:' mod='crossselling_mod'}
            {else}
                {l s='We recommend' mod='crossselling_mod'}
            {/if}
        </h3>
        {include file="$tpl_dir./product-slider.tpl" products=$orderProducts id='crossseling_products_slider'}
    </section>
{/if}