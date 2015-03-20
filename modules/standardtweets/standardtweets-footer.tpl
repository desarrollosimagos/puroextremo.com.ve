<section id="standardtweets_module" class="footer-block col-xs-12 col-sm-3">
	<div>
		<h4>{l s='Last tweets' mod='standardtweets'}</h4>
		<ul class="toggle-footer">
			<li>
				<a class="twitter-timeline" href="https://twitter.com/{$username}" data-widget-id="{$widgetid}">Tweets by @{$username}</a>
				<script>{literal}!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");{/literal}</script>
			</li></ul></div>
</section>
