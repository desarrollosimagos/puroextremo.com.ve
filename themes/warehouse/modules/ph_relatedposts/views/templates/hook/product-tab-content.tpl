<section class="page-product-box tab-pane fade" id="idTab669">
		<div class="ph_simpleblog simpleblog-related-posts">
			<div class="row simpleblog-posts">
				{foreach from=$posts item=post}

					{assign var='cols' value='col-xs-12 col-ms-6 col-sm-6'}
					{assign var='columns' value=Configuration::get('PH_RELATEDPOSTS_GRID_COLUMNS')}
					{if $columns eq '3'}
					{assign var='cols' value='col-xs-12 col-ms-6 col-sm-4'}
					{/if}

					{if $columns eq '4'}
					{assign var='cols' value='col-xs-12 col-ms-6 col-sm-3'}
					{/if}

					<div class="simpleblog-post-item {$cols}">

						<div class="post-item">

						{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
						<div class="post-thumbnail">
							<a href="{$post.url}" title="{l s='Permalink to' mod='ph_recentposts'} {$post.meta_title}">
								{if $blogLayout eq 'full'}
									<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
								{else}
									<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
								{/if}
							</a>
						<h2>
							<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
						</h2>
						</div>
					{/if}
						{if !isset($post.banner) || !Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
					<div class="post-title">
				
						<h2>
							<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
						</h2>
						
					</div>	{/if}
					<div class="post-content">
						
						{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
						<a href="{$post.url}" title="{$post.short_content}">
							{$post.short_content}
							</a>
						{/if}
					</div>	


							<div class="post-additional-info">
								{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
									<span class="post-date">
										{l s='Posted on:' mod='ph_simpleblog'} {$post.date_add|date_format:Configuration::get('PH_BLOG_DATEFORMAT')}
									</span>
								{/if}

								{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
									<span class="post-category">
										{l s='Posted in:' mod='ph_simpleblog'} <a href="{$post.category_url}" title="">{$post.category}</a>
									</span>
								{/if}

								{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
									<span class="post-author">
										{l s='Author:' mod='ph_simpleblog'} {$post.author}
									</span>
								{/if}

								{if isset($post.tags) && $post.tags && Configuration::get('PH_BLOG_DISPLAY_TAGS')}
									<span class="post-tags clear">
										{l s='Tags:' mod='ph_simpleblog'} 
										{foreach from=$post.tags item=tag name='tagsLoop'}
											{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
										{/foreach}
									</span>
								{/if}
							</div><!-- .additional-info -->
						</div>
					</div><!-- .simpleblog-post-item -->

				{/foreach}
			</div><!-- .ph_row -->
		</div><!-- .ph_simpleblog.related-posts -->
	</section>

	<script>

	$(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
		var target = $(e.target).attr("href");
		if ((target == '#idTab669')) {
		SimpleBlogEqualHeight();
}
})

$( window ).resize(function() {
SimpleBlogEqualHeight();
});


function SimpleBlogEqualHeight()
{
  	var mini = 0;
  	//	$('.simpleblog-post-item .post-item').removeAttr( 'style' );
  	$('.simpleblog-post-item .post-item .post-content').removeAttr( 'style' );
  	$('.simpleblog-post-item .post-item').each(function(){
      	if(parseInt($(this).height()) > mini )
      	{
        	mini = parseInt($(this).height());
      	}  
      	

  	});
  	  	$('.simpleblog-post-item .post-item').each(function(){
  	$(this).find('.post-content').height($(this).find('.post-content').height()+(mini - $(this).height()));
  	});
  	$('.simpleblog-post-item .post-item').parent().css('height',mini+10);  
}
</script>