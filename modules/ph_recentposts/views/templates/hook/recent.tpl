{if isset($posts) && count($posts) > 0}
<section class="ph_simpleblog simpleblog-recent block">
	<p class="title_block">{l s='Recent posts' mod='ph_recentposts'}</p>
	<div class="row simpleblog-posts">
		{foreach from=$posts item=post}

			{assign var='cols' value='col-md-6 col-xs-12 col-sm-6'}
			{assign var='columns' value=Configuration::get('PH_RECENTPOSTS_GRID_COLUMNS')}
			{if $columns eq '3'}
				{assign var='cols' value='col-md-4 col-xs-12 col-sm-6'}
			{/if}

			{if $columns eq '4'}
				{assign var='cols' value='col-md-3 col-xs-12 col-sm-6'}
			{/if}

			<article class="simpleblog-post-item {if $blogLayout eq 'grid'}{$cols}{else}col-md-12{/if}">

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
								{l s='Posted on:' mod='ph_recentposts'} {$post.date_add|date_format:Configuration::get('PH_BLOG_DATEFORMAT')}
							</span>
						{/if}

						{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
							<span class="post-category">
								{l s='Posted in:' mod='ph_recentposts'} <a href="{$post.category_url}" title="">{$post.category}</a>
							</span>
						{/if}

						{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
							<span class="post-author">
								{l s='Author:' mod='ph_recentposts'} {$post.author}
							</span>
						{/if}

						{if isset($post.tags) && $post.tags && Configuration::get('PH_BLOG_DISPLAY_TAGS')}
							<span class="post-tags clear">
								{l s='Tags:' mod='ph_recentposts'} 
								{foreach from=$post.tags item=tag name='tagsLoop'}
									{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
								{/foreach}
							</span>
						{/if}
					</div><!-- .additional-info -->
				</div>
			</article><!-- .simpleblog-post-item -->

		{/foreach}
	</div><!-- .ph_row -->
</section><!-- .ph_simpleblog -->
{else}
	<p class="warning">{l s='There are no posts' mod='ph_recentposts'}</p>
{/if}
<script>
$(window).load(  SimpleBlogEqualHeight  );
$(window).resize(SimpleBlogEqualHeight);

function SimpleBlogEqualHeight()
{
  	var mini = 0;
  	$('.simpleblog-post-item .post-item').each(function(){
      	if(parseInt($(this).css('height')) > mini )
      	{
        	mini = parseInt($(this).css('height'));
      	}  
  	});

  	$('.simpleblog-post-item .post-item').css('height', mini);  
}
</script>