
                <section id="ei-slider-fw-wrapper">
                <div id="ei-slider-fw" class="flexslider loading_mainslider">
                    <ul class="slides">
									
											{foreach from=$images item=image key=i}
											<li>
							{if isset($image.link) AND $image.link}

            <a href="{$image.link}">
		{/if}
							{if isset($image.name) AND $image.name}
							{assign var="imgLink" value="{$modules_dir}simpleslideshow/slides/{$image.name}"}
			<img src="{$link->getMediaLink($imgLink)|escape:'html'}"  alt="{$image.name}" >
		{/if}	
						
								{if isset($image.link) AND $image.link}
			</a>    
		{/if}</li>
                  	{/foreach}
                    </ul><!-- ei-slider-large -->
										
										



</div>
                </section><!-- ei-slider -->

 
				

