<!-- CS Manufacturer module -->
<div class="manufacturerContainer">
<div class="list_manufacturer responsive">
		<ul id="scroller">
		{$i=0}
		{foreach from=$manufacs item=manufacturer name=manufacturer_list}
			{$i=$i+1}
			{if file_exists($ps_manu_img_dir|cat:$manufacturer.id_manufacturer|cat:'.jpg')}
			<li class="not-animated {if $smarty.foreach.product_list.first}first_item{elseif $smarty.foreach.product_list.last}last_item{/if}" data-animate="bounceIn" data-delay="{$i*150}">
				<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}" title="{$manufacturer.name|escape:'htmlall':'UTF-8'}">
				<img src="{$img_manu_dir}{$manufacturer.id_manufacturer|escape:'htmlall':'UTF-8'}-manu_default.jpg" alt="{$manufacturer.name|escape:'htmlall':'UTF-8'}" /></a>
			</li>
			{/if}
		{/foreach}
		</ul>
			<a id="prev_cs_manu" class="prev btn" href="#">&lt;</a>
			<a id="next_cs_manu" class="next btn" href="#">&gt;</a>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#scroller").carouFredSel({
			auto: false,
			responsive: true,
				width: '100%',
				height:'variable',
				onWindowResize: 'debounce',
				prev: '#prev_cs_manu',
				next: '#next_cs_manu',
				swipe: {
					onTouch : true
				},
				items: {
					width: 160,
					height:'auto',
					visible: {
						min: 2,
						max: 10
					}
				},
				scroll: {
					items : 3 ,       //  The number of items scrolled.
					direction : 'left',    //  The direction of the transition.
					duration  : 500   //  The duration of the transition.
				}

		});
	});
</script>
<!-- /CS Manufacturer module -->

