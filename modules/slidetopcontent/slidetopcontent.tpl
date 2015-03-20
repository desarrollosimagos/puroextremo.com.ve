<!-- Module slidetopcontent -->
<div id="slidetopcontent" class="slidetopcontent">
	<div class="container">
		<ul class="row clearfix">

			{if $homepage_logo}<li class="col-sm-4"><img src="{$link->getMediaLink($image_path)|escape:'html'}" {if $slidetopcontent->main_title}alt="{$slidetopcontent->main_title|stripslashes}" {/if}/></li>{/if}
			<li class="col-sm-{if $homepage_logo}4{else}6{/if}">

				{if $slidetopcontent->main_title}<h4>{$slidetopcontent->main_title|stripslashes}</h4>{/if}
				{if $slidetopcontent->main_paragraph}<div class="rte">{$slidetopcontent->main_paragraph|stripslashes}</div>{/if}
				{if $slidetopcontent->main_link}<div class="rte"><a class="btn btn-default button button-small pull-right" href="{$slidetopcontent->main_link|stripslashes}"><span>{l s='Read more' mod='slidetopcontent'}</span></a></div>{/if}

			</li>
			<li class="col-sm-{if $homepage_logo}4{else}6{/if}">

				{if $slidetopcontent->second_title}<h4>{$slidetopcontent->second_title|stripslashes}</h4>{/if}
				{if $slidetopcontent->second_paragraph}<div class="rte">{$slidetopcontent->second_paragraph|stripslashes}</div>{/if}
				{if $slidetopcontent->second_link}<div class="rte"><a class="btn btn-default button button-small pull-right" href="{$slidetopcontent->second_link|stripslashes}"><span>{l s='Read more' mod='slidetopcontent'}</span></a></div>{/if}
			</li>
		</ul>
	</div></div>
	<!-- /Module slidetopcontent -->