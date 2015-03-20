<div class="container">
{assign var='gridSize' value=12/$number_per_line}
<section id="homepageadvertise2" class="row clearfix">
	<ul>
		{foreach from=$images item=image key=i}
		<li class="col-sm-{$gridSize}">
			{if isset($image.link) AND $image.link}<a href="{$image.link}">{/if}
				
				{if isset($image.name) AND $image.name}
				{assign var="imgLink" value="{$modules_dir}homepageadvertise2/slides/{$image.name}"}
				<img src="{$link->getMediaLink($imgLink)|escape:'html'}"  alt="{$image.name}" >
				{/if}	

			{if isset($image.link) AND $image.link}</a>{/if}
		</li>
			{/foreach}
		</ul>
</section>
</div>