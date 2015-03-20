<?php
/*
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
*/

/**
 * @since 0.1
 */
class PagoentiendaValidationModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function postProcess()
	{
		if ($this->context->cart->id_customer == 0 || $this->context->cart->id_address_delivery == 0 || $this->context->cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'pagoentienda')
			{
				$authorized = true;
				break;
			}
		if (!$authorized)
			die(Tools::displayError('Esta forma de pago no está disponible.'));

		$customer = new Customer($this->context->cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

		if (Tools::getValue('confirm'))
		{
			$customer = new Customer((int)$this->context->cart->id_customer);
			$total = $this->context->cart->getOrderTotal(true, Cart::BOTH);
			$this->module->validateOrder((int)$this->context->cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $this->module->displayName, null, array(), null, false, $customer->secure_key);
			Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->module->id.'&id_order='.(int)$this->module->currentOrder);
		}
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->display_column_left = false;
		parent::initContent();

		$this->context->smarty->assign(array(
			'total' => $this->context->cart->getOrderTotal(true, Cart::BOTH),
			'this_path' => $this->module->getPathUri(),//keep for retro compat
			'this_path_cod' => $this->module->getPathUri(),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

		$this->setTemplate('validation.tpl');
	}
}
