<!-- Block links module -->
<section id="blocklink_mod" class="footer-block col-xs-12 col-sm-3">
	<div>
		<h4>{if $url}
			<a href="{$url|escape}">{$title|escape}</a>
			{else}
			{$title|escape}
			{/if}</h4>
			<ul class="toggle-footer bullet clearfix">

				{foreach from=$blocklink_links item=blocklink_link}
				{if isset($blocklink_link.$lang)} 
				<li><a href="{$blocklink_link.url|escape}"{if $blocklink_link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$blocklink_link.$lang|escape}</a></li>
				{/if}
				{/foreach}
			</ul>
		</div>
	</section>
	<!-- /Block links module -->
