{if $is_16}
	<section class="page-product-box">
		<h3 class="page-product-heading">{l s='Related posts' mod='ph_relatedposts'}</h3>
		<div class="ph_simpleblog simpleblog-related-posts">
			<div class="row simpleblog-posts">
				{foreach from=$posts item=post}

					{assign var='cols' value='col-md-6 col-xs-6 col-ms-12'}
					{assign var='columns' value=Configuration::get('PH_RELATEDPOSTS_GRID_COLUMNS')}

					{if $columns eq '3'}
						{assign var='cols' value='col-md-4 col-xs-4 col-ms-12'}
					{/if}

					{if $columns eq '4'}
						{assign var='cols' value='col-md-3 col-xs-3 col-ms-12'}
					{/if}

					<div class="simpleblog-post-item {$cols}">

						<div class="post-item">

							{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
								<div class="post-thumbnail">
									<a href="{$post.url}" title="{l s='Permalink to' mod='ph_simpleblog'} {$post.meta_title}">
										{if $blogLayout eq 'full'}
											<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
										{else}
											<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
										{/if}
									</a>
								</div>
							{/if}

							<div class="post-content">
								<h2>
									<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
								</h2>
								{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
									{$post.short_content}
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
{else}
	<div id="idTab669">
		<div class="ph_simpleblog simpleblog-related-posts">
			<div class="row simpleblog-posts">
				{foreach from=$posts item=post}

					{assign var='cols' value='col-md-6 col-xs-6 col-ms-12'}
					{assign var='columns' value=Configuration::get('PH_RELATEDPOSTS_GRID_COLUMNS')}

					{if $columns eq '3'}
						{assign var='cols' value='col-md-4 col-xs-4 col-ms-12'}
					{/if}

					{if $columns eq '4'}
						{assign var='cols' value='col-md-3 col-xs-3 col-ms-12'}
					{/if}

					<div class="simpleblog-post-item {$cols}">

						<div class="post-item">

							{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
								<div class="post-thumbnail">
									<a href="{$post.url}" title="{l s='Permalink to' mod='ph_simpleblog'} {$post.meta_title}">
										{if $blogLayout eq 'full'}
											<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
										{else}
											<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
										{/if}
									</a>
								</div>
							{/if}

							<div class="post-content">
								<h2>
									<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
								</h2>
								{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
									{$post.short_content}
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
	</div>
{/if}
<script>
$(window).load(function() {
	$('body').addClass('simpleblog-related-exists');

	$('#more_info_tabs a.relatedPostsTab').on('click', SimpleBlogEqualHeight );
});

// $(window).load(  SimpleBlogEqualHeight  );
// $(window).resize(SimpleBlogEqualHeight);

function SimpleBlogEqualHeight()
{
  	var mini = 0;
  	$('.simpleblog-post-item .post-item').each(function(){
      	if(parseInt($(this).css('height')) > mini )
      	{
        	mini = parseInt($(this).css('height'));
      	}  
  	});

  	$('.simpleblog-post-item .post-item').css('height', mini+40);  
}
</script>