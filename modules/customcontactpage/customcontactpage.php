<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class CustomContactPage extends Module
{
	public function __construct()
	{
		$this->name = 'customcontactpage';
		$this->author = 'IQIT-COMMERCE.COM';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '1.1';

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Custom contact page');
		$this->description = $this->l('Adds map and contact information on contact page');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	
	public function install()
	{
		return (parent::install() 
				&& Configuration::updateValue('customcontactpage_COMPANY', Configuration::get('PS_SHOP_NAME'))
				&& Configuration::updateValue('customcontactpage_ADDRESS', '') 
				&& Configuration::updateValue('customcontactpage_latitude', '25.948969')
				&& Configuration::updateValue('customcontactpage_longitude', '-80.226439')
				&& Configuration::updateValue('customcontactpage_text', array($this->context->language->id => '<p>Custom contact page text information. You can enter here anything you want.&nbsp;</p><p><span style="text-decoration: underline;">Monday - Friday</span></p><p><em class="icon-time"></em> 8am - 6pm</p><p><span style="text-decoration: underline;">Saturday</span>&nbsp;</p><p><em class="icon-time"></em> 11am - 6pm</p><p><span style="text-decoration: underline;">Sunday</span>&nbsp;</p><p><em class="icon-time"></em> Closed&nbsp;</p>'), true) && Configuration::updateValue('customcontactpage_PHONE', '')
				&& Configuration::updateValue('customcontactpage_EMAIL', Configuration::get('PS_SHOP_EMAIL'))
				&& $this->registerHook('header') && $this->registerHook('contactPageHook'));
	}
	
	public function uninstall()
	{
		//Delete configuration			
		return (Configuration::deleteByName('customcontactpage_COMPANY')
				&& Configuration::deleteByName('customcontactpage_ADDRESS')
				&& Configuration::deleteByName('customcontactpage_latitude')
				&& Configuration::deleteByName('customcontactpage_longitude')
				&& Configuration::deleteByName('customcontactpage_text') && Configuration::deleteByName('customcontactpage_PHONE')
				&& Configuration::deleteByName('customcontactpage_EMAIL') && parent::uninstall());
	}
	
	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{	
			Configuration::updateValue('customcontactpage_COMPANY', Tools::getValue('customcontactpage_company'));
			Configuration::updateValue('customcontactpage_ADDRESS', Tools::getValue('customcontactpage_address'));
			Configuration::updateValue('customcontactpage_PHONE', Tools::getValue('customcontactpage_phone'));
			Configuration::updateValue('customcontactpage_EMAIL', Tools::getValue('customcontactpage_email'));

			Configuration::updateValue('customcontactpage_latitude', Tools::getValue('customcontactpage_latitude'));
			Configuration::updateValue('customcontactpage_longitude', Tools::getValue('customcontactpage_longitude'));

			$message_trads = array();
			foreach ($_POST as $key => $value)
				if (preg_match('/customcontactpage_text_/i', $key))
				{
					$id_lang = preg_split('/customcontactpage_text_/i', $key);
					$message_trads[(int)$id_lang[1]] = $value;
				}
			Configuration::updateValue('customcontactpage_text', $message_trads, true);

			$this->_clearCache('customcontactpage.tpl');
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
		}

		$html .= $this->renderForm();
		
		return $html;
	}
	
	public function hookHeader()
	{	
		$this->page_name = Dispatcher::getInstance()->getController();
		if ($this->page_name == 'contact')
		{
		$default_country = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
	

		if((Configuration::get('PS_SSL_ENABLED')))
		$apiUrl = 'https://maps-api-ssl.google.com/maps/api/js?sensor=true&amp;region='.substr($default_country->iso_code, 0, 2);
			else
		$apiUrl = 'http://maps.google.com/maps/api/js?sensor=true&amp;region='.substr($default_country->iso_code, 0, 2);

		$this->smarty->assign(array(
				'apiurl' => $apiUrl,
			));

		$this->context->controller->addJS(($this->_path).'customcontactpage.js');
		$this->context->controller->addCSS(($this->_path).'customcontactpage.css', 'all');
		return $this->display(__FILE__, 'apiurl.tpl');
		}
	}
	
	
	public function hookcontactPageHook($params)
	{	
		if (!$this->isCached('customcontactpage.tpl', $this->getCacheId()))
			$this->smarty->assign(array(
				'customcontactpage_company' => Configuration::get('customcontactpage_COMPANY'),
				'customcontactpage_address' => Configuration::get('customcontactpage_ADDRESS'),
				'customcontactpage_phone' => Configuration::get('customcontactpage_PHONE'),
				'customcontactpage_text' => Configuration::get('customcontactpage_text', $this->context->language->id),
				'customcontactpage_email' => Configuration::get('customcontactpage_EMAIL'),
			));
		Media::addJsDef(array('customcontactpage_latitude' => Configuration::get('customcontactpage_latitude')));
		Media::addJsDef(array('customcontactpage_longitude' => Configuration::get('customcontactpage_longitude')));
		return $this->display(__FILE__, 'customcontactpage.tpl', $this->getCacheId());
	}
	
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Map marker latitude'),
						'name' => 'customcontactpage_latitude',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Map marker longitude'),
						'name' => 'customcontactpage_longitude',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Company name'),
						'name' => 'customcontactpage_company',
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Address'),
						'name' => 'customcontactpage_address',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Phone number'),
						'name' => 'customcontactpage_phone',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Email'),
						'name' => 'customcontactpage_email',
					),
					array(
						'type' => 'textarea',
						'lang' => true,
						'autoload_rte' => true,
						'label' => $this->l('Custom text'),
						'name' => 'customcontactpage_text',
						'desc' => $this->l('Custom text information')	
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{
		$var =  array(
			'customcontactpage_company' => Tools::getValue('customcontactpage_company', Configuration::get('customcontactpage_COMPANY')),
			'customcontactpage_address' => Tools::getValue('customcontactpage_address', Configuration::get('customcontactpage_ADDRESS')),
			'customcontactpage_phone' => Tools::getValue('customcontactpage_phone', Configuration::get('customcontactpage_PHONE')),
			'customcontactpage_email' => Tools::getValue('customcontactpage_email', Configuration::get('customcontactpage_EMAIL')),
			'customcontactpage_latitude' => Tools::getValue('customcontactpage_latitude', Configuration::get('customcontactpage_latitude')),
			'customcontactpage_longitude' => Tools::getValue('customcontactpage_longitude', Configuration::get('customcontactpage_longitude')),
		);

		foreach (Language::getLanguages(false) as $lang)
			$var['customcontactpage_text'][(int)$lang['id_lang']] = Tools::getValue('customcontactpage_text_'.(int)$lang['id_lang'], Configuration::get('customcontactpage_text', (int)$lang['id_lang']));

		return $var;
	}
}
