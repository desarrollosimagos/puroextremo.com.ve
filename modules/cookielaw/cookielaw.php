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

if (!defined('_PS_VERSION_'))
	exit;

class CookieLaw extends Module
{
	public function __construct()
	{
		$this->name = 'cookielaw';
		$this->tab = 'front_office_features';
		$this->version = '2.2';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Cookie law module');
		$this->description = $this->l('Adds cookie information on footer');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/CookielawClass.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('belowFooter') || !$this->registerHook('displayHeader'))
			return false;

		$res = Db::getInstance()->execute(
			'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cookielaw` (
			`id_cookielaw` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			PRIMARY KEY (`id_cookielaw`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
		);

		if ($res)
			$res &= Db::getInstance()->execute(
				'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cookielaw_lang` (
				`id_cookielaw` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`body_paragraph` text NOT NULL,
				PRIMARY KEY (`id_cookielaw`, `id_lang`))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
			);

		if ($res)
			foreach (Shop::getShops(false) as $shop)
				$res &= $this->createExampleCookie($shop['id_shop']);

		if (!$res)
			$res &= $this->uninstall();

		return $res;
	}

	private function createExampleCookie($id_shop)
	{
		$cookielaw = new CookielawClass();
		$cookielaw->id_shop = (int)$id_shop;
		foreach (Language::getLanguages(false) as $lang)
		{

			$cookielaw->body_paragraph[$lang['id_lang']] = 'Site use cookies';
		}

		return $cookielaw->add();
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'cookielaw`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'cookielaw_lang`');

		if (!$res || !parent::uninstall())
			return false;

		return true;
	}

	private function initForm()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'cookielaw';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitUpdatecookielaw';

		$file = dirname(__FILE__).'/homepage_logo_'.(int)$this->context->shop->id.'.jpg';
		$logo = (file_exists($file) ? '<img src="'.$this->_path.'homepage_logo_'.(int)$this->context->shop->id.'.jpg">' : '');

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->displayName,
				'image' => $this->_path.'logo.gif'
			),
			'submit' => array(
				'name' => 'submitUpdatecookielaw',
				'title' => $this->l('Save '),
				'class' => 'button pull-right'
			),
			'input' => array(
				array(
					'type' => 'textarea',
					'label' => $this->l('Introductory text'),
					'name' => 'body_paragraph',
					'lang' => true,
					'autoload_rte' => true,
					'desc' => $this->l('For example... explain your mission, highlight a new product, or describe a recent event.'),
					'cols' => 60,
					'rows' => 30
				),
			)
		);

		return $helper;
	}

	private function initToolbar()
	{
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);

		return $this->toolbar_btn;
	}

	public function getContent()
	{
		$this->_html = '';
		$this->postProcess();

		$helper = $this->initForm();

		$id_shop = (int)$this->context->shop->id;
		$cookielaw = CookielawClass::getByIdShop($id_shop);

		if (!$cookielaw) //if cookielaw ddo not exist for this shop => create a new example one
			$this->createExampleCookie($id_shop);

		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
		{
				$helper->fields_value[$input['name']] = $cookielaw->{$input['name']};
		}


		$this->_html .= $helper->generateForm($this->fields_form);

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;


		if (Tools::isSubmit('submitUpdatecookielaw'))
		{
			$id_shop = (int)$this->context->shop->id;
			$cookielaw = CookielawClass::getByIdShop($id_shop);
			$cookielaw->copyFromPost();
			if (empty($cookielaw->id_shop))
				$cookielaw->id_shop = (int)$id_shop;
			$cookielaw->save();


			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;

			$this->_clearCache('cookielaw.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
		}

		return true;
	}



	public function hookbelowFooter($params)
	{
		if (!isset($_COOKIE['cookielaw_module'])){

		if (!$this->isCached('cookielaw.tpl', $this->getCacheId()))
		{
			$id_shop = (int)$this->context->shop->id;
			$cookielaw = CookielawClass::getByIdShop($id_shop);
			if (!$cookielaw)
				return;
			$cookielaw = new CookielawClass((int)$cookielaw->id, $this->context->language->id);
			if (!$cookielaw)
				return;
			$this->smarty->assign(
				array(
					'cookielaw' => $cookielaw,
					'default_lang' => (int)$this->context->language->id
				)
			);
		}

		return $this->display(__FILE__, 'cookielaw.tpl', $this->getCacheId());
		}
		return false;
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'cookielaw.css', 'all');
		$this->context->controller->addJS(($this->_path).'cookielaw.js');
	}
}