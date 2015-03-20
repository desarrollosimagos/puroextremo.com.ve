{*
* 2013-2014 ProyectoNOUS
*
* AVISO DE LICENCIA
*
* Este archivo de origen está sujeto a la Licencia Academic gratuito (AFL 3.0)
* que se incluye con este paquete en el LICENSE.txt archivo.
* También está disponible a través de la World Wide Web en la siguiente dirección:
* http://opensource.org/licenses/afl-3.0.php
* Si usted no recibió una copia de la licencia y no puede
* obtener a través de la World Wide Web, por favor envíe un correo electrónico
* a licencia@proyectonous.com así que podemos enviar una copia de inmediato.
*
* ACLARACIÓN
*
* No modifique o añadir a este archivo si desea actualizar a una más nueva ProyectoNOUS
* versiones en el futuro. Si desea personalizar ProyectoNOUS para su
* necesidades consulte http://www.proyectonous.com para más información.
*
*  @author ProyectoNOUS SA <contact@proyectonous.com>
*  @copyright  2013-2014 ProyectoNOUS SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of ProyectoNOUS SA
*}

{capture name=path}{l s='Shipping' mod='pagoentienda'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Suma del Pedido' mod='pagoentienda'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<h3>{l s='Pago en Tienda' mod='pagoentienda'}</h3>

<form action="{$link->getModuleLink('pagoentienda', 'validation', [], true)|escape:'html'}" method="post">
	<input type="hidden" name="confirm" value="1" />
	<p>
		<img src="{$this_path_cod}punto1.jpg" alt="{l s='Cash on delivery (COD) payment' mod='pagoentienda'}" style="float:left; margin: 0px 10px 5px 0px;" width="100px" />
		{l s='Ha elegido pagar en nuestra sucursal<br>Solo pagos con Tarjetas de Débito y Crédito (Mastercard, Visa y Americanexpress)' mod='pagoentienda'}
		<br/><br />
		{l s='El importe total de su pedido es ' mod='pagoentienda'}
		<span id="amount_{$currencies.0.id_currency}" class="price">{convertPrice price=$total}</span>
		{if $use_taxes == 1}
		    {l s='(tasas incl.)' mod='pagoentienda'}
		{/if}
	</p>
	<p>
		<br /><br />
		<br /><br />
		<b>{l s='Por favor acepte su pedido pulsando en  \'Confirmar mi Pedido\'' mod='pagoentienda'}.</b>
	</p>
	<p class="cart_navigation" id="cart_navigation">
		<a href="{$link->getPageLink('order', true)}?step=3" class="button_large">{l s='Otros Modos de Pago' mod='pagoentienda'}</a>
		<input type="submit" value="{l s='Confirmar mi Pedido' mod='pagoentienda'}" class="exclusive_large" />
	</p>
</form>
