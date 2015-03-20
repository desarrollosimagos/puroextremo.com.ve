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

{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Mi cuenta'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Su información personal'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Su información personal'}</h1>

{include file="$tpl_dir./errors.tpl"}

{if isset($confirmation) && $confirmation}
	<p class="success">
		{l s='Su información personal se ha actualizado correctamente.'}
		{if isset($pwd_changed)}<br />{l s='Su contraseña ha sido enviada a su correo electrónico:'} {$email}{/if}
	</p>
{else}
	<h3>{l s='Por favor, asegúrese de actualizar su información personal si ha cambiado.'}</h3>
	<p class="required"><sup>*</sup>{l s='campos obligatorios'}</p>
	<form action="{$link->getPageLink('identity', true)|escape:'html'}" method="post" class="std">
		<fieldset>
			<p class="radio">
				<span>{l s='Titulo'}</span>
				{foreach from=$genders key=k item=gender}
					<input type="radio" name="id_gender" id="id_gender{$gender->id}" value="{$gender->id|intval}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
					<label for="id_gender{$gender->id}" class="top">{$gender->name}</label>
				{/foreach}
			</p>
			<p class="required text">
				<label for="firstname">{l s='Nombres'} <sup>*</sup></label>
				<input type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
			</p>
			<p class="required text">
				<label for="lastname">{l s='Apellidos'} <sup>*</sup></label>
				<input type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
			</p>
			<p class="required text">
				<label for="email">{l s='Email'} <sup>*</sup></label>
				<input type="text" name="email" id="email" value="{$smarty.post.email}" />
			</p>
			<p class="required text">
				<label for="old_passwd">{l s='Contraseña actual'} <sup>*</sup></label>
				<input type="password" name="old_passwd" id="old_passwd" />
			</p>
			<p class="password">
				<label for="passwd">{l s='Nueva contraseña'}</label>
				<input type="password" name="passwd" id="passwd" />
			</p>
			<p class="password">
				<label for="confirmation">{l s='Confirmación'}</label>
				<input type="password" name="confirmation" id="confirmation" />
			</p>
			<p class="select">
				<label>{l s='Fecha de Nacimiento'}</label>
				<select name="days" id="days">
					<option value="">-</option>
					{foreach from=$days item=v}
						<option value="{$v}" {if ($sl_day == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
				{*
					{l s='Enero'}
					{l s='Febrero'}
					{l s='Marzo'}
					{l s='Abril'}
					{l s='Mayo'}
					{l s='Junio'}
					{l s='Julio'}
					{l s='Agosto'}
					{l s='Septiembre'}
					{l s='Octubre'}
					{l s='Noviembre'}
					{l s='Deciembre'}
				*}
				<select id="months" name="months">
					<option value="">-</option>
					{foreach from=$months key=k item=v}
						<option value="{$k}" {if ($sl_month == $k)}selected="selected"{/if}>{l s=$v}&nbsp;</option>
					{/foreach}
				</select>
				<select id="years" name="years">
					<option value="">-</option>
					{foreach from=$years item=v}
						<option value="{$v}" {if ($sl_year == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
			</p>
			{if $newsletter}
			<p class="checkbox">
				<input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if} autocomplete="off"/>
				<label for="newsletter">{l s='Suscríbase a nuestro boletín de noticias!'}</label>
			</p>
			<p class="checkbox">
				<input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if} autocomplete="off"/>
				<label for="optin">{l s='Recibir ofertas especiales de nuestros socios!'}</label>
			</p>
			{/if}
			<p class="submit">
				<input type="submit" class="button" name="submitIdentity" value="{l s='Guardar'}" />
			</p>
			<p id="security_informations">
				{l s='[Inserte la cláusula de protección de datos al cliente aquí, en su caso]'}
			</p>
		</fieldset>
	</form>
{/if}

<ul class="footer_links">
	<li><a href="{$link->getPageLink('my-account', true)}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='Volver a su cuenta'}</a></li>
	<li class="f_right"><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /> {l s='Inicio'}</a></li>
</ul>
