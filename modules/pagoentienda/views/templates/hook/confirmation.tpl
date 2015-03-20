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

<p>{l s='Tu pedido en' mod='pagoentienda'} <span class="bold">{$shop_name}</span> {l s='ha sido registrado' mod='pagoentienda'}
	<br /><br />
	{l s='Ha elegido pagar al momento de la entrega.<br>Solo pagos con Tarjetas de Débito y Crédito (Mastercard, Visa y Americanexpress)' mod='pagoentienda'}
	<br /><br />{l s='Para cualquier pregunta, póngase en contacto con nuestro servicio ' mod='pagoentienda'} <a href="{$link->getPageLink('contact-form', true)|escape:'html'}">{l s='Atención al Cliente' mod='pagoentienda'}</a>.
</p>
