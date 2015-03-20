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

/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit ;

include_once (_PS_MODULE_DIR_ . 'textbanners/TextBanner.php');

class TextBanners extends Module {
	private $_html = '';

	public function __construct() {
		$this->name = 'textbanners';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Text banner hompage module');
		$this->description = $this->l('Adds hompage text banners');
	}

	/**
	 * @see Module::install()
	 */
	public function install() {
		/* Adds Module */
		if (parent::install() && $this->registerHook('maxInfos') && $this->registerHook('maxInfos2') && $this->registerHook('header') && $this->registerHook('actionShopDataDuplication')) {
			/* Sets up configuration */
			$res = Configuration::updateValue('textbanners_perline', '3');
			$res = Configuration::updateValue('textbanners_style', '2');
			$res = Configuration::updateValue('textbanners_border', '1');
			$res = Configuration::updateValue('textbanners_show', '1');
			/* Creates tables */
			$res &= $this->createTables();

			
			/* Adds samples */
			if ($res)
				$this->installSamples();

			return $res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples() {
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 3; ++$i) {
			$banner = new TextBanner();
			$banner->position = $i;
			$banner->active = 1;
			$banner->icon = 'icon-star';
			foreach ($languages as $language) {
				$banner->title[$language['id_lang']] = 'Sample ' . $i;
				$banner->legend[$language['id_lang']] = 'sample-' . $i;
				$banner->url[$language['id_lang']] = 'http://www.prestashop.com';
			}
			$banner->add();
		}
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall() {
		/* Deletes Module */
		if (parent::uninstall()) {
			/* Deletes tables */
			$res = $this->deleteTables();
			/* Unsets configuration */
			$res &= Configuration::deleteByName('textbanners_perline');
			$res &= Configuration::deleteByName('textbanners_style');
			$res &= Configuration::deleteByName('textbanners_border');
			$res &= Configuration::deleteByName('textbanners_show');

			
			return $res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables() {
		/* banners */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'textbanners` (
				`id_textbanners_banners` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_textbanners_banners`, `id_shop`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
		');

		/* banners configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'textbanners_banners` (
			  `id_textbanners_banners` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  `icon` varchar(64) NULL,
			  `icon_bg` varchar(64) NULL,
			  `icon_c` varchar(64) NULL,
			  `title_c` varchar(64) NULL,
			  `label_c` varchar(64) NULL,
			  PRIMARY KEY (`id_textbanners_banners`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
		');

		/* banners lang configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'textbanners_banners_lang` (
			  `id_textbanners_banners` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `legend` varchar(255) NOT NULL,
			  `url` varchar(255) NULL,
			  PRIMARY KEY (`id_textbanners_banners`,`id_lang`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
		');

		return $res;
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables() {
		$banners = $this->getbanners();
		foreach ($banners as $banner) {
			$to_del = new TextBanner($banner['id_banner']);
			$to_del->delete();
		}
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'textbanners`, `' . _DB_PREFIX_ . 'textbanners_banners`, `' . _DB_PREFIX_ . 'textbanners_banners_lang`;
		');
	}

	public function getContent() {

		$this->_html .= $this->headerHTML();
	
		if (Tools::isSubmit('submitbanner') 
			|| Tools::isSubmit('delete_id_banner') 
			|| Tools::isSubmit('submitbannerr') 
			|| Tools::isSubmit('changeStatus')) {
		
			if ($this->_postValidation())
			{
				$this->_postProcess();
				$this->_html .= $this->renderForm();
				$this->_html .= $this->renderList();
			}
			else
				$this->_html .= $this->renderAddForm();

		} 
		elseif (Tools::isSubmit('addbanner') || (Tools::isSubmit('id_banner') && $this->bannerExists((int)Tools::getValue('id_banner'))))
				$this->_html .= $this->renderAddForm();
		else
		{
			$this->_html .= $this->renderForm();
			$this->_html .= $this->renderList();
		}

		return $this->_html;
	}


	public function renderForm()
	{
		$options = array(
			array(
				'id_option' => 1,                 
				'name' => '1'              
				),
			array(
				'id_option' => 2,
				'name' => '2'
				),
			array(
				'id_option' => 3,
				'name' => '3'
				),
			array(
				'id_option' => 4,
				'name' => '4'
				),
			);

		$options_style= array(
			array(
				'id_option' => 1,                 
				'name' => $this->l('Icon on left, text align to left')            
				),
			array(
				'id_option' => 2,
				'name' => $this->l('Icon above, text align to center')
				),
			);

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Banners per line:'),
						'name' => 'textbanners_perline',                    
  						'required' => true,
  						'options' => array(
   							 'query' => $options,                           
   							 'id' => 'id_option',                         
   							 'name' => 'name'                               
  					)
					),
					array(
						'type' => 'select',
						'label' => $this->l('Style'),
						'name' => 'textbanners_style',                    
  						'required' => true,
  						'options' => array(
   							 'query' => $options_style,                           
   							 'id' => 'id_option',                         
   							 'name' => 'name'                               
  					)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Border separator'),
						'name' => 'textbanners_border',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'radio',
						'label' => $this->l('Show on'),
						'name' => 'textbanners_show',
						'is_bool'   => true,
						'values'    => array(                                 
  							array(
      						'id'    => 'active_on',                         
      						'value' => 0,                                      
      						'label' => $this->l('All pages')                   
    						),
    						array(
      						'id'    => 'active_of',                       
      						'value' => 1,                                        
      						'label' => $this->l('Only homepage')                   
    						),
  						),
					)
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitbannerr';
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
		return array(
			'textbanners_perline' => Tools::getValue('textbanners_perline', Configuration::get('textbanners_perline')),
			'textbanners_show' => Tools::getValue('textbanners_show', Configuration::get('textbanners_show')),
			'textbanners_border' => Tools::getValue('textbanners_border', Configuration::get('textbanners_border')),
			'textbanners_style' => Tools::getValue('textbanners_style', Configuration::get('textbanners_style')),
		);
	}


	public function renderAddForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Slide informations'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Title'),
						'name' => 'title',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('URL'),
						'name' => 'url',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Legend'),
						'name' => 'legend',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Icon class'),
						'desc' => $this->l('Example: \'icon-star\'. You can use icons from font awesome, ').'<a target="_blank" href="http://fortawesome.github.io/Font-Awesome/3.2.1/icons/">'.$this->l('please check here').'</a>',
						'name' => 'icon',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Icon background color'),
						'name' => 'icon_bg',
						'size' => 30,
						),
					array(
						'type' => 'color',
						'label' => $this->l('Icon color'),
						'name' => 'icon_c',
						'size' => 30,
						),
					array(
						'type' => 'color',
						'label' => $this->l('Title color'),
						'name' => 'title_c',
						'size' => 30,
						),
					array(
						'type' => 'color',
						'label' => $this->l('Legend color'),
						'name' => 'label_c',
						'size' => 30,
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Active'),
						'name' => 'active_banner',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

	if (Tools::isSubmit('id_banner') && $this->bannerExists((int)Tools::getValue('id_banner')))
		{
			$banner = new TextBanner((int)Tools::getValue('id_banner'));
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_banner');
		}

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitbanner';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getAddFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => $this->_path.'images/'
		);

		$helper->override_folder = '/';

		return $helper->generateForm(array($fields_form));
	}

	public function getAddFieldsValues()
	{
		$fields = array();

		if (Tools::isSubmit('id_banner') && $this->bannerExists((int)Tools::getValue('id_banner')))
		{
			$banner = new TextBanner((int)Tools::getValue('id_banner'));
			$fields['id_banner'] = (int)Tools::getValue('id_banner', $banner->id);
		}
		else
			$banner = new TextBanner();

		$fields['active_banner'] = Tools::getValue('active_banner', $banner->active);

		$fields['icon'] = (Tools::getValue('icon', $banner->icon)? Tools::getValue('icon', $banner->icon) : '');
		$fields['icon_bg'] = (Tools::getValue('icon_bg', $banner->icon_bg)? Tools::getValue('icon_bg', $banner->icon_bg) : '');
		$fields['icon_c'] = (Tools::getValue('icon_c', $banner->icon_c)? Tools::getValue('icon_c', $banner->icon_c) : '');
		$fields['title_c'] = (Tools::getValue('title_c', $banner->title_c)? Tools::getValue('title_c', $banner->title_c) : '');
		$fields['label_c'] = (Tools::getValue('label_c', $banner->label_c)? Tools::getValue('label_c', $banner->label_c) : ''); 

	

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
		
			$fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $banner->title[$lang['id_lang']]);
			$fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $banner->url[$lang['id_lang']]);
			$fields['legend'][$lang['id_lang']] = Tools::getValue('legend_'.(int)$lang['id_lang'], $banner->legend[$lang['id_lang']]);
		}

		return $fields;
	}

	public function renderList()
	{
		$banners = $this->getBanners();
		foreach ($banners as $key => $banner)
			$banners[$key]['status'] = $this->displayStatus($banner['id_banner'], $banner['active']);

		$this->context->smarty->assign(
			array(
				'link' => $this->context->link,
				'banners' => $banners,
				'image_baseurl' => $this->_path.'images/'
			)
		);

		return $this->display(__FILE__, 'list.tpl');
	}

	private function _postValidation() {
		$errors = array();

		/* Validation for bannerr configuration */
		if (Tools::isSubmit('submitbannerr')) {

			if (!Validate::isInt(Tools::getValue('textbanners_perline')))
				$errors[] = $this->l('Invalid values');
		}/* Validation for status */
		elseif (Tools::isSubmit('changeStatus')) {
			if (!Validate::isInt(Tools::getValue('id_banner')))
				$errors[] = $this->l('Invalid banner');
		}
		/* Validation for banner */
		elseif (Tools::isSubmit('submitbanner')) {
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_banner')) || (Tools::getValue('active_banner') != 0 && Tools::getValue('active_banner') != 1))
				$errors[] = $this->l('Invalid banner state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid banner position');
			/* If edit : checks id_banner */
			if (Tools::isSubmit('id_banner')) {
				if (!Validate::isInt(Tools::getValue('id_banner')) && !$this->bannerExists(Tools::getValue('id_banner')))
					$errors[] = $this->l('Invalid id_banner');
			}
			/* Checks title/url/legend/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language) {
				if (Tools::strlen(Tools::getValue('title_' . $language['id_lang'])) > 255)
					$errors[] = $this->l('The title is too long.');
				if (Tools::strlen(Tools::getValue('legend_' . $language['id_lang'])) > 255)
					$errors[] = $this->l('The legend is too long.');
				if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 255)
					$errors[] = $this->l('The URL is too long.');
				if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_' . $language['id_lang'])))
					$errors[] = $this->l('The URL format is not correct.');

			}

			/* Checks title/url/legend/description for default lang */
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
			if (Tools::strlen(Tools::getValue('title_' . $id_lang_default)) == 0)
				$errors[] = $this->l('The title is not set.');
			if (Tools::strlen(Tools::getValue('legend_' . $id_lang_default)) == 0)
				$errors[] = $this->l('The legend is not set.');
		}/* Validation for deletion */
		elseif (Tools::isSubmit('delete_id_banner') && (!Validate::isInt(Tools::getValue('delete_id_banner')) || !$this->bannerExists((int)Tools::getValue('delete_id_banner'))))
			$errors[] = $this->l('Invalid id_banner');

		/* Display errors if needed */
		if (count($errors)) {
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess() {
		$errors = array();

		/* Processes bannerr */
		if (Tools::isSubmit('submitbannerr')) {
			$res = Configuration::updateValue('textbanners_perline', (int)Tools::getValue('textbanners_perline'));
			$res &= Configuration::updateValue('textbanners_style', (int)Tools::getValue('textbanners_style'));
			$res &= Configuration::updateValue('textbanners_border', (int)Tools::getValue('textbanners_border'));
			$res &= Configuration::updateValue('textbanners_show', (int)Tools::getValue('textbanners_show'));
			$this->clearCache();
			if (!$res)
				$errors[] = $this->displayError($this->l('The configuration could not be updated.'));
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
		}/* Process banner status */
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_banner')) {
			$banner = new TextBanner((int)Tools::getValue('id_banner'));
			if ($banner->active == 0)
				$banner->active = 1;
			else
				$banner->active = 0;
			$res = $banner->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		}
		/* Processes banner */
		elseif (Tools::isSubmit('submitbanner')) {
			/* Sets ID if needed */
			if (Tools::getValue('id_banner')) {
				$banner = new TextBanner((int)Tools::getValue('id_banner'));
				if (!Validate::isLoadedObject($banner)) {
					$this->_html .= $this->displayError($this->l('Invalid id_banner'));
					return;
				}
			} else
				$banner = new TextBanner();
			/* Sets position */
			$banner->position = (int)Tools::getValue('position');
			/* Sets active */
			$banner->active = (int)Tools::getValue('active_banner');

			$banner->icon = Tools::getValue('icon');
			/* Sets colors */
			$banner->icon_bg = Tools::getValue('icon_bg');
			$banner->icon_c = Tools::getValue('icon_c');
			$banner->title_c = Tools::getValue('title_c');
			$banner->label_c = Tools::getValue('label_c');

			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language) {
				$banner->title[$language['id_lang']] = Tools::getValue('title_' . $language['id_lang']);
				$banner->url[$language['id_lang']] = Tools::getValue('url_' . $language['id_lang']);
				$banner->legend[$language['id_lang']] = Tools::getValue('legend_' . $language['id_lang']);

			}

			/* Processes if no errors  */
			if (!$errors) {
				/* Adds */
				if (!Tools::getValue('id_banner')) {
					if (!$banner->add())
						$errors[] = $this->displayError($this->l('The banner could not be added.'));
				}
				/* Update */
				elseif (!$banner->update())
					$errors[] = $this->displayError($this->l('The banner could not be updated.'));
				$this->clearCache();
			}
		}/* Deletes */
		elseif (Tools::isSubmit('delete_id_banner')) {
			$banner = new TextBanner((int)Tools::getValue('delete_id_banner'));
			$res = $banner->delete();
			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('banner deleted'));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitbanner') && Tools::getValue('id_banner'))
			$this->_html .= $this->displayConfirmation($this->l('banner updated'));
		elseif (Tools::isSubmit('submitbanner'))
			$this->_html .= $this->displayConfirmation($this->l('banner added'));
	}

	private function _prepareHook() {

		if (!$this->isCached('textbanners.tpl', $this->getCacheId())) {
			$banners = $this->getbanners(true);
			if (!$banners)
				return false;

			$this->smarty->assign('textbanners_banners', $banners);
			$this->smarty->assign('textbanners_perline', Configuration::get('textbanners_perline'));
			$this->smarty->assign('textbanners_border', Configuration::get('textbanners_border'));
			$this->smarty->assign('textbanners_style', Configuration::get('textbanners_style'));

		}
		return true;
	}

	public function hookDisplayHeader() {
		$this->context->controller->addCSS(($this->_path) . 'textbanners.css', 'all');

				if (Shop::getContext() == Shop::CONTEXT_GROUP)
				$this->context->controller->addCSS(($this->_path) . 'txtbanners_g_'.(int)$this->context->shop->getContextShopGroupID().'.css', 'all');
				elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
				$this->context->controller->addCSS(($this->_path) . 'txtbanners_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
	}

	public function hookDisplayHome() {
		if (!$this->_prepareHook())
			return;

		return $this->display(__FILE__, 'textbanners.tpl', $this->getCacheId());
	}

	public function hookmaxInfos($params) {
		
		
		return $this->hookDisplayHome($params);
		
	}
	public function hookmaxInfos2($params) {

		if(Configuration::get('textbanners_show')==0)
		return $this->hookDisplayHome($params);
		
	}
	public function clearCache() {
		$this->_clearCache('textbanners.tpl');
		$this->generateCss();

	}
	public function generateCss() {
		$css = '';
		
		$banners = $this->getbanners();
		foreach ($banners as $banner) {
			$css .= '#textbannersmodule .txtbanner'.$banner['id_banner'].' .circle{color: '.$banner['icon_c'].'; background-color: '.$banner['icon_bg'].'; }';
			$css .= '#textbannersmodule .txtbanner'.$banner['id_banner'].' .circle:hover{ color: '.$banner['icon_bg'].'; background-color: '.$banner['icon_c'].'; }';
			$css .= '#textbannersmodule .txtbanner'.$banner['id_banner'].' .txttitle{ color: '.$banner['title_c'].';  }';
			$css .= '#textbannersmodule .txtbanner'.$banner['id_banner'].' .txtlegend{ color: '.$banner['label_c'].'; }';

			
		}


		if (Shop::getContext() == Shop::CONTEXT_GROUP)
			$myFile = $this->local_path . "txtbanners_g_".(int)$this->context->shop->getContextShopGroupID().".css";
		elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
			$myFile = $this->local_path . "txtbanners_s_".(int)$this->context->shop->getContextShopID().".css";
		
		
		
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $css);
		fclose($fh);
	}

	public function hookActionShopDataDuplication($params) {
		Db::getInstance()->execute('
		INSERT IGNORE INTO ' . _DB_PREFIX_ . 'textbanners (id_textbanners_banners, id_shop)
		SELECT id_textbanners_banners, ' . (int)$params['new_id_shop'] . '
		FROM ' . _DB_PREFIX_ . 'textbanners
		WHERE id_shop = ' . (int)$params['old_id_shop']);
		$this->clearCache();
	}

	public function headerHTML() {
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		/* Style & js for fieldset 'slides configuration' */
		$html = '<script type="text/javascript">
			$(function() {
				var $mySlides = $("#banners");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize") + "&action=updatebannersPosition";
						$.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
						}
					});
				$mySlides.hover(function() {
					$(this).css("cursor","move");
					},
					function() {
					$(this).css("cursor","auto");
				});
			});
		</script>';

		return $html;
	}

	public function getNextPosition() {
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
				SELECT MAX(hss.`position`) AS `next_position`
				FROM `' . _DB_PREFIX_ . 'textbanners_banners` hss, `' . _DB_PREFIX_ . 'textbanners` hs
				WHERE hss.`id_textbanners_banners` = hs.`id_textbanners_banners` AND hs.`id_shop` = ' . (int)$this->context->shop->id);

		return (++$row['next_position']);
	}

	public function getbanners($active = null) {
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_textbanners_banners` as id_banner,
					   hss.`position`,
					   hss.`active`,
					   hss.`icon`,
					   hss.`icon_bg`,
					   hss.`icon_c`,
					   hss.`title_c`,
					   hss.`label_c`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`legend`
			FROM ' . _DB_PREFIX_ . 'textbanners hs
			LEFT JOIN ' . _DB_PREFIX_ . 'textbanners_banners hss ON (hs.id_textbanners_banners = hss.id_textbanners_banners)
			LEFT JOIN ' . _DB_PREFIX_ . 'textbanners_banners_lang hssl ON (hss.id_textbanners_banners = hssl.id_textbanners_banners)
			WHERE (id_shop = ' . (int)$id_shop . ')
			AND hssl.id_lang = ' . (int)$id_lang . ($active ? ' AND hss.`active` = 1' : ' ') . '
			ORDER BY hss.position');
	}

	public function displayStatus($id_banner, $active) {
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int)$active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="' . AdminController::$currentIndex . '&configure=' . $this->name . '
				&token=' . Tools::getAdminTokenLite('AdminModules') . '
				&changeStatus&id_banner=' . (int)$id_banner . '" title="' . $title . '"><img src="' . _PS_ADMIN_IMG_ . '' . $img . '" alt="" /></a>';
		return $html;
	}

	public function bannerExists($id_banner) {
		$req = 'SELECT hs.`id_textbanners_banners` as id_banner
				FROM `' . _DB_PREFIX_ . 'textbanners` hs
				WHERE hs.`id_textbanners_banners` = ' . (int)$id_banner;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

}
