<?php
/*
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
*/

if (!defined('_PS_VERSION_'))
	exit;

class Slidetopcontent extends Module
{
	public function __construct()
	{
		$this->name = 'slidetopcontent';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Slide-in content module');
		$this->description = $this->l('A text-edit slide in module ');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/ContentslideClass.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayNav')  ||  !$this->registerHook('freeFblock') || !$this->registerHook('displayHeader'))
			return false;

		$res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'slidetopcontent` (
				`id_slidetopcontent` int(10) unsigned NOT NULL auto_increment,
				`id_shop` int(10) unsigned NOT NULL ,
				PRIMARY KEY (`id_slidetopcontent`))
		ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		if ($res)
			$res &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'slidetopcontent_lang` (
					`id_slidetopcontent` int(10) unsigned NOT NULL,
					`id_lang` int(10) unsigned NOT NULL,
					`main_title` varchar(255) NOT NULL,
					`main_paragraph` text NOT NULL,
					`main_link` varchar(255) NOT NULL,
					`second_title` varchar(255) NOT NULL,
					`second_paragraph` text NOT NULL,
					`second_link` varchar(255) NOT NULL,
					PRIMARY KEY (`id_slidetopcontent`, `id_lang`))
		ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');


		if ($res)
		foreach (Shop::getShops(false) as $shop)
			$res &= $this->createExample($shop['id_shop']);

			if (!$res)
				$res &= $this->uninstall();

			return $res;
		}

		private function createExample($id_shop)
		{
			$slidetopcontent = new ContentslideClass();
			$slidetopcontent->id_shop = (int)$id_shop;
			foreach (Language::getLanguages(false) as $lang)
			{
				$slidetopcontent->main_title[$lang['id_lang']] = 'Lorem ipsum dolor sit amet';
				$slidetopcontent->main_paragraph[$lang['id_lang']] = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>';
				$slidetopcontent->main_link[$lang['id_lang']] = 'http://www.iqit-commerce.com';
				$slidetopcontent->second_title[$lang['id_lang']] = 'Lorem ipsum dolor sit amet';
				$slidetopcontent->second_paragraph[$lang['id_lang']] = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>';
				$slidetopcontent->second_link[$lang['id_lang']] = 'http://www.iqit-commerce.com';
			}
			return $slidetopcontent->add();
		}

		public function uninstall()
		{
			$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'slidetopcontent`');
			$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'slidetopcontent_lang`');

			if (!$res || !parent::uninstall())
				return false;

			return true;
		}

		private function initForm()
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $k => $language)
				$languages[$k]['is_default'] = (int)($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));

			$helper = new HelperForm();
			$helper->module = $this;
			$helper->name_controller = 'slidetopcontent';
			$helper->identifier = $this->identifier;
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->languages = $languages;
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
			$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
			$helper->allow_employee_form_lang = true;
			$helper->toolbar_scroll = true;
			$helper->toolbar_btn = $this->initToolbar();
			$helper->title = $this->displayName;
			$helper->submit_action = 'submitUpdateSlidetopcontent';

			$file = dirname(__FILE__).'/homepage_logo_'.(int)$this->context->shop->id.'.jpg';
			$logo = (file_exists($file) ? '<img src="'.$this->_path.'homepage_logo_'.(int)$this->context->shop->id.'.jpg">' : '');

			$this->fields_form[0]['form'] = array(
				'tinymce' => true,
				'legend' => array(
					'title' => $this->displayName,
					'image' => $this->_path.'logo.gif'
					),
				'submit' => array(
					'name' => 'submitUpdateSlidetopcontent',
					'title' => $this->l('Save ')
					),
				'input' => array(
					array(
						'type' => 'file',
						'label' => $this->l('Homepage logo'),
						'name' => 'body_homepage_logo',
						'display_image' => true,
						'image' => $logo,
						'delete_url' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteLogoImage=1'
						),
					array(
						'type' => 'text',
						'label' => $this->l('Main title'),
						'name' => 'main_title',
						'lang' => true,
						'size' => 64,
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Introductory text'),
						'name' => 'main_paragraph',
						'lang' => true,
						'autoload_rte' => true,
						'hint' => $this->l('For example... explain your mission, highlight a new product, or describe a recent event.'),
						'cols' => 60,
						'rows' => 30
						),
					array(
						'type' => 'text',
						'label' => $this->l('Main link'),
						'name' => 'main_link',
						'lang' => true,
						'size' => 33,
						),
					array(
						'type' => 'text',
						'label' => $this->l('Second title'),
						'name' => 'second_title',
						'lang' => true,
						'size' => 64,
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Second Introductory text'),
						'name' => 'second_paragraph',
						'lang' => true,
						'autoload_rte' => true,
						'hint' => $this->l('For example... explain your mission, highlight a new product, or describe a recent event.'),
						'cols' => 60,
						'rows' => 30
						),
					array(
						'type' => 'text',
						'label' => $this->l('Second link'),
						'name' => 'second_link',
						'lang' => true,
						'size' => 33,
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
	$slidetopcontent = ContentslideClass::getByIdShop($id_shop);

		if (!$slidetopcontent) //if slidetopcontent ddo not exist for this shop => create a new example one
		$this->createExample($id_shop);
		
		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
		{
			if ($input['name'] != 'body_homepage_logo')
				$helper->fields_value[$input['name']] = $slidetopcontent->{$input['name']};
		}

		$file = dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg';
		$helper->fields_value['body_homepage_logo']['image'] = (file_exists($file) ? '<img src="'.$this->_path.'homepage_logo_'.(int)$id_shop.'.jpg">' : '');
		if ($helper->fields_value['body_homepage_logo'] && file_exists($file))
			$helper->fields_value['body_homepage_logo']['size'] = filesize($file) / 1000;
		
		$this->_html .= $helper->generateForm($this->fields_form);
		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		// Delete logo image
		if (Tools::isSubmit('deleteLogoImage'))
		{
			if (!file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
				$errors .= $this->displayError($this->l('This action cannot be made.'));
			else
			{
				unlink(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('slidetopcontent_IMAGE_DISABLE', 1);
				$this->_clearCache('slidetopcontent.tpl');
				Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)$this->context->employee->id));
			}
			$this->_html .= $errors;
		}


		if (Tools::isSubmit('submitUpdateSlidetopcontent'))
		{
			$id_shop = (int)$this->context->shop->id;
			$slidetopcontent = ContentslideClass::getByIdShop($id_shop);
			$slidetopcontent->copyFromPost();
			if (empty($slidetopcontent->id_shop))
				$slidetopcontent->id_shop = (int)$id_shop;
			$slidetopcontent->update();

			/* upload the image */
			if (isset($_FILES['body_homepage_logo']) && isset($_FILES['body_homepage_logo']['tmp_name']) && !empty($_FILES['body_homepage_logo']['tmp_name']))
			{
				Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
				if (file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
					unlink(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				if ($error = ImageManager::validateUpload($_FILES['body_homepage_logo']))
					$errors .= $error;
				elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['body_homepage_logo']['tmp_name'], $tmpName))
					return false;
				elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
					$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));
				if (isset($tmpName))
					unlink($tmpName);
			}
			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;
			if (file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
			{
				list($width, $height, $type, $attr) = getimagesize(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('slidetopcontent_IMAGE_WIDTH', (int)round($width));
				Configuration::updateValue('slidetopcontent_IMAGE_HEIGHT', (int)round($height));
				Configuration::updateValue('slidetopcontent_IMAGE_DISABLE', 0);
			}
			$this->_clearCache('slidetopcontent.tpl');
		}
	}

	public function hookfreeFblock($params)
	{
		if (!$this->isCached('slidetopcontent.tpl', $this->getCacheId()))
		{
			$id_shop = (int)$this->context->shop->id;
			$slidetopcontent = ContentslideClass::getByIdShop($id_shop);
			if (!$slidetopcontent)
				return;			
			$slidetopcontent = new ContentslideClass((int)$slidetopcontent->id, $this->context->language->id);
			if (!$slidetopcontent)
				return;
			$this->smarty->assign(array(
				'slidetopcontent' => $slidetopcontent,
				'default_lang' => (int)$this->context->language->id,
				'image_width' => Configuration::get('slidetopcontent_IMAGE_WIDTH'),
				'image_height' => Configuration::get('slidetopcontent_IMAGE_HEIGHT'),
				'id_lang' => $this->context->language->id,
				'homepage_logo' => !Configuration::get('slidetopcontent_IMAGE_DISABLE') && file_exists('modules/slidetopcontent/homepage_logo_'.(int)$id_shop.'.jpg'),
				'image_path' => $this->_path.'homepage_logo_'.(int)$id_shop.'.jpg'
				));
		}
		return $this->display(__FILE__, 'slidetopcontent.tpl', $this->getCacheId());
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'slidetopcontent.css', 'all');
		$this->context->controller->addJS(($this->_path).'slidetopcontent.js');
	}
	public function hookDisplayNav($params)
	{
		return $this->display(__FILE__, 'slidetopcontent-top.tpl');
	}
}
