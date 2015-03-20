{if $nbComments != 0}
<div class="productlistRating clearfix" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	{if $empty_grade==0}
	{section name="i" start=0 loop=5 step=1}
	{if $average_total le $smarty.section.i.index}
	<div class="star"></div>
	{else}

	<div class="star star_on"></div>
	{/if}
	{/section}
	{/if}

	<meta itemprop="worstRating" content = "0" />
	<meta itemprop="bestRating" content = "5" />
	<span class="hidden" itemprop="ratingValue">{$average_total}</span> 

	<span class="nb-comments" itemprop="reviewcount">{l s='%s Review(s)'|sprintf:$nbComments mod='ratingsproductlist'}</span>
</div>
{/if}