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

<p class="payment_module">
	<a href="{$link->getModuleLink('pagoentienda', 'validation', [], true)|escape:'html'}" title="{l s='Pago en Tienda (COD)' mod='pagoentienda'}" rel="nofollow">
		<img src="{$this_path_cod}punto1.jpg" alt="{l s='Pago en Tienda (COD)' mod='pagoentienda'}" style="float:left;" / width="86px">
		<br />{l s='Pago en Tienda (COD)' mod='pagoentienda'}
		<br />{l s='Solo pagos con Tarjetas de Débito y Crédito (Mastercard, Visa y Americanexpress)' mod='pagoentienda'}
		<br style="clear:both;" />
	</a>
</p>