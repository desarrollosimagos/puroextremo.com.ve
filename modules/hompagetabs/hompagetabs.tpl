	<!-- MODULE Home Tabs Products -->
	<section id="homepagetabs_module" class="homepagetabs_module_list block  clearfix">


		<ul class="nav nav-tabs">
			{if isset($fproducts) AND $fproducts}
			<li> 
				<a href="#featured_products_tab" title="{l s='Featured products' mod='hompagetabs'}" data-toggle="tab">{l s='Featured products' mod='hompagetabs'}</a>
			</li>
			{/if}
			{if isset($nproducts) AND $nproducts}
			<li>
				<a href="#new_products_tab" title="{l s='New products' mod='hompagetabs'}" data-toggle="tab">{l s='New products' mod='hompagetabs'}</a>
			</li>
			{/if}
			{if isset($special) AND $special}
			<li>
				<a href="#special_products_tab"  title="{l s='Price drops' mod='hompagetabs'}" data-toggle="tab">{l s='Price drops' mod='hompagetabs'}</a>
			</li>
			{/if}
			{if isset($categories) AND $categories}
			{foreach from=$categories item=category}
			{if isset($category.products) AND $category.products}
			<li>
				<a href="#category_products_tab_{$category.id}" data-toggle="tab">{$category.name}</a>
			</li>
			{/if}
			{/foreach}
			{/if}
		</ul>

		<div class="tab-content">
			{if isset($fproducts) AND $fproducts}
			<div id="featured_products_tab"  class="tab-pane fade">
				{if isset($fproducts_productimg)}
				{include file="$tpl_dir./product-list.tpl" productimg=$fproducts_productimg products=$fproducts}
				{else}
				{include file="$tpl_dir./product-list.tpl" products=$fproducts}
				{/if}
			</div>
			{/if}

			{if isset($nproducts) AND $nproducts}
			<div id="new_products_tab"  class="tab-pane fade">
				{if isset($nproducts_productimg)}
				{include file="$tpl_dir./product-list.tpl" productimg=$nproducts_productimg products=$nproducts}
				{else}
				{include file="$tpl_dir./product-list.tpl" products=$nproducts}
				{/if}
			</div>
			{/if}

			{if isset($special) AND $special}

			<div id="special_products_tab"  class="tab-pane fade">
				{if isset($sproducts_productimg)}
				{include file="$tpl_dir./product-list.tpl" productimg=$sproducts_productimg products=$special}
				{else}
				{include file="$tpl_dir./product-list.tpl" products=$special}
				{/if}	
			</div>
			{/if}

			{if isset($categories) AND $categories}
			{foreach from=$categories item=category}

			{if isset($category.products) AND $category.products}

			<div id="category_products_tab_{$category.id}"  class="tab-pane fade">
				{if isset($category.productimg)}
				{include file="$tpl_dir./product-list.tpl" products=$category.products productimg=$category.productimg}
				{else}	
				{include file="$tpl_dir./product-list.tpl" products=$category.products}
				{/if}	
			</div>
			{/if}
			
			{/foreach}
			{/if}
		</div>

	</section>
	<!-- /MODULE Homepage tabs  Products -->
