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

{capture name=path}{l s='My account'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Mi cuenta'}</h1>
{if isset($account_created)}
	<p class="success">
		{l s='Su cuenta ha sido creada.'}
	</p>
{/if}
<p class="title_block">{l s='Bienvenido a su cuenta. Aquí usted puede administrar su información personal y los pedidos.'}</p>
<ul class="myaccount_lnk_list">
	{if $has_customer_an_address}
	<li><a href="{$link->getPageLink('address', true)|escape:'html'}" title="{l s='Add my first address'}"><img src="{$img_dir}icon/addrbook.gif" alt="{l s='Add my first address'}" class="icon" /> {l s='Añadir mi primera dirección'}</a></li>
	{/if}
	<li><a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Orders'}"><img src="{$img_dir}icon/order.gif" alt="{l s='Orders'}" class="icon" /> {l s='Historia y detalle del pedido'}</a></li>
	{if $returnAllowed}
		<li><a href="{$link->getPageLink('order-follow', true)|escape:'html'}" title="{l s='Merchandise returns'}"><img src="{$img_dir}icon/return.gif" alt="{l s='Merchandise returns'}" class="icon" /> {l s='Mis devoluciones de mercancía'}</a></li>
	{/if}
	<li><a href="{$link->getPageLink('order-slip', true)|escape:'html'}" title="{l s='Credit slips'}"><img src="{$img_dir}icon/slip.gif" alt="{l s='Credit slips'}" class="icon" /> {l s='Mis hojas de crédito'}</a></li>
	<li><a href="{$link->getPageLink('addresses', true)|escape:'html'}" title="{l s='Addresses'}"><img src="{$img_dir}icon/addrbook.gif" alt="{l s='Addresses'}" class="icon" /> {l s='Mis direcciones'}</a></li>
	<li><a href="{$link->getPageLink('identity', true)|escape:'html'}" title="{l s='Information'}"><img src="{$img_dir}icon/userinfo.gif" alt="{l s='Information'}" class="icon" /> {l s='Mi información personal'}</a></li>
	{if $voucherAllowed}
		<li><a href="{$link->getPageLink('discount', true)|escape:'html'}" title="{l s='Vouchers'}"><img src="{$img_dir}icon/voucher.gif" alt="{l s='Vouchers'}" class="icon" /> {l s='Mis vales'}</a></li>
	{/if}
	{$HOOK_CUSTOMER_ACCOUNT}
</ul>
<p><a href="{$base_dir}" title="{l s='Home'}"><img src="{$img_dir}icon/home.gif" alt="{l s='Home'}" class="icon" /></a><a href="{$base_dir}" title="{l s='Home'}">{l s='Inicio'}</a></p>
