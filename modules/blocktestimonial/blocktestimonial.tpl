<!-- Block testimonial module -->
<div id="block_testimonials" class="block">
	<h4><i>{l s='Testimonials' mod='blockTestimonial'}</i></h4>
	    <div id="testimonials">
    		{if isset($testims)}		  
			{foreach from=$testims item=nr}	
				<div class="testimonial-item">
	    			<div class="testimonial-title">
	    				<!--<a href="{$this_path}testimonials.php#{$nr.testimonial_id}">-->{$nr.testimonial_title}</div>
	    			<div class="testimonial-body">{$nr.testimonial_main_message}</div>	    			
	    			<div class="testimonial-author">{$nr.testimonial_submitter_name}</div>	    			
	    			<div class="testimonial-date">{$nr.date_added|truncate:13:''}</div>
	    		</div>
    		{/foreach}
    		{/if}
	    </div>
   	<div class="blocktestimonial">
	    <a class="button testimonial-view" href="{$this_path}testimonials.php">{l s='View All' mod='blockTestimonial'}</a>
	    <a class="button testimonial-add" href="{$this_path}addtestimonial.php">{l s='Add Your' mod='blockTestimonial'}</a>
	</div>
</div>
<!-- /Block testimonial module -->
