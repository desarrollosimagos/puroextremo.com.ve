        <section id="columnadverts" class="block">
			<p class="title_block">{l s='Check also' mod='columnadverts'}</p>
	<div class="block_content clearfix">
        	<ul>

        		{foreach from=$climages item=image key=i}
        		<li>
        			{if isset($image.link) AND $image.link}

        			<a href="{$image.link}">
        				{/if}
        				{if isset($image.name) AND $image.name}
        				{assign var="imgLink" value="{$modules_dir}columnadverts/slides/{$image.name}"}
        				<img src="{$link->getMediaLink($imgLink)|escape:'html'}"  alt="{$image.name}" >
        				{/if}	

        				{if isset($image.link) AND $image.link}
        			</a>    
        			{/if}</li>
        			{/foreach}
        		</ul><!-- ei-slider-large -->
</div>
        	</section><!-- ei-slider -->




