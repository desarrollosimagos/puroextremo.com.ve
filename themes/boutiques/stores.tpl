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

{capture name=path}{l s='Nuestras tiendas'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Nuestras tiendas'}</h1>

{if $simplifiedStoresDiplay}
	{if $stores|@count}
	<p>{l s='Aquí usted puede encontrar nuestras tiendas. No dude en ponerse en contacto con nosotros:'}</p>
	{foreach $stores as $store}
		<div class="store-small grid_2">
			{if $store.has_picture}<p><img src="{$img_store_dir}{$store.id_store}-medium_default.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" /></p>{/if}
			<p>
				<b>{$store.name|escape:'htmlall':'UTF-8'}</b><br />
				{$store.address1|escape:'htmlall':'UTF-8'}<br />
				{if $store.address2}{$store.address2|escape:'htmlall':'UTF-8'}{/if}<br />
				{$store.postcode} {$store.city|escape:'htmlall':'UTF-8'}{if $store.state}, {$store.state}{/if}<br />
				{$store.country|escape:'htmlall':'UTF-8'}<br />
				{if $store.phone}{l s='Teléfono:' js=0} {$store.phone}{/if}
			</p>
				{if isset($store.working_hours)}{$store.working_hours}{/if}
		</div>
	{/foreach}
	{/if}
{else}
	<script type="text/javascript">
		// <![CDATA[
		var map;
		var markers = [];
		var infoWindow;
		var locationSelect;

		var defaultLat = '{$defaultLat}';
		var defaultLong = '{$defaultLong}';
		
		var translation_1 = '{l s='No se encontraron tiendas. Por favor, intente seleccionar un radio más amplio.' js=1}';
		var translation_2 = '{l s='tienda encontrados -- ver detalles:' js=1}';
		var translation_3 = '{l s='tiendas encontradas -- Ver todos los resultados:' js=1}';
		var translation_4 = '{l s='Teléfono:' js=1}';
		var translation_5 = '{l s='Obtener ruta' js=1}';
		var translation_6 = '{l s='No se ha encontrado' js=1}';
		
		var hasStoreIcon = '{$hasStoreIcon}';
		var distance_unit = '{$distance_unit}';
		var img_store_dir = '{$img_store_dir}';
		var img_ps_dir = '{$img_ps_dir}';
		var searchUrl = '{$searchUrl}';
		var logo_store = '{$logo_store}';
		//]]>
	</script>

	<p>{l s='Escriba una ubicación (por ejemplo zip / código postal, dirección, ciudad o país) con el fin de encontrar las tiendas más cercanas.'}</p>
	<p>
		<label for="addressInput">{l s='Su ubicación:'}</label>
		<input type="text" name="location" id="addressInput" value="{l s='Dirección, zip / código postal, ciudad, estado o país'}" onclick="this.value='';" />
	</p>
	<p>
		<label for="radiusSelect">{l s='Radio:'}</label> 
		<select name="radius" id="radiusSelect">
			<option value="15">15</option>
			<option value="25">25</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select> {$distance_unit}
		<img src="{$img_ps_dir}loader.gif" class="middle" alt="" id="stores_loader" />
	</p>
	<p class="clearfix">
		<input type="button" class="button" onclick="searchLocations();" value="{l s='Buscar'}" style="display: inline;" /> 
	</p>
	<div><select id="locationSelect"><option></option></select></div>
    <div id="map"></div>
	<table cellpadding="0" cellspacing="0" border="0" id="stores-table" class="table_block">
		<tr>
			<th>{l s='#'}</th>
			<th>{l s='Tienda'}</th>
			<th>{l s='Dirección'}</th>
			<th>{l s='Distancia'}</th>
		</tr>		
	</table>
{/if}
