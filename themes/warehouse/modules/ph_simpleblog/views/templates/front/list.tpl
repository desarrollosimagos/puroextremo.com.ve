<script>
var currentBlog = '{if $is_category}category{else}home{/if}';
</script>
{if Configuration::get('PH_BLOG_DISPLAY_BREADCRUMBS')}
	{capture name=path}
		<a href="{ph_simpleblog::getLink()}">{l s='Blog' mod='ph_simpleblog'}</a>
		{if $is_category eq true}
			<span class="navigation-pipe">{$navigationPipe}</span>{$blogCategory->name}
		{/if}
	{/capture}
	{if !$is_16}{include file="$tpl_dir./breadcrumb.tpl"}{/if}
{/if}

{if isset($posts) && count($posts) > 0}
<div class="ph_simpleblog simpleblog-{if $is_category}category{else}home{/if}">
	{if $is_category eq true}
		<h1 class="page-heading">{$blogCategory->name}</h1>

		{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY_IMAGE') && isset($blogCategory->image)}
		<div class="simpleblog-category-image">
			<img src="{$blogCategory->image}" alt="{$blogCategory->name}" class="img-responsive" />
		</div>
		{/if}

		{if !empty($blogCategory->description) && Configuration::get('PH_BLOG_DISPLAY_CAT_DESC')}
		<div class="ph_cat_description">
			{$blogCategory->description}
		</div>
		{/if}
	{else}
		<h1 class="page-heading">{$blogMainTitle}</h1>
	{/if}

	<div class="row simpleblog-posts">

		{foreach from=$posts item=post}

			{assign var='cols' value='col-xs-12 col-ms-6 col-md-6'}

			{if $columns eq '3'}
					{assign var='cols' value='col-xs-12 col-ms-6 col-md-4'}
			{/if}

			{if $columns eq '4'}
				{assign var='cols' value='col-xs-12 col-ms-6 col-md-3'}
			{/if}

			<div class="simpleblog-post-item {if $blogLayout eq 'grid'}{$cols}{else}col-md-12{/if}">

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


					<div class="post-additional-info clearfix">
						{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
							<span class="post-date">
								{l s='Posted on:' mod='ph_simpleblog'} {$post.date_add|date_format:Configuration::get('PH_BLOG_DATEFORMAT')}
							</span>
						{/if}

						{if $is_category eq false && Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
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
	</div><!-- .row -->
		
	{if $is_category}
		{include file="./pagination.tpl" rewrite=$blogCategory->link_rewrite type='category'}
	{else}
		{include file="./pagination.tpl" rewrite=false type=false}
	{/if}
</div><!-- .ph_simpleblog -->
<script>
$(window).load(function() {
	$('body').addClass('simpleblog simpleblog-'+currentBlog);
});
{if $blogLayout eq 'grid'}


$(window).load(function() {
SimpleBlogEqualHeight();
	});

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
{/if}
</script>
{else}
	<p class="warning alert alert-warning">{l s='There are no posts' mod='ph_simpleblog'}</p>
{/if}