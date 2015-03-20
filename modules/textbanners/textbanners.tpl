<!-- Text banners -->
{if isset($textbanners_banners)}
{assign var='gridSize' value=12/$textbanners_perline}
<section id="textbannersmodule" class="row clearfix {if !$textbanners_border}no_borders{/if} {if $textbanners_style==1}iconleft{/if}">
<ul>
{foreach from=$textbanners_banners item=banner key=i}
	{if $banner.active}
		<li class="col-xs-12 col-sm-{$gridSize} {if ($i+1) % $textbanners_perline == 0} last-item{/if}"><div class="txtbanner txtbanner{$banner.id_banner} clearfix">
		{if isset($banner.icon) && $banner.icon!=''}<div class="circle"><i class="{$banner.icon}"></i></div>{/if}
		{if isset($banner.url) AND $banner.url !="" }<a href="{$banner.url|escape:'htmlall':'UTF-8'}">{/if}
			 <span class="txttitle">{$banner.title|escape:'htmlall':'UTF-8'}</span>
            <span class="txtlegend">{$banner.legend|escape:'htmlall':'UTF-8'}</span>
            {if isset($banner.url) AND $banner.url !="" }</a>{/if}</div></li>
	{/if}
{/foreach}
</ul>
</section>
{/if}
<!-- /Text banners -->
