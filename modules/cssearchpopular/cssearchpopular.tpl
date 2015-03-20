<!-- MODULE search popular -->
{if $searchList}
<div class="block_popular_word_search clearfix">
	<div class="block_content">
	{foreach from=$searchList item=search name=searchList}
	<a href="{$link->getPageLink('search', true, NULL, "search_query={$search.word|urlencode}")}">{$search.word}</a>
	<span>.</span>
	{/foreach}
	</div>
</div>
{/if}
<!-- MODULE search popular -->