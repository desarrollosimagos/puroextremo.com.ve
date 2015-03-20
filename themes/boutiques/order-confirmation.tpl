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

{capture name=path}{l s='Confirmación del pedido'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Confirmación del pedido'}</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{include file="$tpl_dir./errors.tpl"}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}

<br />
{if $is_guest}
	<p>{l s='Su Id. de pedido es:'} <span class="bold">{$id_order_formatted}</span> . {l s='Su Id. de pedido se ha enviado por correo electrónico.'}</p>
	<a href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order}&email={$email}")|escape:'html'}" title="{l s='Seguir mi pedido'}"><img src="{$img_dir}icon/order.gif" alt="{l s='Seguir mi pedido'}" class="icon" /></a>
	<a href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order}&email={$email}")|escape:'html'}" title="{l s='Seguir mi pedido'}">{l s='Seguir mi pedido'}</a>
{else}
	<a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Volver a los pedidos'}"><img src="{$img_dir}icon/order.gif" alt="{l s='Volver a los pedidos'}" class="icon" /></a>
	<a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Volver a los pedidos'}">{l s='Volver a los pedidos'}</a>
{/if}
