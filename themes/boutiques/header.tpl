{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta http-equiv="content-language" content="{$meta_language}" />
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport"/>
        	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
				
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'/>
		
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		<script type="text/javascript">
			var baseDir = '{$content_dir|addslashes}';
			var baseUri = '{$base_uri|addslashes}';
			var static_token = '{$static_token|addslashes}';
			var token = '{$token|addslashes}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
			var priceDisplayMethod = {$priceDisplay};
			var roundMode = {$roundMode};
		</script>
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}

	<link href="{$css_dir}reponsive.css" rel="stylesheet" type="text/css" media="screen" />
	
	<link href="{$css_dir}cs.animate.css" rel="stylesheet" type="text/css" media="screen" />
	
{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}
<!--[if IE 7]><link href="{$css_dir}global-ie.css" rel="stylesheet" type="text/css" media="{$media}" /><![endif]-->
<!--[if IE 8]><link href="{$css_dir}cshometab1-ie8.css" rel="stylesheet" type="text/css" media="{$media}" /><![endif]-->

	<script type="text/javascript" src="{$js_dir}codespot/jquery.carouFredSel-6.2.1.js"></script>
	<script type="text/javascript" src="{$js_dir}codespot/getwidthbrowser.js"></script>
	<script type="text/javascript" src="{$js_dir}codespot/jquery.mousewheel.min.js"></script>
	<script type="text/javascript" src="{$js_dir}codespot/jquery.touchSwipe.min.js"></script>	
	
	<script type="text/javascript" src="{$js_dir}animate/jquery.appear.js"></script>
	<script type="text/javascript" src="{$js_dir}animate/smooth.js"></script>
	<script type="text/javascript" src="{$js_dir}animate/cs.script.js"></script>
	
	<script type="text/javascript" src="{$js_dir}codespot/jquery.ba-throttle-debounce.min.js"></script>
    <!--Adobe Edge Runtime-->
        <script type="text/javascript" charset="utf-8" src="/extremo/icon-deportes_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-27094677 { visibility:hidden; }
    </style>
    <!--Adobe Edge Runtime End-->
    
	{if $page_name == 'products-comparison'}
		<script type="text/javascript" src="{$js_dir}codespot/jquery.nicescroll.min.js"></script>
	{/if}
	{if $page_name != "index" && $page_name != "product"}<!--list - gird-->
		<script type="text/javascript" src="{$js_dir}codespot/list.gird.js"></script>
	{/if}
		{$HOOK_HEADER}
	</head>
	
	<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'htmlall':'UTF-8'}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if $content_only} content_only{/if}">
	{if !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
		<div id="restricted-country">
			<p>{l s='No se puede realizar un nuevo pedido desde su país.'} <span class="bold">{$geolocation_country}</span></p>
		</div>
		{/if}
		<div id="page">
			<!-- Header -->
			<div class="mode_header">
				<div class="mode_header_content">
					<div class="container_24">
						<div id="header" class="grid_24 clearfix omega alpha">
							<div id="header_left" class="grid_8 alpha">
								{if isset($HOOK_TOP_LEFT) && $HOOK_TOP_LEFT}{$HOOK_TOP_LEFT}{/if}
                                <div id="Stage" class="EDGE-27094677" style="background-color:#ebebeb;">
								</div>
							</div>
							<div id="header_content" class="grid_8">
								<a id="header_logo" href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
									<img class="logo" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" />
								</a>
								{if isset($HOOK_TOP_CONTENT) && $HOOK_TOP_CONTENT}{$HOOK_TOP_CONTENT}{/if}
							</div>
							<div id="header_right" class="grid_8 omega">
								{$HOOK_TOP}
							</div>			
						</div>
					</div>
				</div>
			</div>
				
			{if $page_name == 'index'}
				<div class="mode_slideshow">
					{if isset($HOOK_CS_SLIDESHOW) && $HOOK_CS_SLIDESHOW}
						{$HOOK_CS_SLIDESHOW}
					{/if}
				</div>
			{/if}
			<div class="mode_megamenu">
				<div class="container_24">
					{if isset($CS_MEGA_MENU) && $CS_MEGA_MENU}{$CS_MEGA_MENU}{/if}
				</div>
			</div>
			<div class="cs_mode_contain">
			<div class="mode_container">
				<div class="container_24">
				{if $page_name != 'index'}
					<!-- Breadcumb -->
					<script type="text/javascript">
						jQuery(document).ready(function() {
							if (jQuery("#old_bc").html()) {
								jQuery("#bc").html(jQuery("#old_bc").html());
								jQuery("#old_bc").hide();
							}
						});
					</script>
					<div class="bc_line">
						<div id="bc" class="breadcrumb"></div>
					</div>
				{/if}
				
				<div id="columns" class="{if isset($grid_column)}{$grid_column}{/if} grid_24 omega alpha">
				{if $page_name != 'index'}
					{if isset($settings)}
						{if ($settings->column == '2_column_left' || $settings->column == '3_column') }
							<!-- Left -->
							<div id="left_column" class="{if isset($settings)} {if $page_name == 'index'}{$settings->left_class_home} {else}{$settings->left_class}{/if} {else} grid_4{/if} alpha">
								{$HOOK_LEFT_COLUMN}
							</div>
						{/if}
					{else}
						<!-- Left -->
							<div id="left_column" class="grid_4 alpha">
								{$HOOK_LEFT_COLUMN}
							</div>
					{/if}
				{/if}

					<!-- Center -->
				{if $page_name == 'index'}
					<div id="center_column" class="{if isset($settings)} {if $page_name == 'index'}{$settings->center_class_home} {else}{$settings->center_class}{/if} {else} grid_24 omega alpha{/if}">
				{else}
					<div id="center_column" class="{if isset($settings)} {$settings->center_class}{else} grid_20 omega{/if}">
				{/if}
		{/if}
