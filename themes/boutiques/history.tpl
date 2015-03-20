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

{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Mi cuenta'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Historial de pedidos'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
{include file="$tpl_dir./errors.tpl"}

<h1>{l s='Historial de pedidos'}</h1>
<p>{l s='Estos son los pedidos realizados desde su cuenta.'}</p>

{if $slowValidation}<p class="warning">{l s='Si sólo tiene un pedido, puede tardar algunos minutos para que pueda ser validado. Por favor, volver a cargar esta página si su orden no se encuentra.'}</p>{/if}

<div class="block-center" id="block-history">
	{if $orders && count($orders)}
	<table id="order-list" class="std">
		<thead>
			<tr>
				<th class="first_item">{l s='Referencia de pedido'}</th>
				<th class="item">{l s='Fecha'}</th>
				<th class="item">{l s='Precio total'}</th>
				<th class="item">{l s='Pago: '}</th>
				<th class="item">{l s='Estado'}</th>
				<th class="item">{l s='Factura'}</th>
				<th class="last_item" style="width:65px">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$orders item=order name=myLoop}
			<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
				<td class="history_link bold">
					{if isset($order.invoice) && $order.invoice && isset($order.virtual) && $order.virtual}<img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Products to download'}" title="{l s='Products to download'}" />{/if}
					<a class="color-myaccount" href="javascript:showOrder(1, {$order.id_order|intval}, '{$link->getPageLink('order-detail', true)|escape:'html'}');">{Order::getUniqReferenceOf($order.id_order)}</a>
				</td>
				<td class="history_date bold">{dateFormat date=$order.date_add full=0}</td>
				<td class="history_price"><span class="price">{displayPrice price=$order.total_paid currency=$order.id_currency no_utf8=false convert=false}</span></td>
				<td class="history_method">{$order.payment|escape:'htmlall':'UTF-8'}</td>
				<td class="history_state">{if isset($order.order_state)}{$order.order_state|escape:'htmlall':'UTF-8'}{/if}</td>
				<td class="history_invoice">
				{if (isset($order.invoice) && $order.invoice && isset($order.invoice_number) && $order.invoice_number) && isset($invoiceAllowed) && $invoiceAllowed == true}
					<a href="{$link->getPageLink('pdf-invoice', true, NULL, "id_order={$order.id_order}")|escape:'html'}" title="{l s='Invoice'}" class="_blank"><img src="{$img_dir}icon/pdf.gif" alt="{l s='Invoice'}" class="icon" /></a>
					<a href="{$link->getPageLink('pdf-invoice', true, NULL, "id_order={$order.id_order}")|escape:'html'}" title="{l s='Invoice'}" class="_blank">{l s='PDF'}</a>
				{else}-{/if}
				</td>
				<td class="history_detail">
					<a class="color-myaccount" href="javascript:showOrder(1, {$order.id_order|intval}, '{$link->getPageLink('order-detail', true)|escape:'html'}');">{l s='Detalles'}</a>
					{if isset($opc) && $opc}
					<a href="{$link->getPageLink('order-opc', true, NULL, "submitReorder&id_order={$order.id_order}")|escape:'html'}" title="{l s='Reorder'}">
					{else}
					<a href="{$link->getPageLink('order', true, NULL, "submitReorder&id_order={$order.id_order}")|escape:'html'}" title="{l s='Reorder'}">
					{/if}
						<img src="{$img_dir}arrow_rotate_anticlockwise.png" alt="{l s='Reorder'}" title="{l s='Reorder'}" class="icon" />
					</a>
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div id="block-order-detail" class="hidden">&nbsp;</div>
	{else}
		<p class="warning">{l s='No ha realizado ningún pedido.'}</p>
	{/if}
</div>

<ul class="footer_links clearfix">
	<li><a href="{$link->getPageLink('my-account', true)|escape:'html'}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /> {l s='Volver a su cuenta'}</a></li>
	<li class="f_right"><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /> {l s='Inicio'}</a></li>
</ul>