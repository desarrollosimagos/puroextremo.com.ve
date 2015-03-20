<section id="productpageadverts" class="flexslider loading_mainslider">
	<ul class="slides">
		{foreach from=$climages item=image key=i}
		<li>
			{if isset($image.link) AND $image.link}
			<a href="{$image.link}">
				{/if}
				{if isset($image.name) AND $image.name}
				{assign var="imgLink" value="{$modules_dir}productpageadverts/slides/{$image.name}"}
				<img src="{$link->getMediaLink($imgLink)|escape:'html'}"  alt="{$image.name}" >
				{/if}	

				{if isset($image.link) AND $image.link}
			</a>    
			{/if}</li>
			{/foreach}
		</ul>
</section>