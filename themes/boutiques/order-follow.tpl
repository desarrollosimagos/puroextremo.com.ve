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

{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Mi cuenta'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Autorización de Devolución de Mercancía (RMA)'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Autorización de Devolución de Mercancía (RMA)'}</h1>
{if isset($errorQuantity) && $errorQuantity}<p class="error">{l s='Usted no tiene suficientes productos para solicitar una devolución de la mercancía adicional.'}</p>{/if}
{if isset($errorMsg) && $errorMsg}
	<p class="error">
		{l s='Por favor, dar una explicación de su RMA.'}
	</p>
	<p>
		<h2>{l s='Por favor, dar una explicación de su RMA:'}</h2>
		<form method="POST"  id="returnOrderMessage">
			<p class="textarea">
				<textarea name="returnText"></textarea>
			</p>
			{foreach $ids_order_detail as $id_order_detail}
				<input type="hidden" name="ids_order_detail[{$id_order_detail}]" value="{$id_order_detail}"/>
			{/foreach}
			{foreach $order_qte_input as $key => $value}
				<input type="hidden" name="order_qte_input[{$key}]" value="{$value}"/>
			{/foreach}
			<input type="hidden" name="id_order" value="{$id_order}"/>
			<input class="button_large" type="submit" name="submitReturnMerchandise" value="{l s='Hacer un recibo RMA'}"/>
		</form>
	</p>
{/if}
{if isset($errorDetail1) && $errorDetail1}<p class="error">{l s='Por favor marque al menos un producto que le gustaría devolver.'}</p>{/if}
{if isset($errorDetail2) && $errorDetail2}<p class="error">{l s='Para cada producto que desea añadir, por favor, especifique la cantidad deseada.'}</p>{/if}
{if isset($errorNotReturnable) && $errorNotReturnable}<p class="error">{l s='Este pedido no se puede devolver.'}</p>{/if}

<p>{l s='Here is a list of pending merchandise returns'}.</p>
<div class="block-center" id="block-history">
	{if $ordersReturn && count($ordersReturn)}
	<table id="order-list" class="std">
		<thead>
			<tr>
				<th class="first_item">{l s='Retorno'}</th>
				<th class="item">{l s='Pedido'}</th>
				<th class="item">{l s='Estado del Paquete'}</th>
				<th class="item">{l s='fecha de emisión'}</th>
				<th class="last_item">{l s='recibo de retorno'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$ordersReturn item=return name=myLoop}
			<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
				<td class="bold"><a class="color-myaccount" href="javascript:showOrder(0, {$return.id_order_return|intval}, '{$link->getPageLink('order-return', true)|escape:'html'}');">{l s='#'}{$return.id_order_return|string_format:"%06d"}</a></td>
				<td class="history_method"><a class="color-myaccount" href="javascript:showOrder(1, {$return.id_order|intval}, '{$link->getPageLink('order-detail', true)|escape:'html'}');">{$return.reference}</a></td>
				<td class="history_method"><span class="bold">{$return.state_name|escape:'htmlall':'UTF-8'}</span></td>
				<td class="bold">{dateFormat date=$return.date_add full=0}</td>
				<td class="history_invoice">
				{if $return.state == 2}
					<a href="{$link->getPageLink('pdf-order-return', true, NULL, "id_order_return={$return.id_order_return|intval}")|escape:'html'}" title="{l s='retorno del pedido'} {l s='#'}{$return.id_order_return|string_format:"%06d"}"><img src="{$img_dir}icon/pdf.gif" alt="{l s='retorno del pedido'} {l s='#'}{$return.id_order_return|string_format:"%06d"}" class="icon" /></a>
					<a href="{$link->getPageLink('pdf-order-return', true, NULL, "id_order_return={$return.id_order_return|intval}")|escape:'html'}" title="{l s='retorno del pedido'} {l s='#'}{$return.id_order_return|string_format:"%06d"}">{l s='imprimir'}</a>
				{else}
					--
				{/if}
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div id="block-order-detail" class="hidden">&nbsp;</div>
	{else}
		<p class="warning">{l s='No tiene autorización de devolución de mercancía.'}</p>
	{/if}
</div>

<ul class="footer_links">
	<li><a href="{$link->getPageLink('my-account', true)}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Volver a su cuenta'}</a></li>
	<li class="f_right"><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Inicio'}</a></li>
</ul>
