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

{capture name=path}{l s='Mapa del sitio'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Mapa del sitio'}</h1>
<div id="sitemap_content" class="clearfix">
	<div class="sitemap_block">
		<h3>{l s='Nuestras ofertas'}</h3>
		<ul>
			<li><a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='Ver un nuevo producto'}">{l s='Ver un nuevo producto'}</a></li>
			{if !$PS_CATALOG_MODE}
			<li><a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='Ver los productos más vendidos'}">{l s='Los más vendidos'}</a></li>
			<li><a href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='Ver los productos con precios bajos'}">{l s='Precios bajos'}</a></li>
			{/if}
			{if $display_manufacturer_link OR $PS_DISPLAY_SUPPLIERS}<li><a href="{$link->getPageLink('manufacturer')|escape:'html'}" title="{l s='Ver una lista de los fabricantes'}">{l s='Fabricantes:'}</a></li>{/if}
			{if $display_supplier_link OR $PS_DISPLAY_SUPPLIERS}<li><a href="{$link->getPageLink('supplier')|escape:'html'}" title="{l s='Ver una lista de proveedores'}">{l s='Proveedores:'}</a></li>{/if}
		</ul>
	</div>
	<div class="sitemap_block">
		<h3>{l s='Su cuenta'}</h3>
		<ul>
		{if $logged}
			<li><a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Administrar su cuenta de cliente'}" rel="nofollow">{l s='Su cuenta'}</a></li>
			<li><a href="{$link->getPageLink('identity', true)|escape:'html'}" title="{l s='Gestione su información personal'}" rel="nofollow">{l s='Datos personales'}</a></li>
			<li><a href="{$link->getPageLink('addresses', true)|escape:'html'}" title="{l s='Ver una lista de mis direcciones'}" rel="nofollow">{l s='Direcciones'}</a></li>
			{if $voucherAllowed}<li><a href="{$link->getPageLink('discount', true)|escape:'html'}" title="{l s='Ver una lista de mis descuentos'}" rel="nofollow">{l s='Descuentos'}</a></li>{/if}
			<li><a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Ver una lista de mis pedidos'}" rel="nofollow">{l s='Historial de pedidos'}</a></li>
		{else}
			<li><a href="{$link->getPageLink('authentication', true)|escape:'html'}" title="{l s='Autenticación'}" rel="nofollow">{l s='Autenticación'}</a></li>
			<li><a href="{$link->getPageLink('authentication', true)|escape:'html'}" title="{l s='Crear una nueva cuenta'}" rel="nofollow">{l s='Crear una nueva cuenta'}</a></li>
		{/if}
		{if $logged}
			<li><a href="{$link->getPageLink('index')}?mylogout" title="{l s='Salir'}" rel="nofollow">{l s='Finalizar la sesión'}</a></li>
		{/if}
		</ul>
	</div>
	<br class="clear" />
</div>
<div id="listpage_content">
	<div class="categTree">
		<h3>{l s='Categorías'}</h3>
		<div class="tree_top"><a href="{$base_dir_ssl}" title="{$categoriesTree.name|escape:'htmlall':'UTF-8'}">{$categoriesTree.name|escape:'htmlall':'UTF-8'}</a></div>
		<ul class="tree">
		{if isset($categoriesTree.children)}
			{foreach $categoriesTree.children as $child}
				{if $child@last}
					{include file="$tpl_dir./category-tree-branch.tpl" node=$child last='true'}
				{else}
					{include file="$tpl_dir./category-tree-branch.tpl" node=$child}
				{/if}
			{/foreach}
		{/if}
		</ul>
	</div>
	<div class="categTree">
		<h3>{l s='Páginas'}</h3>
		<div class="tree_top"><a href="{$categoriescmsTree.link}" title="{$categoriescmsTree.name|escape:'htmlall':'UTF-8'}">{$categoriescmsTree.name|escape:'htmlall':'UTF-8'}</a></div>
		<ul class="tree">
			{if isset($categoriescmsTree.children)}
				{foreach $categoriescmsTree.children as $child}
					{if (isset($child.children) && $child.children|@count > 0) || $child.cms|@count > 0}
						{include file="$tpl_dir./category-cms-tree-branch.tpl" node=$child}
					{/if}
				{/foreach}
			{/if}
			{foreach from=$categoriescmsTree.cms item=cms name=cmsTree}
				<li><a href="{$cms.link|escape:'htmlall':'UTF-8'}" title="{$cms.meta_title|escape:'htmlall':'UTF-8'}">{$cms.meta_title|escape:'htmlall':'UTF-8'}</a></li>
			{/foreach}
			<li><a href="{$link->getPageLink('contact', true)|escape:'html'}" title="{l s='Contacto'}">{l s='Contacto'}</a></li>
			{if $display_store}<li class="last"><a href="{$link->getPageLink('stores')|escape:'html'}" title="{l s='Lista de nuestras tiendas'}">{l s='Nuestras tiendas'}</a></li>{/if}
		</ul>
	</div>
</div>
