<?php
/*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class AddthisPlugin extends Module
{
	public function __construct()
	{
		$this->name = 'addthisplugin';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Addthis plugin on product page');
		$this->description = $this->l('Show Addthis plugin on product page');
	}
	
	public function install()
	{
		$this->_clearCache('addthisplugin.tpl');
		return (parent::install() AND Configuration::updateValue('addthisplugin_id', '0') && 
			$this->registerHook('productActions'));
	}
	
	public function uninstall()
	{
		//Delete configuration		
		$this->_clearCache('addthisplugin.tpl');	
		return (Configuration::deleteByName('addthisplugin_id')  AND parent::uninstall());
	}
	
	public function getContent()
	{
		// If we try to update the settings
		$output = '';
		if (isset($_POST['submitModule']))
		{	
			Configuration::updateValue('addthisplugin_id', $_POST['addthisplugin_id']);
			$output .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('addthisplugin.tpl');
		}
		
		$output .= $this->renderForm();

		return $output;
	}


	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Addthis plugin'),
					'icon' => 'icon-link'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Addthis ID:'),
						'name' => 'addthisplugin_id',
						'desc' => $this->l('Input your own Addthis id ex.: ra-50d44b832bee7204 for analitycs'),
					),
				),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
				)
			),
		);
		
		if (Shop::isFeatureActive())
			$fields_form['form']['description'] = $this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops'));
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;		
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);
		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'addthisplugin_id' => Tools::getValue('addthisplugin_id', Configuration::get('addthisplugin_id'))
		);
	}
	
	public function hookExtraLeft($params)
	{
		global $smarty;

		if (!$this->isCached('addthisplugin.tpl', $this->getCacheId()))
		{

			$smarty->assign(array(
				'addthisplugin_id' => Configuration::get('addthisplugin_id'),
				));
		}
		return $this->display(__FILE__, 'addthisplugin.tpl', $this->getCacheId());
	}

	public function hookProductActions($params)
	{
		return $this->hookExtraLeft($params);
	}
}

