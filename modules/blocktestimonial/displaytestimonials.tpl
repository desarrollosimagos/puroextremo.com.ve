<!-- Block testimonial module -->
<link rel="stylesheet" type="text/css" href="{$this_path}/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="{$this_path}/fancybox/jquery.fancybox-1.2.6.pack.js"></script>
<div id="block_testimonials">
	<h3>{l s='Testimonials' mod='blockTestimonial'}</h3>	
	<div id="testimonials">
		{if isset($testimonials)}				  
			{foreach from=$testimonials item=nr}				  
				<div class="testimonial">
					<strong class="testimonialhead">{$nr.testimonial_title}</strong>
					<div id="text">
						<div class="testimonialbody">
						{if $nr.testimonial_img != NULL}
							<a class="zoom" ALIGN="left" href="//{$http_host}{$base_dir}{$nr.testimonial_img}">
								<img class="testimonialImage" src="//{$http_host}{$base_dir}{$nr.testimonial_img}" height="50" width="50" />
							</a>
							{/if}
							{$nr.testimonial_main_message}
						</div>
					</div>
					<ul>
						<li>{l s='Submitted By:' mod='blockTestimonial'} {$nr.testimonial_submitter_name}</li>
						<li>{l s='Submitted Date:' mod='blockTestimonial'} {$nr.date_added|strip_tags}
					</ul>
				</div>
			{/foreach}
		{else}
			<h1>{l s='No Testimonials Yet!' mod='blockTestimonial'}</h1>
		{/if}
	</div>
	<div id="paginationTop">
		{if $currentpage > 1}
			<a href='{$smarty.server.PHP_SELF}?currentpage=1'>{l s=' << Last' mod='blockTestimonial'}</a>
			{* show < link to go back to 1 page *}
			<a href='{$smarty.server.PHP_SELF}?currentpage={$prevpage}'>{l s=' < Previous' mod='blockTestimonial'}</a>			
		{/if}		  
		[{$currentpage}]
		{if $currentpage != $totalpages}	    
			<a href='{$smarty.server.PHP_SELF}?currentpage={$nextpage}'>{l s='Next >' mod='blockTestimonial'}</a>
			<a href='{$smarty.server.PHP_SELF}?currentpage={$totalpages}'>{l s='Last >>' mod='blockTestimonial'}</a>
		{/if}
	</div>
	<div class="addblocktestimonial">
		<a class="button addblocktestimonial" href="{$this_path}addtestimonial.php">Write Testimonial</a>
	</div>
</div> <!-- /end paginationTop div -->
<script type="text/javascript">
	{literal}
	$(document).ready(function() {
		$("a.zoom").fancybox();
	});
	{/literal}
</script>
<!-- /Block testimonial module -->