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

if (!defined('_PS_VERSION_'))
	exit;

class PagoEnTienda extends PaymentModule
{	
	public function __construct()
	{
		$this->name = 'pagoentienda';
		$this->tab = 'payments_gateways';
		$this->version = '0.1';
		$this->author = 'PROYECTO NOUS VZLA';
		$this->need_instance = 1;
		
		$this->currencies = false;

		parent::__construct();

		$this->displayName = $this->l('PAGO EN TIENDA(COD)');
		$this->description = $this->l('Acepta pagos en la tienda');

		/* For 1.4.3 and less compatibility */
		$updateConfig = array('PS_OS_CHEQUE', 'PS_OS_PAYMENT', 'PS_OS_PREPARATION', 'PS_OS_SHIPPING', 'PS_OS_CANCELED', 'PS_OS_REFUND', 'PS_OS_ERROR', 'PS_OS_OUTOFSTOCK', 'PS_OS_BANKWIRE', 'PS_OS_PAYPAL', 'PS_OS_WS_PAYMENT');
		if (!Configuration::get('PS_OS_PAYMENT'))
			foreach ($updateConfig as $u)
				if (!Configuration::get($u) && defined('_'.$u.'_'))
					Configuration::updateValue($u, constant('_'.$u.'_'));
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $smarty;

		// Check if cart has product download
		foreach ($params['cart']->getProducts() AS $product)
		{
			$pd = ProductDownload::getIdFromIdProduct((int)($product['id_product']));
			if ($pd AND Validate::isUnsignedInt($pd))
				return false;
		}

		$smarty->assign(array(
			'this_path' => $this->_path, 
			'this_path_cod' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'confirmation.tpl');
	}
}
