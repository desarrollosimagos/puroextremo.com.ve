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
{include file="./errors.tpl"}
{if isset($orderRet)}
	<p class="title_block">{l s='RE#'}<span class="color-myaccount">{$orderRet->id|string_format:"%06d"}</span> {l s='on'} {dateFormat date=$order->date_add full=0}</p>
	<div>
		<p class="bold">{l s='Hemos registrado su solicitud de devolución.'}</p>
		<p>{l s='Su paquete debe ser devuelto en el plazo de'} {$nbdaysreturn} {l s='días de recibir el pedido.'}</p>
		<p>{l s='El estado actual de su retorno de la mercancía es:'} <span class="bold">{$state_name|escape:'htmlall':'UTF-8'}</span></p>
		<p>{l s='Lista de artículos que se devolverán:'}</p>
	</div>
	<div id="order-detail-content" class="table_block">
		<table class="std">
			<thead>
				<tr>
					<th class="first_item">{l s='Referencia'}</th>
					<th class="item">{l s='Producto'}</th>
					<th class="last_item">{l s='Cantidad'}</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$products item=product name=products}

				{assign var='quantityDisplayed' value=0}
				{foreach from=$returnedCustomizations item='customization' name=products}
					{if $customization.product_id == $product.product_id}
						<tr class="{if $smarty.foreach.products.first}first_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
							<td>{if $customization.reference}{$customization.reference|escape:'htmlall':'UTF-8'}{else}--{/if}</td>
							<td class="bold">{$customization.name|escape:'htmlall':'UTF-8'}</td>
							<td><span class="order_qte_span editable">{$customization.product_quantity|intval}</span></td>
						</tr>
						{assign var='productId' value=$customization.product_id}
						{assign var='productAttributeId' value=$customization.product_attribute_id}
						{assign var='customizationId' value=$customization.id_customization}
						{foreach from=$customizedDatas.$productId.$productAttributeId.$customizationId.datas key='type' item='datas'}
							<tr class="alternate_item">
								<td colspan="3">
									{if $type == $smarty.const._CUSTOMIZE_FILE_}
									<ul class="customizationUploaded">
										{foreach from=$datas item='data'}
											<li><img src="{$pic_dir}{$data.value}_small" alt="" class="customizationUploaded" /></li>
										{/foreach}
									</ul>
									{elseif $type == $smarty.const._CUSTOMIZE_TEXTFIELD_}
									<ul class="typedText">{counter start=0 print=false}
										{foreach from=$datas item='data'}
											{assign var='customizationFieldName' value="Text #"|cat:$data.id_customization_field}
											<li>{l s='%s:' sprintf=$data.name|default:$customizationFieldName} {$data.value}</li>
										{/foreach}
									</ul>
									{/if}
								</td>
							</tr>
						{/foreach}
						{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.product_quantity}
					{/if}
				{/foreach}

				{if $product.product_quantity > $quantityDisplayed}
					<tr class="{if $smarty.foreach.products.first}first_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
						<td>{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</td>
						<td class="bold">{$product.product_name|escape:'htmlall':'UTF-8'}</td>
						<td><span class="order_qte_span editable">{$product.product_quantity|intval}</span></td>
					</tr>
				{/if}
			{/foreach}
			</tbody>
		</table>
	</div>

	{if $orderRet->state == 2}
	<p class="bold">{l s='Recordatorio:'}</p>
	<div>
		- {l s='Toda la mercancía debe ser devuelto en su embalaje y estado original.'}
		<br />- {l s='Por favor imprima la'} <a href="{$link->getPageLink('pdf-order-return', true, NULL, "id_order_return={$orderRet->id|intval}")|escape:'html'}">{l s='PDF recibo de retorno'}</a> {l s='e incluir con su paquete.'}
		<br />- {l s='Por favor, vea el recibo de retorno en PDF'} (<a href="{$link->getPageLink('pdf-order-return', true, NULL, "id_order_return={$orderRet->id|intval}")|escape:'html'}">{l s='para la dirección correcta'}</a>)
		<br /><br />
		{l s='Cuando recibamos su paquete, se lo notificaremos por correo electrónico. Entonces comenzaremos el reembolso del pedido.'}
		<br /><br /><a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='Por favor, háganos saber si usted tiene alguna pregunta.'}</a>
		<br />
		<p class="bold">{l s='Si no se respetan las condiciones de retorno que aparece más arriba, nos reservamos el derecho de rechazar el paquete y / o el reembolso.'}</p>
	</div>
	{elseif $orderRet->state == 1}
		<p class="bold">{l s='Usted debe esperar la confirmación antes de devolver cualquier mercancía.'}</p>
	{/if}
{/if}

