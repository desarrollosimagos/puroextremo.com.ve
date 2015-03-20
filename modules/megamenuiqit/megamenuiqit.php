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
 *  @version  Release: $Revision: 7048 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
	exit ;

require (dirname(__FILE__) . '/megamenutoplinks.class.php');
require (dirname(__FILE__) . '/megamenutab.class.php');

class Megamenuiqit extends Module {
	private $_html = '';
	private $_menu = '';
	private $_responsivemenu= '';
	
	private $user_groups;

	/*
	 * Pattern for matching config values
	 */
	private $pattern = '/^([A-Z_]*)[0-9]+/';

	/*
	 * Name of the controller
	 * Used to set item selected or not in top menu
	 */
	private $page_name = '';

	/*
	 * Spaces per depth in BO
	 */
	private $spacer_size = '5';
	private $_postErrors = array();

	public function __construct() {
		$this -> name = 'megamenuiqit';
		$this -> tab = 'front_office_features';
		$this -> version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';

		parent::__construct();

		$this -> displayName = $this->l('Mega menu by IQIT-COMMERCE.COM');
		$this -> description = $this->l('Top advanced horizontal menu');
	}

	public function install() {
		if (!parent::install() || !$this -> registerHook('displayAdminHomeQuickLinks') || !$this->registerHook('maxHeader') || !$this->registerHook('top') 
			|| !Configuration::updateValue($this->name.'_sswidth'	, 0) 
			|| !Configuration::updateValue($this->name.'_mstick'	, 0) 
			|| !Configuration::updateValue($this->name.'_mborder'	, 0) 
			|| !Configuration::updateValue($this->name.'_mmstyle'	, 0) 
			|| !Configuration::updateValue($this->name.'_home_txt_color', '') 
			|| !Configuration::updateValue($this->name.'_home_bg'	, '') 
			|| !Configuration::updateValue($this->name.'_mtransparent'	, 0) 


			||  !$this->registerHook('actionObjectCategoryUpdateAfter') ||
			!$this->registerHook('actionObjectCategoryDeleteAfter') ||
			!$this->registerHook('actionObjectCategoryAddAfter') ||
			!$this->registerHook('actionObjectCmsUpdateAfter') ||
			!$this->registerHook('actionObjectCmsDeleteAfter') ||
			!$this->registerHook('actionObjectCmsAddAfter') ||
			!$this->registerHook('actionObjectSupplierUpdateAfter') ||
			!$this->registerHook('actionObjectSupplierDeleteAfter') ||
			!$this->registerHook('actionObjectSupplierAddAfter') ||
			!$this->registerHook('actionObjectManufacturerUpdateAfter') ||
			!$this->registerHook('actionObjectManufacturerDeleteAfter') ||
			!$this->registerHook('actionObjectManufacturerAddAfter') ||
			!$this->registerHook('actionObjectProductUpdateAfter') ||
			!$this->registerHook('actionObjectProductDeleteAfter') ||
			!$this->registerHook('actionObjectProductAddAfter') ||
			!$this->registerHook('categoryUpdate') ||
			!$this->registerHook('actionShopDataDuplication')
			|| !Configuration::updateGlobalValue('MOD_IQITMENU_ITEMS', 'CAT1,CMS1,CMS2,PRD1') || !$this -> registerHook('displayHeader') || !$this -> installDB()

			)
return false;
$this->installSamples();
return true;
}
private function installSamples()
{
	$languages = Language::getLanguages(false);
	

	$labels = array();
	$links = array();
	$insidecontent = array();

	$insidecontent['left_panel'] ="HIDE";
	$insidecontent['right_panel'] ="HIDE";
	$insidecontent['bottom_panel'] ="HIDE";
	$insidecontent['left_panel_val'] ="HIDE";
	$insidecontent['right_panel_val'] ="HIDE";
	$insidecontent['bottom_panel_val'] ="HIDE";

	foreach ($languages as $language)
	{
		$labels[$language['id_lang']] = 'Sample tab';
		$links[$language['id_lang']] = 'http://www.iqit-commerce.com';
		$labels_tag[$language['id_lang']] = 'Hot!';
	}
	MegaMenuTab::add($links, $labels, $labels_tag, 1, 0, NULL, NULL, NULL, (int)Shop::getContextShopID(), $insidecontent);

}
public function installDb() {
	return (Db::getInstance() -> execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_menus` (
			`id_menu` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`id_shop` INT UNSIGNED NOT NULL,
			`position` INT(10) UNSIGNED NOT NULL,
			`active` TINYINT( 1 ) NOT NULL,
			`new_window` TINYINT( 1 ) NOT NULL,
			`label_icon` VARCHAR( 255 ) NULL,
			`background_color` VARCHAR( 255 ) NULL,
			`text_color` VARCHAR( 255 ) NULL,
			INDEX (`id_shop`)
			) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;') && Db::getInstance() -> execute('
	CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_insidem` (
		`id_menu` INT NOT NULL,
		`left` VARCHAR( 255 ) NOT NULL ,
		`right` VARCHAR( 255 ) NOT NULL ,
		`bottom` VARCHAR( 255 ) NOT NULL ,
		`left_val` VARCHAR( 255 ) NOT NULL ,
		`right_val` VARCHAR( 255 ) NOT NULL ,
		`bottom_val` VARCHAR( 255 ) NOT NULL ,
		INDEX ( `id_menu`)
		) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;') && Db::getInstance() -> execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_menus_lang` (
				`id_menu` INT NOT NULL,
				`id_lang` INT NOT NULL,
				`id_shop` INT NOT NULL,
				`label` VARCHAR( 128 ) NOT NULL,
				`label_tag` VARCHAR( 128 ) NULL,
				`link` VARCHAR( 128 ),
				INDEX ( `id_menu` , `id_lang`, `id_shop`)
				) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;') && Db::getInstance() -> execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_menusin_lang` (
			`id_menu` INT NOT NULL,
			`id_lang` INT NOT NULL,
			`id_shop` INT NOT NULL,
			`link_right` VARCHAR( 128 ) NOT NULL ,
			`link_bottom` VARCHAR( 128 ),
			INDEX ( `id_menu` , `id_lang`, `id_shop`)
			) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;') && Db::getInstance() -> execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_links` (
					`id_linksmenutop` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
					`id_shop` INT UNSIGNED NOT NULL,
					`new_window` TINYINT( 1 ) NOT NULL,
					INDEX (`id_shop`)
					) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;') && Db::getInstance() -> execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'megamenuiqit_links_lang` (
				`id_linksmenutop` INT NOT NULL,
				`id_lang` INT NOT NULL,
				`id_shop` INT NOT NULL,
				`label` VARCHAR( 128 ) NOT NULL ,
				`link` VARCHAR( 128 ) NOT NULL ,
				INDEX ( `id_linksmenutop` , `id_lang`, `id_shop`)
				) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;'));
}

private function uninstallDb() {
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_menus`');
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_insidem`');
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_menus_lang`');
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_links`');
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_links_lang`');
	Db::getInstance() -> execute('DROP TABLE `' . _DB_PREFIX_ . 'megamenuiqit_menusin_lang`');
	return true;
}

public function uninstall() {
	if (!parent::uninstall() 
		|| !Configuration::deleteByName('MOD_IQITMENU_ITEMS') 
		|| !Configuration::deleteByName($this->name.'_sswidth')
		|| !Configuration::deleteByName($this->name.'_mstick')
		|| !Configuration::deleteByName($this->name.'_mborder')
		|| !Configuration::deleteByName($this->name.'_mmstyle')
		|| !Configuration::deleteByName($this->name.'_home_txt_color')
		|| !Configuration::deleteByName($this->name.'_home_bg')
		|| !Configuration::deleteByName($this->name.'_mtransparent')
		
		|| !$this -> uninstallDB())
		return false;
	return true;
}

public function getContent() {
	$this -> context -> controller -> addCSS(($this -> _path) . 'css/admin.css');
	$this -> context -> controller -> addJS(($this -> _path) . 'js/admin.js');
	$this -> context -> controller -> addJS(($this -> _path) . 'js/ajaxupload.js');
	$this -> context -> controller -> addJS(_PS_JS_DIR_ . 'jquery/plugins/jquery.colorpicker.js');

	$this -> context -> controller ->addjQueryPlugin(array(
		'autocomplete'
		));


	$id_lang = (int)Context::getContext() -> language -> id;
	$languages = $this -> context -> controller -> getLanguages();
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

	$labels = Tools::getValue('label') ? array_filter(Tools::getValue('label'), 'strlen') : array();
	$links_label = Tools::getValue('link') ? array_filter(Tools::getValue('link'), 'strlen') : array();
	$spacer = str_repeat('&nbsp;', $this -> spacer_size);
	$divLangName = 'link_label';
	$update_cache = false;


	if (Tools::isSubmit('submitMenuOptions')) {
		if (Configuration::updateValue($this->name.'_home_txt_color', Tools::getValue('home_txt_color')) && Configuration::updateValue($this->name.'_home_bg', Tools::getValue('home_bg')) && Configuration::updateValue($this->name.'_sswidth', Tools::getValue('sswidth')) && Configuration::updateValue($this->name.'_mstick', Tools::getValue('mstick'))  && Configuration::updateValue($this->name.'_mborder', Tools::getValue('mborder'))  && Configuration::updateValue($this->name.'_mtransparent', Tools::getValue('mtransparent'))
			&& Configuration::updateValue($this->name.'_mcenter', Tools::getValue('mcenter'))  && Configuration::updateValue($this->name.'_mmstyle', Tools::getValue('mmstyle')))
			$this->_html .= $this->displayConfirmation($this->l('Settings Updated'));
		else
			$this->_html .= $this->displayError($this->l('Unable to update settings'));
		$update_cache = true;
	}

	else if (Tools::isSubmit('submitResponsivemenu')) {
		if (Configuration::updateValue('MOD_IQITMENU_ITEMS', Tools::getValue('items')))
			$this->_html .= $this->displayConfirmation($this->l('Settings Updated'));
		else
			$this->_html .= $this->displayError($this->l('Unable to update settings'));

		$update_cache = true;
	} else if (Tools::isSubmit('submitAddMenuTab')) {

		if (!count($labels))
			$this -> _html .= $this -> displayError($this->l('Please add a label'));
		else if (!isset($labels[$default_language]))
			$this -> _html .= $this -> displayError($this->l('Please add a label for your default language'));
		else {

			$insidecontent = array();

			$insidecontent['left_panel'] = Tools::getValue('left_panel');
			switch(Tools::getValue('left_panel')) {
				case 'CATE' :
				$insidecontent['left_panel_val'] = Tools::getValue('leftcatitems');
				break;
				case 'LINKS' :
				$insidecontent['left_panel_val'] = Tools::getValue('leftlinksitems');
				break;
				case 'CMS_P' :
				$insidecontent['left_panel_val'] = Tools::getValue('lpanelcms');
				break;
				case 'PRD' :
				$insidecontent['left_panel_val'] = Tools::getValue('leftproductsitems');
				break;
				case 'MAN' :
				$insidecontent['left_panel_val'] = Tools::getValue('leftmanitems');
				break;
				case 'HIDE' :
				$insidecontent['left_panel_val'] = "HIDE";
				break;
			}



			$insidecontent['right_panel'] = Tools::getValue('right_panel');
			switch(Tools::getValue('right_panel')) {

				case 'IMAGE' :


				if (isset($_FILES['right_image']) && isset($_FILES['right_image']['tmp_name']) && !empty($_FILES['right_image']['tmp_name'])) {
					if (ImageManager::validateUpload($_FILES['right_image'], Tools::convertBytes(ini_get('upload_max_filesize'))))
						$this -> _html .= $this -> displayError($this->l('Error during image upload'));

					else {

						$filename = date("Ymd").uniqid('right', false);
						$fileext = substr($_FILES['right_image']['name'], strrpos($_FILES['right_image']['name'], '.') + 1);


						if (Shop::getContext() == Shop::CONTEXT_GROUP)
							$adv_imgname = $filename.'-g'.(int)$this->context->shop->getContextShopGroupID();
						elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
							$adv_imgname = $filename.'-s'.(int)$this->context->shop->getContextShopID();

						$fullfilename =  $adv_imgname.'.'.$fileext;

						if (!move_uploaded_file($_FILES['right_image']['tmp_name'], _PS_MODULE_DIR_ . $this -> name . '/uploads/' . $fullfilename))
							$this -> _html .= $this -> displayError($this->l('Error during move image, maybe folder  permission problems'));
					}
				}
				else{
					$fullfilename =	Tools::getValue('rightimageedit');
				}

				$insidecontent['right_panel_val'] = $fullfilename;
				$insidecontent['right_link_lang'] = Tools::getValue('link_right');
				break;
				case 'PRD' :
				$insidecontent['right_panel_val'] = "PRD".Tools::getValue('right_product_id');
				break;
				case 'HIDE' :
				$insidecontent['right_panel_val'] = "HIDE";
				break;
			}





			$insidecontent['bottom_panel'] = Tools::getValue('bottom_panel');
			switch(Tools::getValue('bottom_panel')) {

				case 'IMAGE' :


				if (isset($_FILES['bottom_image']) && isset($_FILES['bottom_image']['tmp_name']) && !empty($_FILES['bottom_image']['tmp_name'])) {
					if (ImageManager::validateUpload($_FILES['bottom_image'], Tools::convertBytes(ini_get('upload_max_filesize'))))
						$this -> _html .= $this -> displayError($this->l('Error during image upload'));

					else {

						$filename = date("Ymd").uniqid('bottom', false);
						$fileext = substr($_FILES['bottom_image']['name'], strrpos($_FILES['bottom_image']['name'], '.') + 1);


						if (Shop::getContext() == Shop::CONTEXT_GROUP)
							$adv_imgname = $filename.'-g'.(int)$this->context->shop->getContextShopGroupID();
						elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
							$adv_imgname = $filename.'-s'.(int)$this->context->shop->getContextShopID();

						$fullfilename =  $adv_imgname.'.'.$fileext;



						if (!move_uploaded_file($_FILES['bottom_image']['tmp_name'], _PS_MODULE_DIR_ . $this -> name . '/uploads/' . $fullfilename))
							$this -> _html .= $this -> displayError($this->l('Error during move image, maybe folder  permission problems'));
					}
				}
				else{
					$fullfilename =	Tools::getValue('bottomimageedit');
				}





				$insidecontent['bottom_panel_val'] = $fullfilename;
				$insidecontent['bottom_link_lang'] = Tools::getValue('link_bottom');
				break;
				case 'LINKS' :
				$insidecontent['bottom_panel_val'] = Tools::getValue('bottomitems');
				break;
				case 'HIDE' :
				$insidecontent['bottom_panel_val'] = "HIDE";
				break;
			}







			if (isset($_FILES['label_icon']) && isset($_FILES['label_icon']['tmp_name']) && !empty($_FILES['label_icon']['tmp_name'])) {
				if (ImageManager::validateUpload($_FILES['label_icon'], Tools::convertBytes(ini_get('upload_max_filesize'))))
					$this -> _html .= $this -> displayError($this->l('Error during image upload'));
				
				else {

					$filename = date("Ymd").uniqid('label_icon', false);
					$fileext = substr($_FILES['label_icon']['name'], strrpos($_FILES['label_icon']['name'], '.') + 1);

					
					if (Shop::getContext() == Shop::CONTEXT_GROUP)
						$adv_imgname = $filename.'-g'.(int)$this->context->shop->getContextShopGroupID();
					elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
						$adv_imgname = $filename.'-s'.(int)$this->context->shop->getContextShopID();

					$label_icon =  $adv_imgname.'.'.$fileext;
					
					if (!move_uploaded_file($_FILES['label_icon']['tmp_name'], _PS_MODULE_DIR_ . $this -> name . '/uploads/' . $label_icon))
						$this -> _html .= $this -> displayError($this->l('Error during move image, maybe folder  permission problems'));
				}
			}
			else{
				$label_icon =	Tools::getValue('label_icon_edit');
			}



			$id_menu = Tools::getValue('id_menu');
			if ($id_menu != "NONE") {

				MegaMenuTab::update(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('label_tag'), Tools::getValue('active'), Tools::getValue('new_window'),  $label_icon, Tools::getValue('background_color') ,Tools::getValue('text_color'), (int)Shop::getContextShopID(), $insidecontent, $id_menu);
				$this -> _html .= $this -> displayConfirmation($this->l('The menu tab has been updated'));
			} else {
				MegaMenuTab::add(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('label_tag'), Tools::getValue('active'), Tools::getValue('new_window'), $label_icon, Tools::getValue('background_color') ,Tools::getValue('text_color'), (int)Shop::getContextShopID(), $insidecontent);
				$this -> _html .= $this -> displayConfirmation($this->l('The menu tab has been added'));
			}
		}
		$update_cache = true;
	} else if (Tools::isSubmit('submitMenuTabRemove')) {
		$id_menu = Tools::getValue('id_menu', 0);
		MegaMenuTab::remove($id_menu, (int)Shop::getContextShopID());
		$this -> _html .= $this -> displayConfirmation($this->l('The Menu Tab has been removed'));
		$update_cache = true;

	} else if (Tools::isSubmit('submitMenuTabEdit')) {
		$id_menu = Tools::getValue('id_menu', 0);
		return $this -> displayAddTabForm($id_menu);

	} else if (Tools::isSubmit('moveUp')) {
		$id_menu = Tools::getValue('id_menu', 0);
		MegaMenuTab::moveUp($id_menu, (int)Shop::getContextShopID());
		$this -> _html .= $this -> displayConfirmation($this->l('Menu order changed'));
		$update_cache = true;
	} else if (Tools::isSubmit('moveDown')) {
		$id_menu = Tools::getValue('id_menu', 0);
		MegaMenuTab::moveDown($id_menu, (int)Shop::getContextShopID());
		$this -> _html .= $this -> displayConfirmation($this->l('Menu order changed'));
		$update_cache = true;
	} else if (Tools::isSubmit('submitBlocktopmenuLinks')) {

		if ((!count($links_label)) && (!count($labels)))
			;
		else if (!count($links_label))
			$this -> _html .= $this -> displayError($this->l('Please, fill the "Link" field'));
		else if (!count($labels))
			$this -> _html .= $this -> displayError($this->l('Please add a label'));
		else if (!isset($labels[$default_language]))
			$this -> _html .= $this -> displayError($this->l('Please add a label for your default language'));
		else {

			MegaMenuTopLinks::add(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('new_window', 0), (int)Shop::getContextShopID());
			$this -> _html .= $this -> displayConfirmation($this->l('The link has been added'));
		}
		$update_cache = true;
	} else if (Tools::isSubmit('submitBlocktopmenuRemove')) {
		$id_linksmenutop = Tools::getValue('id_linksmenutop', 0);
		MegaMenuTopLinks::remove($id_linksmenutop, (int)Shop::getContextShopID());
		Configuration::updateValue('MOD_BLOCKTOPMENU_ITEMS', str_replace(array('LNK' . $id_linksmenutop . ',', 'LNK' . $id_linksmenutop), '', Configuration::get('MOD_BLOCKTOPMENU_ITEMS')));
		$this -> _html .= $this -> displayConfirmation($this->l('The link has been removed'));
		$update_cache = true;
	} else if (Tools::isSubmit('submitBlocktopmenuEdit')) {
		$id_linksmenutop = (int)Tools::getValue('id_linksmenutop', 0);
		$id_shop = (int)Shop::getContextShopID();

		if (!Tools::isSubmit('link')) {
			$tmp = MegaMenuTopLinks::getLinkLang($id_linksmenutop, $id_shop);
			$links_label_edit = $tmp['link'];
			$labels_edit = $tmp['label'];
			$new_window_edit = $tmp['new_window'];
		} else {
			MegaMenuTopLinks::update(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('new_window', 0), (int)$id_shop, (int)$id_linksmenutop, (int)$id_linksmenutop);
			$this -> _html .= $this -> displayConfirmation($this->l('The link has been edited'));
		}
		$update_cache = true;
	} else if (Tools::isSubmit('addTab')) {
		return $this -> displayAddTabForm();
	}

	if ($update_cache)
		$this->clearMenuCache();

	$this -> _html .= '
	<fieldset>
	<legend><img src="../img/admin/details.gif" alt="" title="" />' . $this->l('Options') . '</legend>
	<form action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" method="post">
	<label>' . $this->l('Megamenu style') . '</label>
	<div class="margin-form">
	<input type="radio" name="mmstyle" id="mmstyle_1" value="1" ' . ((Configuration::get($this->name.'_mmstyle') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mmstyle_1"> Style 1 <img src="'.$this -> _path.'style2.png" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
	<input type="radio" name="mmstyle" id="mmstyle_0" value="0" ' . ((Configuration::get($this->name.'_mmstyle') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mmstyle_0"> Style 2 <img src="'.$this -> _path.'style1.png" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	<input type="radio" name="mmstyle" id="mmstyle_3" value="3" ' . ((Configuration::get($this->name.'_mmstyle') == 3) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mmstyle_3"> Style 3 <img src="'.$this -> _path.'style3.png" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	</div>

	<label>' . $this->l('100% menu width') . '</label>
	<div class="margin-form">
	<input type="radio" name="sswidth" id="sswidth_1" value="1" ' . ((Configuration::get($this->name.'_sswidth') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="sswidth_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
	<input type="radio" name="sswidth" id="sswidth_0" value="0" ' . ((Configuration::get($this->name.'_sswidth') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="sswidth_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	</div>
	<label>' . $this->l('Sticky menu') . '</label>
	<div class="margin-form">
	<input type="radio" name="mstick" id="mstick_1" value="1" ' . ((Configuration::get($this->name.'_mstick') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mstick_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
	<input type="radio" name="mstick" id="mstick_0" value="0" ' . ((Configuration::get($this->name.'_mstick') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mstick_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	</div>
	<label>' . $this->l('Menu border') . '</label>
	<div class="margin-form">
	<input type="radio" name="mborder" id="mborder_2" value="2" ' . ((Configuration::get($this->name.'_mborder') == 2) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mborder_2"> ' . $this->l('Both(top and bottom)') . '</label>
	<input type="radio" name="mborder" id="mborder_1" value="1" ' . ((Configuration::get($this->name.'_mborder') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mborder_1"> ' . $this->l('Bottom') . '</label>
	<input type="radio" name="mborder" id="mborder_0" value="0" ' . ((Configuration::get($this->name.'_mborder') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mborder_0"> ' . $this->l('None') . '</label>
	</div>
	<label>' . $this->l('Transparency on in sticky menu') . '</label>
	<div class="margin-form">
	<input type="radio" name="mtransparent" id="mtransparent_1" value="1" ' . ((Configuration::get($this->name.'_mtransparent') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mtransparent_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
	<input type="radio" name="mtransparent" id="mtransparent_0" value="0" ' . ((Configuration::get($this->name.'_mtransparent') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mtransparent_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	</div>
	<label>' . $this->l('Center menu elements') . '</label>
	<div class="margin-form">
	<input type="radio" name="mcenter" id="mcenter_1" value="1" ' . ((Configuration::get($this->name.'_mcenter') == 1) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mcenter_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
	<input type="radio" name="mcenter" id="mcenter_0" value="0" ' . ((Configuration::get($this->name.'_mcenter') == 0) ? 'checked="checked" ' : '') . '/>
	<label class="t" for="mcenter_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
	</div>
	<label style="clear: both;">' . $this->l('Home link Backgroud color') . '</label>
	<div class="margin-form">
	<input type="color" name="home_bg"  class="colorpicker" data-hex="true" value="' . (Configuration::get($this->name.'_home_bg')) . '" />
	<p class="clear">' . $this->l('Leave empty if you want to keep default') . '</p>
	</div>

	<label style="clear: both;">' . $this->l('Home link text color') . '</label>
	<div class="margin-form">
	<input type="color" name="home_txt_color"  class="colorpicker" data-hex="true" value="' . (Configuration::get($this->name.'_home_txt_color')) . '" />
	<p class="clear">' . $this->l('Leave empty if you want to keep default') . '</p>
	</div>
	<div class="margin-form">
	<input type="submit" name="submitMenuOptions" value="'.$this->l('	Save	').'" class="button" /></div>
	</form>
	</fieldset>
	<br /> <br />
	<fieldset>
	<legend><img src="../img/admin/details.gif" alt="" title="" />' . $this->l('Main menu') . '</legend>

	<a class="button"  href="' . AdminController::$currentIndex . '&configure=' . $this -> name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&addTab">';
	$this -> _html .= '<img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Add New Tab');
	$this -> _html .= '</a><br /> <br />';

	$links = MegaMenuTab::gets((int)$id_lang, null, (int)Shop::getContextShopID());

	if (count($links)) {

		$this -> _html .= '
		<table style="width:100%;">
		<thead class="tabthead">
		<tr style="text-align: left;">
		<th>' . $this->l('Id Tab') . '</th>
		<th>' . $this->l('Label') . '</th>
		<th>' . $this->l('Link') . '</th>
		<th>' . $this->l('Active') . '</th>
		<th>' . $this->l('Action') . '</th>
		</tr>
		</thead>
		<tbody>';
		foreach ($links as $link) {
			$this -> _html .= '
			<tr class="tabtr">
			<td>' . (int)$link['id_menu'] . '</td>
			<td>' . Tools::safeOutput($link['label']) . '</td>
			<td><a href="' . Tools::safeOutput($link['link']) . '">' . Tools::safeOutput($link['link']) . '</a></td>
			<td>	' . (isset($link['active']) && ($link['active'] == 1) ? '<img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" />' : ' <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" />') . '</td>
			<td>
			<form action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" method="post">
			<input type="hidden" name="id_menu" value="' . (int)$link['id_menu'] . '" />
			<input type="submit" name="submitMenuTabEdit" value="' . $this->l('Edit') . '" class="button" />
			<input type="submit" name="submitMenuTabRemove" value="' . $this->l('Remove') . '" class="button" />	
			<input type="submit" name="moveUp" value="' . $this->l('Move up') . '" class="button moveup" />
			<input type="submit" name="moveDown" value="' . $this->l('Move down') . '" class="button movedown" />
			</form>
			</td>
			</tr>';
		}
		$this -> _html .= '</tbody>
		</table>';

	}

	$this -> _html .= '</fieldset><br />';

		// Submenu links

	$this -> _html .= '

	<fieldset>
	<legend><img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Mobile(Responsive) menu') . '</legend>
	<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" id="form">
	<div style="display: none">
	<label>'.$this->l('Items').'</label>
	<div class="margin-form">
	<input type="text" name="items" id="itemsInput" value="'.Tools::safeOutput(Configuration::get('MOD_IQITMENU_ITEMS')).'" size="70" />
	</div>
	</div>

	<div class="clear">&nbsp;</div>
	<table style="margin-left: 130px;">
	<tbody>
	<tr>
	<td style="padding-left: 20px;">
	<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';

		// BEGIN CMS
	$this->_html .= '<optgroup label="'.$this->l('CMS').'">';
	$this->getCMSOptions(0, 1, $id_lang);
	$this->_html .= '</optgroup>';

		// BEGIN SUPPLIER
	$this->_html .= '<optgroup label="'.$this->l('Supplier').'">';
	$suppliers = Supplier::getSuppliers(false, $id_lang);
	foreach ($suppliers as $supplier)
		$this->_html .= '<option value="SUP'.$supplier['id_supplier'].'">'.$spacer.$supplier['name'].'</option>';
	$this->_html .= '</optgroup>';

		// BEGIN Manufacturer
	$this->_html .= '<optgroup label="'.$this->l('Manufacturer').'">';
	$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
	foreach ($manufacturers as $manufacturer)
		$this->_html .= '<option value="MAN'.$manufacturer['id_manufacturer'].'">'.$spacer.$manufacturer['name'].'</option>';
	$this->_html .= '</optgroup>';

		// BEGIN Categories
	$this->_html .= '<optgroup label="'.$this->l('Categories').'">';
	$this->getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
	$this->_html .= '</optgroup>';

		// BEGIN Shops
	if (Shop::isFeatureActive())
	{
		$this->_html .= '<optgroup label="'.$this->l('Shops').'">';
		$shops = Shop::getShopsCollection();
		foreach ($shops as $shop)
		{
			if (!$shop->setUrl() && !$shop->getBaseURL())
				continue;
			$this->_html .= '<option value="SHOP'.(int)$shop->id.'">'.$spacer.$shop->name.'</option>';
		}	
		$this->_html .= '</optgroup>';
	}

		// BEGIN Products
	$this->_html .= '<optgroup label="'.$this->l('Products').'">';
	$this->_html .= '<option value="PRODUCT" style="font-style:italic">'.$spacer.$this->l('Choose ID product').'</option>';
	$this->_html .= '</optgroup>';

		// BEGIN Menu Top Links
	$this->_html .= '<optgroup label="'.$this->l('Menu Top Links').'">';
	$links = MegaMenuTopLinks::gets($id_lang, null, (int)Shop::getContextShopID());
	foreach ($links as $link)
	{
		if ($link['label'] == '')
		{
			$link = MegaMenuTopLinks::get($link['id_linksmenutop'], $default_language, (int)Shop::getContextShopID());
			$this->_html .= '<option value="LNK'.(int)$link[0]['id_linksmenutop'].'">'.$spacer.$link[0]['label'].'</option>';
		}
		else
			$this->_html .= '<option value="LNK'.(int)$link['id_linksmenutop'].'">'.$spacer.$link['label'].'</option>';
	}
	$this->_html .= '</optgroup>';

	$this->_html .= '</select><br />
	<br />
	<a href="#" id="addItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">'.$this->l('Add').' &gt;&gt;</a>
	</td>
	<td>
	<select multiple="multiple" id="items" style="width: 300px; height: 160px;">';
	$this->makeResponsiveMenuOption();
	$this->_html .= '</select><br/>
	<br/>
	<a href="#" id="removeItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; '.$this->l('Remove').'</a>
	</td>
	</tr>
	</tbody>
	</table>
	<div class="clear">&nbsp;</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#addItem").click(add);
		$("#availableItems").dblclick(add);
		$("#removeItem").click(remove);
		$("#items").dblclick(remove);
		function add()
		{
			$("#availableItems option:selected").each(function(i){
				var val = $(this).val();
				var text = $(this).text();
				text = text.replace(/(^\s*)|(\s*$)/gi,"");
				if (val == "PRODUCT")
				{
					val = prompt("'.$this->l('Set ID product').'");
					if (val == null || val == "" || isNaN(val))
						return;
					text = "'.$this->l('Product ID').' "+val;
					val = "PRD"+val;
				}
				$("#items").append("<option value=\""+val+"\">"+text+"</option>");
			});
serialize();
return false;
}
function remove()
{
	$("#items option:selected").each(function(i){
		$(this).remove();
	});
serialize();
return false;
}
function serialize()
{
	var options = "";
	$("#items option").each(function(i){
		options += $(this).val()+",";
	});
$("#itemsInput").val(options.substr(0, options.length - 1));
}
});
</script>
<p class="margin-form" style="padding-left: 154px; ">
<input type="submit" name="submitResponsivemenu" value="'.$this->l('	Save	').'" class="button" />
</p>
</form>
</fieldset><br />

<fieldset>
<legend><img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Add Menu Sublink(links which can be used in main menu dropdown)') . '</legend>
<form action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" method="post" id="form">';

foreach ($languages as $language) {
	$this -> _html .= '
	<div id="link_label_' . (int)$language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $id_lang ? 'block' : 'none') . ';">
	<label>' . $this->l('Label') . '</label>
	<div class="margin-form">
	<input type="text" name="label[' . (int)$language['id_lang'] . ']" id="label_' . (int)$language['id_lang'] . '" size="70" value="' . (isset($labels_edit[$language['id_lang']]) ? Tools::safeOutput($labels_edit[$language['id_lang']]) : '') . '" />
	</div>
	';

	$this -> _html .= '
	<label>' . $this->l('Link') . '</label>
	<div class="margin-form">
	<input type="text" name="link[' . (int)$language['id_lang'] . ']" id="link_' . (int)$language['id_lang'] . '" value="' . (isset($links_label_edit[$language['id_lang']]) ? Tools::safeOutput($links_label_edit[$language['id_lang']]) : '') . '" size="70" />
	</div>
	</div>';
}

$this -> _html .= '<label>' . $this->l('Language') . '</label>
<div class="margin-form">' . $this -> displayFlags($languages, (int)$id_lang, $divLangName, 'link_label', true) . '</div><p style="clear: both;"> </p>';

$this -> _html .= '<label style="clear: both;">' . $this->l('New Window') . '</label>
<div class="margin-form">
<input style="clear: both;" type="checkbox" name="new_window" value="1" ' . (isset($new_window_edit) && $new_window_edit ? 'checked' : '') . '/>
</div>
<div class="margin-form">';

if (Tools::isSubmit('id_linksmenutop'))
	$this -> _html .= '<input type="hidden" name="id_linksmenutop" value="' . (int)Tools::getValue('id_linksmenutop') . '" />';

if (Tools::isSubmit('submitBlocktopmenuEdit'))
	$this -> _html .= '<input type="submit" name="submitBlocktopmenuEdit" value="' . $this->l('Edit') . '" class="button" />';

$this -> _html .= '
<input type="submit" name="submitBlocktopmenuLinks" value="' . $this->l('	Add	') . '" class="button" />
</div>

</form>
</fieldset><br />';

$links = MegaMenuTopLinks::gets((int)$id_lang, null, (int)Shop::getContextShopID());

if (!count($links))
	return $this -> _html;

$this -> _html .= '
<fieldset>
<legend><img src="../img/admin/details.gif" alt="" title="" />' . $this->l('List Menu Sublinks') . '</legend>
<table style="width:100%;">
<thead class="tabthead">
<tr style="text-align: left;">
<th>' . $this->l('Id Link') . '</th>
<th>' . $this->l('Label') . '</th>
<th>' . $this->l('Link') . '</th>
<th>' . $this->l('New Window') . '</th>
<th>' . $this->l('Action') . '</th>
</tr>
</thead>
<tbody>';
foreach ($links as $link) {
	$this -> _html .= '
	<tr class="tabtr">
	<td>' . (int)$link['id_linksmenutop'] . '</td>
	<td>' . Tools::safeOutput($link['label']) . '</td>
	<td><a href="' . Tools::safeOutput($link['link']) . '"' . (($link['new_window']) ? ' target="_blank"' : '') . '>' . Tools::safeOutput($link['link']) . '</a></td>
	<td>' . (($link['new_window']) ? $this->l('Yes') : $this->l('No')) . '</td>
	<td>
	<form action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" method="post">
	<input type="hidden" name="id_linksmenutop" value="' . (int)$link['id_linksmenutop'] . '" />
	<input type="submit" name="submitBlocktopmenuEdit" value="' . $this->l('Edit') . '" class="button" />
	<input type="submit" name="submitBlocktopmenuRemove" value="' . $this->l('Remove') . '" class="button" />
	</form>
	</td>
	</tr>';
}
$this -> _html .= '</tbody>
</table>
</fieldset><br />


';
return $this -> _html;
}



private function makeResponsiveMenuOption()
{
	$menu_item = explode(',', Configuration::get('MOD_IQITMENU_ITEMS'));
	$id_lang = (int)$this->context->language->id;
	$id_shop = (int)Shop::getContextShopID();
	foreach ($menu_item as $item)
	{
		if (!$item)
			continue;

		preg_match($this->pattern, $item, $values);
		$id = (int)substr($item, strlen($values[1]), strlen($item));

		switch (substr($item, 0, strlen($values[1])))
		{
			case 'CAT':
			$category = new Category((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$this->_html .= '<option value="CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
			break;

			case 'PRD':
			$product = new Product((int)$id, true, (int)$id_lang);
			if (Validate::isLoadedObject($product))
				$this->_html .= '<option value="PRD'.$id.'">'.$product->name.'</option>'.PHP_EOL;
			break;

			case 'CMS':
			$cms = new CMS((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($cms))
				$this->_html .= '<option value="CMS'.$id.'">'.$cms->meta_title.'</option>'.PHP_EOL;
			break;

			case 'CMS_CAT':
			$category = new CMSCategory((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$this->_html .= '<option value="CMS_CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
			break;

			case 'MAN':
			$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($manufacturer))
				$this->_html .= '<option value="MAN'.$id.'">'.$manufacturer->name.'</option>'.PHP_EOL;
			break;

			case 'SUP':
			$supplier = new Supplier((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($supplier))
				$this->_html .= '<option value="SUP'.$id.'">'.$supplier->name.'</option>'.PHP_EOL;
			break;

			case 'LNK':
			$link = MegaMenuTopLinks::get((int)$id, (int)$id_lang, (int)$id_shop);
			if (count($link))
			{
				if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
				{
					$default_language = Configuration::get('PS_LANG_DEFAULT');
					$link = MegaMenuTopLinks::get($link[0]['id_linksmenutop'], (int)$default_language, (int)Shop::getContextShopID());
				}
				$this->_html .= '<option value="LNK'.$link[0]['id_linksmenutop'].'">'.$link[0]['label'].'</option>';
			}
			break;
			case 'SHOP':
			$shop = new Shop((int)$id);
			if (Validate::isLoadedObject($shop))
				$this->_html .= '<option value="SHOP'.(int)$id.'">'.$shop->name.'</option>'.PHP_EOL;
			break;
		}
	}
}


private function getResponsiveCategory($id_category, $id_lang = false, $id_shop = false)
{
	$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
	$category = new Category((int)$id_category, (int)$id_lang);

	if ($category->level_depth > 1)
		$category_link = $category->getLink();
	else
		$category_link = $this->context->link->getPageLink('index');

	if (is_null($category->id))
		return;

	$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
	$selected = ($this->page_name == 'category' && ((int)Tools::getValue('id_category') == $id_category)) ? ' class="sfHoverForce"' : '';

	$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
		// filter the categories that the user is allowed to see and browse
	if (!empty($is_intersected))
	{
		$this->_responsivemenu .= '<li '.$selected.'>';
		$this->_responsivemenu .= '<a href="'.$category_link.'">'.$category->name.'</a>';

		if (count($children))
		{
			$this->_responsivemenu .= '<ul>';

			foreach ($children as $child)
				$this->getResponsiveCategory((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);

			$this->_responsivemenu .= '</ul>';
		}
		$this->_responsivemenu .= '</li>';
	}
}


private function getResponsiveCMSMenuItems($parent, $depth = 1, $id_lang = false)
{
	$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

	if ($depth > 3)
		return;

	$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
	$pages = $this->getCMSPages((int)$parent);

	if (count($categories) || count($pages))
	{
		$this->_responsivemenu .= '<ul>';

		foreach ($categories as $category)
		{
			$this->_responsivemenu .= '<li>';
			$this->_responsivemenu .= '<a href="#">'.$category['name'].'</a>';
			$this->getResponsiveCMSMenuItems($category['id_cms_category'], (int)$depth + 1);
			$this->_responsivemenu .= '</li>';
		}

		foreach ($pages as $page)
		{
			$cms = new CMS($page['id_cms'], (int)$id_lang);
			$links = $cms->getLinks((int)$id_lang, array((int)$cms->id));

			$selected = ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms') == $page['id_cms'])) ? ' class="sfHoverForce"' : '';
			$this->_responsivemenu .= '<li '.$selected.'>';
			$this->_responsivemenu .= '<a href="'.$links[0]['link'].'">'.$cms->meta_title.'</a>';
			$this->_responsivemenu .= '</li>';
		}

		$this->_responsivemenu .= '</ul>';
	}
}

private function getCMSMenuItems($parent, $depth = 1, $id_lang = false)
{
	$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

	if ($depth > 3)
		return;

	$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
	$pages = $this->getCMSPages((int)$parent);

	if (count($categories) || count($pages))
	{

		$this->_menu .= '<ul class="left_column_cmssubcats depth depth2 another_cats">';
		foreach ($categories as $category)
		{
			$this->_menu .= '<li class="has_submenu2">';
			$this->_menu .= '<a href="#">'.$category['name'].'</a>';
			$this->getCMSMenuItems($category['id_cms_category'], (int)$depth + 1);
			$this->_menu .= '</li>';
		}


		foreach ($pages as $page)
		{
			$cms = new CMS($page['id_cms'], (int)$id_lang);
			$links = $cms->getLinks((int)$id_lang, array((int)$cms->id));

			$this->_menu.= '<li>';
			$this->_menu .= '<a href="'.$links[0]['link'].'">'.$cms->meta_title.'</a>';
			$this->_menu .= '</li>';
		}
		$this->_menu .= '</ul>';

	}
}

private function makeMegaResponsiveMenu()
{
	$menu_items = explode(',', Configuration::get('MOD_IQITMENU_ITEMS'));
	$id_lang = (int)$this->context->language->id;
	$id_shop = (int)Shop::getContextShopID();

	foreach ($menu_items as $item)
	{
		if (!$item)
			continue;

		preg_match($this->pattern, $item, $value);
		$id = (int)substr($item, strlen($value[1]), strlen($item));

		switch (substr($item, 0, strlen($value[1])))
		{
			case 'CAT':
			$this->getResponsiveCategory((int)$id);
			break;

			case 'PRD':
			$selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover"' : '';
			$product = new Product((int)$id, true, (int)$id_lang);
			if (!is_null($product->id))
				$this->_responsivemenu .= '<li'.$selected.'><a href="'.$product->getLink().'">'.$product->name.'</a></li>'.PHP_EOL;
			break;

			case 'CMS':
			$selected = ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? ' class="sfHover"' : '';
			$cms = CMS::getLinks((int)$id_lang, array($id));
			if (count($cms))
				$this->_responsivemenu .= '<li'.$selected.'><a href="'.$cms[0]['link'].'">'.$cms[0]['meta_title'].'</a></li>'.PHP_EOL;
			break;

			case 'CMS_CAT':
			$category = new CMSCategory((int)$id, (int)$id_lang);
			if (count($category))
			{
				$this->_responsivemenu .= '<li><a href="'.$category->getLink().'">'.$category->name.'</a>';
				$this->getResponsiveCMSMenuItems($category->id);
				$this->_responsivemenu .= '</li>'.PHP_EOL;
			}
			break;

			case 'MAN':
			$selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover"' : '';
			$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
			if (!is_null($manufacturer->id))
			{
				if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
					$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
				else
					$manufacturer->link_rewrite = 0;
				$link = new Link;
				$this->_responsivemenu .= '<li'.$selected.'><a href="'.$link->getManufacturerLink((int)$id, $manufacturer->link_rewrite).'">'.$manufacturer->name.'</a></li>'.PHP_EOL;
			}
			break;

			case 'SUP':
			$selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover"' : '';
			$supplier = new Supplier((int)$id, (int)$id_lang);
			if (!is_null($supplier->id))
			{
				$link = new Link;
				$this->_responsivemenu .= '<li'.$selected.'><a href="'.$link->getSupplierLink((int)$id, $supplier->link_rewrite).'">'.$supplier->name.'</a></li>'.PHP_EOL;
			}
			break;

			case 'SHOP':
			$selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover"' : '';
			$shop = new Shop((int)$id);
			if (Validate::isLoadedObject($shop))
			{
				$link = new Link;
				$this->_responsivemenu .= '<li'.$selected.'><a href="'.$shop->getBaseURL().'">'.$shop->name.'</a></li>'.PHP_EOL;
			}
			break;
			case 'LNK':
			$link = MegaMenuTopLinks::get((int)$id, (int)$id_lang, (int)$id_shop);
			if (count($link))
			{
				if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
				{
					$default_language = Configuration::get('PS_LANG_DEFAULT');
					$link = MegaMenuTopLinks::get($link[0]['id_linksmenutop'], $default_language, (int)Shop::getContextShopID());
				}
				$this->_responsivemenu .= '<li><a href="'.$link[0]['link'].'"'.(($link[0]['new_window']) ? ' target="_blank"': '').'>'.$link[0]['label'].'</a></li>'.PHP_EOL;
			}
			break;
		}
	}
}


public function displayAddTabForm($id_menu = NULL) {

	$this -> context -> controller -> addJS(($this -> _path) . 'js/admin2.js');
	$this -> context -> controller -> addJS(_PS_JS_DIR_ . 'jquery/plugins/jquery.colorpicker.js');

	$id_shop = (int)Shop::getContextShopID();

	$label_icon = NULL;
	$left_edit = NULL;
	$left_val_edit = NULL;

	$right_edit = NULL;
	$right_val_edit = NULL;


	$bottom_edit = NULL;
	$bottom_val_edit = NULL;


	if (isset($id_menu)) {
		if (!Tools::isSubmit('link')) {
			$tmp = MegaMenuTab::getLinkLang($id_menu, $id_shop);
			$links_label_edit = $tmp['link'];
			$labels_edit = $tmp['label'];
			$active_edit = $tmp['active'];
			$label_tag_edit = $tmp['label_tag'];
			$new_window_edit = $tmp['new_window'];
			$label_icon = $tmp['label_icon'];
			$background_color_edit = $tmp['background_color'];
			$text_color_edit = $tmp['text_color'];

			$links_right_edit = $tmp['link_right'];
			$links_bottom_edit = $tmp['link_bottom'];
			$tmp = MegaMenuTab::getinside($id_menu);


			$left_edit = $tmp['left'];
			$left_val_edit = $tmp['left_val'];

			$right_edit = $tmp['right'];
			$right_val_edit = $tmp['right_val'];


			$bottom_edit = $tmp['bottom'];
			$bottom_val_edit = $tmp['bottom_val'];



			
		}

	}

	$id_lang = (int)Context::getContext() -> language -> id;
	$languages = $this -> context -> controller -> getLanguages();
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

	$labels = Tools::getValue('label') ? array_filter(Tools::getValue('label'), 'strlen') : array();
	$links_label = Tools::getValue('link') ? array_filter(Tools::getValue('link'), 'strlen') : array();
	$label_tag = Tools::getValue('label_tag') ? array_filter(Tools::getValue('label_tag'), 'strlen') : array();
	$spacer = str_repeat('&nbsp;', $this -> spacer_size);
	$divLangName = 'link_right¤link_bottom¤link_label¤label_tag';

	$this -> _html .= '
	<fieldset>';
	if (isset($id_menu)) {
		$this -> _html .= '	<legend><img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Edit Menu Tab') . '</legend>';
	}

	else 
	{
		$this -> _html .= '	<legend><img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Add Menu Tab') . '</legend>';
	}


	$this -> _html .= '	<form action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" method="post" id="form" enctype="multipart/form-data">	';

	if (isset($id_menu)) {
		$this -> _html .= '	<input type="hidden" name="id_menu" value="' . $id_menu . '" />';
	} else {

		$this -> _html .= '	<input type="hidden" name="id_menu" value="NONE" />';
	}

	foreach ($languages as $language) {
		$this -> _html .= '
		<div id="link_label_' . (int)$language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $id_lang ? 'block' : 'none') . ';">
		<label>' . $this->l('Label') . '</label>
		<div class="margin-form">
		<input type="text" name="label[' . (int)$language['id_lang'] . ']" id="label_' . (int)$language['id_lang'] . '" size="70" value="' . (isset($labels_edit[$language['id_lang']]) ? $labels_edit[$language['id_lang']] : '') . '" />
		</div>
		';

		$this -> _html .= '
		<label>' . $this->l('Link') . '</label>
		<div class="margin-form">
		<input type="text" name="link[' . (int)$language['id_lang'] . ']" id="link_' . (int)$language['id_lang'] . '" value="' . (isset($links_label_edit[$language['id_lang']]) ?$links_label_edit[$language['id_lang']] : '') . '" size="70" />
		</div>';

		$this -> _html .= '
		<label>' . $this->l('Label Tag') . '</label>
		<div class="margin-form">
		<input type="text" name="label_tag[' . (int)$language['id_lang'] . ']" id="label_tag_' . (int)$language['id_lang'] . '" value="' . (isset($label_tag_edit[$language['id_lang']]) ?$label_tag_edit[$language['id_lang']] : '') . '" size="70" />
		</div>
		</div>';
	}

	$this -> _html .= '<label>' . $this->l('Language') . '</label>
	<div class="margin-form">' . $this -> displayFlags($languages, (int)$id_lang, $divLangName, 'link_label', true) . '</div><p style="clear: both;"> </p>';

	$this -> _html .= '

	<label style="clear: both;">' . $this->l('Label icon') . '</label>
	<div class="margin-form">
	<input type="hidden" value="'.$label_icon.'" name="label_icon_edit" id="label_icon_edit">
	<input id="label_icon" type="file" name="label_icon" onchange="readURL33(this);">
	<img id="label_icon_prev" src=" ' . ($label_icon!=NULL ? $this -> _path.'/uploads/'.$label_icon : $this -> _path . 'img/admin/your.jpg') . '" alt="your image" class="bottomimagepreview"/>
	' . ($label_icon!=NULL ? '<span id="delete_label_icon"><img src="../img/admin/delete.gif" alt="Delete"> Delete icon</span>' : '') . '
	<script type="text/javascript">	

	$("#delete_label_icon").click(function () {
		$("#label_icon_edit").val("");
		$("#label_icon_prev").attr("src", "'. $this -> _path . 'img/admin/your.jpg");
		$(this).slideUp();
	});
</script>			<script type="text/javascript">

function readURL33(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#label_icon_prev").attr("src", e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}
</script>					


</div>

<label style="clear: both;">' . $this->l('Custom Backgroud color') . '</label>
<div class="margin-form">
<input type="color" name="background_color"  class="colorpicker" data-hex="true" value="' . (isset($background_color_edit) ? $background_color_edit : '') . '" />
<p class="clear">' . $this->l('Leave empty if you want to keep default') . '</p>
</div>

<label style="clear: both;">' . $this->l('Custom text color') . '</label>
<div class="margin-form">
<input type="color" name="text_color"  class="colorpicker" data-hex="true" value="' . (isset($text_color_edit) ? $text_color_edit : '') . '" />
<p class="clear">' . $this->l('Leave empty if you want to keep default') . '</p>
</div>

<label style="clear: both;">' . $this->l('Active') . '</label>
<div class="margin-form">
<input type="radio" name="active" id="active_1" value="1" ' . ((isset($active_edit) && ($active_edit == 1)) || !isset($active_edit) ? 'checked' : '') . '/>
<label class="t" for="active_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
<input type="radio" name="active" id="active_0" value="0" ' . (isset($active_edit) && ($active_edit == 0) ? 'checked' : '') . '/>
<label class="t" for="active_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
<p class="clear">' . $this->l('Enable tab?') . '</p>
</div>

<label style="clear: both;">' . $this->l('New window') . '</label>
<div class="margin-form">
<input type="radio" name="new_window" id="new_window_1" value="1" ' . ((isset($new_window_edit) && ($new_window_edit == 1)) ? 'checked' : '') . '/>
<label class="t" for="new_window_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" /></label>
<input type="radio" name="new_window" id="new_window_0" value="0" ' . ((isset($new_window_edit) && ($new_window_edit == 0))  || !isset($new_window_edit)? 'checked' : '') . '/>
<label class="t" for="new_window_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" /></label>
<p class="clear">' . $this->l('Open link in new window?') . '</p>
</div>

<label style="clear: both;">' . $this->l('Content') . '</label>
<div class="margin-form">
<div class="content_panel">
<div class="cleft_panel cpanel">


<label class="panel_option" for="categories_left_panel">
<input type="radio" name="left_panel"  value="CATE" id="categories_left_panel"  ' . (isset($left_edit) && ($left_edit == "CATE") ? 'checked' : '') . '/>
' . $this->l('Categories') . '
</label>

<label class="panel_option" for="links_left_panel">
<input type="radio" name="left_panel"  value="LINKS" id="links_left_panel" ' . (isset($left_edit) && ($left_edit == "LINKS") ? 'checked' : '') . ' />
' . $this->l('Links') . '
</label>

<label class="panel_option" for="cms_left_panel">
<input type="radio" name="left_panel"  value="CMS_P" id="cms_left_panel" ' . (isset($left_edit) && ($left_edit == "CMS_P") ? 'checked' : '') . ' />
' . $this->l('CMS') . '
</label>

<label class="panel_option" for="product_left_panel">
<input type="radio" name="left_panel"  value="PRD" id="product_left_panel" ' . (isset($left_edit) && ($left_edit == "PRD") ? 'checked' : '') . ' />
' . $this->l('Products') . '
</label>

<label class="panel_option" for="man_left_panel">
<input type="radio" name="left_panel"  value="MAN" id="man_left_panel" ' . (isset($left_edit) && ($left_edit == "MAN") ? 'checked' : '') . ' />
' . $this->l('Manufacturers logo') . '
</label>

<label class="panel_option" for="hide_left_panel">
<input type="radio" name="left_panel"  value="HIDE" id="hide_left_panel"  ' . ((isset($left_edit) && ($left_edit == "HIDE")) || !isset($left_edit) ? 'checked' : '') . '/>
' . $this->l('Hide panel') . '
</label>


<div id="leftcat_links" class="margin_field hide">

<div style="display: none">
<label>' . $this->l('Items') . '</label>
<div class="margin-form">
<input type="text" name="leftcatitems" id="leftcatitemsInput" value="' . (isset($left_edit) && ($left_edit == "CATE") ? $left_val_edit : '') . '" size="70" />
</div></div>

<table>
<tbody>
<tr>
<td>
<select multiple="multiple" id="leftcatavailableItems" style="width: 280px; height: 160px;">';

		// BEGIN Categories
$this -> _html .= '<optgroup label="' . $this->l('Categories') . '">';
$this -> getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
$this -> _html .= '</optgroup>';


$this -> _html .= '</select><br />
<br />
<a href="#" id="leftcataddItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">' . $this->l('Add') . ' &gt;&gt;</a>
</td>
<td>
<select multiple="multiple" id="leftcatitems" style="width: 280px; height: 160px;">';
$panel = "leftcat";
if(isset($left_edit) && ($left_edit == "CATE"))
	$this -> makeMenuOption($id_menu, $panel);
$this -> _html .= '</select><br/>
<br/>
<a href="#" id="leftcatremoveItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; ' . $this->l('Remove') . '</a>
</td>
</tr>
</tbody>
</table>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#leftcataddItem").click(add_leftcat);
	$("#leftcatavailableItems").dblclick(add_leftcat);
	$("#leftcatremoveItem").click(remove_leftcat);
	$("#leftcatitems").dblclick(remove_leftcat);
	function add_leftcat()
	{
		$("#leftcatavailableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");
			if (val == "PRODUCT")
			{
				val = prompt("' . $this->l('Set ID product') . '");
				if (val == null || val == "" || isNaN(val))
					return;
				text = "' . $this->l('Product ID') . ' "+val;
				val = "PRD"+val;
			}
			$("#leftcatitems").append("<option value=\""+val+"\">"+text+"</option>");
		});
serialize_leftcat();
return false;
}
function remove_leftcat()
{
	$("#leftcatitems option:selected").each(function(i){
		$(this).remove();
	});
serialize_leftcat();
return false;
}
function serialize_leftcat()
{
	var options = "";
	$("#leftcatitems option").each(function(i){
		options += $(this).val()+",";
	});
$("#leftcatitemsInput").val(options.substr(0, options.length - 1));
}
});
</script>
</div>





<div id="leftlinks_links" class="margin_field hide">

<div style="display: none">
<label>' . $this->l('Items') . '</label>
<div class="margin-form">
<input type="text" name="leftlinksitems" id="leftlinksitemsInput" value=" ' . (isset($left_edit) && ($left_edit == "LINKS") ? $left_val_edit : '') . '" size="70" />
</div></div>
' . $this->l('Links from this option are sorted in one column verticaly') . '
<table>
<tbody>
<tr>
<td>
<select multiple="multiple" id="leftlinksavailableItems" style="width: 280px; height: 160px;">';

// BEGIN CMS
$this -> _html .= '<optgroup label="' . $this->l('CMS') . '">';
$this -> getCMSOptions(0, 1, $id_lang);
$this -> _html .= '</optgroup>';

		// BEGIN SUPPLIER
$this -> _html .= '<optgroup label="' . $this->l('Supplier') . '">';
$suppliers = Supplier::getSuppliers(false, $id_lang);
foreach ($suppliers as $supplier)
	$this -> _html .= '<option value="SUP' . $supplier['id_supplier'] . '">' . $spacer . $supplier['name'] . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Manufacturer
$this -> _html .= '<optgroup label="' . $this->l('Manufacturer') . '">';
$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
foreach ($manufacturers as $manufacturer)
	$this -> _html .= '<option value="MAN' . $manufacturer['id_manufacturer'] . '">' . $spacer . $manufacturer['name'] . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Categories
$this -> _html .= '<optgroup label="' . $this->l('Categories') . '">';
$this -> getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
$this -> _html .= '</optgroup>';

		// BEGIN Shops
if (Shop::isFeatureActive()) {
	$this -> _html .= '<optgroup label="' . $this->l('Shops') . '">';
	$shops = Shop::getShopsCollection();
	foreach ($shops as $shop) {
		if (!$shop -> setUrl() && !$shop -> getBaseURL())
			continue;
		$this -> _html .= '<option value="SHOP' . (int)$shop -> id . '">' . $spacer . $shop -> name . '</option>';
	}
	$this -> _html .= '</optgroup>';
}

		// BEGIN Products
$this -> _html .= '<optgroup label="' . $this->l('Products') . '">';
$this -> _html .= '<option value="PRODUCT" style="font-style:italic">' . $spacer . $this->l('Choose ID product') . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Menu Top Links
$this -> _html .= '<optgroup label="' . $this->l('Menu Top Links') . '">';
$links = MegaMenuTopLinks::gets($id_lang, null, (int)Shop::getContextShopID());
foreach ($links as $link) {
	if ($link['label'] == '') {
		$link = MegaMenuTopLinks::get($link['id_linksmenutop'], $default_language, (int)Shop::getContextShopID());
		$this -> _html .= '<option value="LNK' . (int)$link[0]['id_linksmenutop'] . '">' . $spacer . $link[0]['label'] . '</option>';
	} else
	$this -> _html .= '<option value="LNK' . (int)$link['id_linksmenutop'] . '">' . $spacer . $link['label'] . '</option>';
}
$this -> _html .= '</optgroup>';


$this -> _html .= '</select><br />
<br />
<a href="#" id="leftlinksaddItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">' . $this->l('Add') . ' &gt;&gt;</a>
</td>
<td>
<select multiple="multiple" id="leftlinksitems" style="width: 280px; height: 160px;">';
$panel = "leftlinks";
if(isset($left_edit) && ($left_edit == "LINKS"))
	$this -> makeMenuOption($id_menu, $panel);
$this -> _html .= '</select><br/>
<br/>
<a href="#" id="leftlinksremoveItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; ' . $this->l('Remove') . '</a>
</td>
</tr>
</tbody>
</table>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#leftlinksaddItem").click(add_leftlinks);
	$("#leftlinksavailableItems").dblclick(add_leftlinks);
	$("#leftlinksremoveItem").click(remove_leftlinks);
	$("#leftlinksitems").dblclick(remove_leftlinks);
	function add_leftlinks()
	{
		$("#leftlinksavailableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");
			if (val == "PRODUCT")
			{
				val = prompt("' . $this->l('Set ID product') . '");
				if (val == null || val == "" || isNaN(val))
					return;
				text = "' . $this->l('Product ID') . ' "+val;
				val = "PRD"+val;
			}
			$("#leftlinksitems").append("<option value=\""+val+"\">"+text+"</option>");
		});
serialize_leftlinks();
return false;
}
function remove_leftlinks()
{
	$("#leftlinksitems option:selected").each(function(i){
		$(this).remove();
	});
serialize_leftlinks();
return false;
}
function serialize_leftlinks()
{
	var options = "";
	$("#leftlinksitems option").each(function(i){
		options += $(this).val()+",";
	});
$("#leftlinksitemsInput").val(options.substr(0, options.length - 1));
}
});
</script>
</div>





<div id="leftpanel_cms" class="margin_field hide">
' . $this->l('Show the content of CMS PAGE') . ' <br />
<select class="select" id="lpanelcms" name="lpanelcms">';

if((isset($left_edit) && ($left_edit == "CMS_P")))
	{	  $this -> getCMSOptions2(1, 1, $id_lang, $left_val_edit );}
else{
	$this -> getCMSOptions2(1, 1, $id_lang);
}



$this -> _html .= '</select>

</div>








<div id="leftproducts" class="margin_field hide">
<div style="display: none">
<label>' . $this->l('Items') . '</label>
<div class="margin-form">
<input type="text" name="leftproductsitems" id="leftproductsitemsInput" value="' . (isset($left_edit) && ($left_edit == "PRD") ? $left_val_edit : '') . '" size="70" />
</div></div>
' . $this->l('Product name') . ':</span> <input type="text" value="" id="leftproduct_autocomplete_input" />
<select multiple="multiple" id="leftproductsitems" style="width: 588px; height: 180px; margin-top: 10px;">';

if((isset($left_edit) && ($left_edit == "PRD"))){


	$lfprdns = explode(",", $left_val_edit);

	foreach ($lfprdns as $lfprdn){
		$lid = str_replace("PRD", "", $lfprdn);		
		$lproduct = new Product((int)$lid, true, (int)$this -> context -> language -> id);

		if (Validate::isLoadedObject($lproduct))
			$this -> _html .= '<option value="PRD' . $lid . '">' . $lproduct -> name . '</option>' . PHP_EOL;
	}
}		



$this -> _html .= '</select>
<a href="#" id="leftproductsremoveItem" style="border: 1px solid rgb(170, 170, 170); margin: 8px 0px 0px 0px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">X ' . $this->l('Remove') . '</a>
</div>










<div id="leftman_links" class="margin_field hide">

<div style="display: none">
<label>' . $this->l('Items') . '</label>
<div class="margin-form">
<input type="text" name="leftmanitems" id="leftmanitemsInput" value="' . (isset($left_edit) && ($left_edit == "MAN") ? $left_val_edit : '') . '" size="70" />
</div></div>

<table>
<tbody>
<tr>
<td>
<select multiple="multiple" id="leftmanavailableItems" style="width: 280px; height: 160px;">';

		// BEGIN MANUFACTURES
$this -> _html .= '<optgroup label="' . $this->l('Manucaturers') . '">';		
$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
foreach ($manufacturers as $manufacturer)
	$this -> _html .= '<option value="MAN' . $manufacturer['id_manufacturer'] . '">' . $spacer . $manufacturer['name'] . '</option>';
$this -> _html .= '</optgroup>';


$this -> _html .= '</select><br />
<br />
<a href="#" id="leftmanaddItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">' . $this->l('Add') . ' &gt;&gt;</a>
</td>
<td>
<select multiple="multiple" id="leftmanitems" style="width: 280px; height: 160px;">';
$panel = "leftman";
if(isset($left_edit) && ($left_edit == "MAN"))
	$this -> makeMenuOption($id_menu, $panel);
$this -> _html .= '</select><br/>
<br/>
<a href="#" id="leftmanremoveItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; ' . $this->l('Remove') . '</a>
</td>
</tr>
</tbody>
</table>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#leftmanaddItem").click(add_leftman);
	$("#leftmanavailableItems").dblclick(add_leftman);
	$("#leftmanremoveItem").click(remove_leftman);
	$("#leftmanitems").dblclick(remove_leftman);
	function add_leftman()
	{
		$("#leftmanavailableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");
			if (val == "PRODUCT")
			{
				val = prompt("' . $this->l('Set ID product') . '");
				if (val == null || val == "" || isNaN(val))
					return;
				text = "' . $this->l('Product ID') . ' "+val;
				val = "PRD"+val;
			}
			$("#leftmanitems").append("<option value=\""+val+"\">"+text+"</option>");
		});
serialize_leftman();
return false;
}
function remove_leftman()
{
	$("#leftmanitems option:selected").each(function(i){
		$(this).remove();
	});
serialize_leftman();
return false;
}
function serialize_leftman()
{
	var options = "";
	$("#leftmanitems option").each(function(i){
		options += $(this).val()+",";
	});
$("#leftmanitemsInput").val(options.substr(0, options.length - 1));
}
});
</script>
</div>





</div>
<div class="cright_panel cpanel">

<label class="panel_option" for="image_right_panel">
<input type="radio" name="right_panel"  value="IMAGE" id="image_right_panel"  ' . (isset($right_edit) && ($right_edit == "IMAGE") ? 'checked' : '') . '/>
' . $this->l('Image') . '
</label>



<label class="panel_option" for="links_right_panel">
<input type="radio" name="right_panel"  value="PRD" id="links_right_panel" ' . (isset($right_edit) && ($right_edit == "PRD") ? 'checked' : '') . ' />
' . $this->l('Product') . '
</label>


<label class="panel_option" for="hide_right_panel">
<input type="radio" name="right_panel"  value="HIDE" id="hide_right_panel"  ' . ((isset($right_edit) && ($right_edit == "HIDE")) || !isset($right_edit) ? 'checked' : '') . '/>
' . $this->l('Hide panel') . '
</label>


<div id="right_image_upload" class="margin_field hide">
<p>'.$this->l('Recommand width of image: 162px').'</p>

<input type="hidden" value="'.$right_val_edit.'" name="rightimageedit">
<input id="right_image" type="file" name="right_image" onchange="readURL2(this);">
<img id="right_image_prev" src=" ' . (isset($right_edit) && ($right_edit == "IMAGE") ? $this -> _path.'/uploads/'.$right_val_edit : $this -> _path . '/img/admin/your.jpg') . '" alt="your image" class="bottomimagepreview"/>';
$this->_html .= '<p>'.$this->l('Link(Redirect after image click)').'</p>';
foreach ($languages as $language)
{
	$this->_html .= '
	<div id="link_right_'.$language['id_lang'].'" style="display: ' . ($language['id_lang'] == $id_lang ? 'block' : 'none') . '; float: left;">
	<input type="text" name="link_right['.$language['id_lang'].']" id="link_right_'.$language['id_lang'].'" size="30" value="' . (isset($links_right_edit[$language['id_lang']]) ? $links_right_edit[$language['id_lang']] : '') . '"/>
	</div>';	
}
$this -> _html .= $this -> displayFlags($languages, (int)$id_lang, $divLangName, 'link_right', true);

$this -> _html .= '
<script type="text/javascript">

function readURL2(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#right_image_prev").attr("src", e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}
</script>					


</div>

<div id="right_links" class="margin_field hide">
' . $this->l('Show product with cover') . ' <br /><br />
<div id="ajax_choose_product"><span style="text-decoration: underline; font-weight: bold;">
' . $this->l('Product name') . ':</span> <input type="text" value="" id="product_autocomplete_inputm" />
<input type="hidden" name="right_product_id" id="right_product_id" value="' . (isset($right_edit) && ($right_edit == "PRD") ?  str_replace("PRD", "", $right_val_edit) : '') . '" />
';






if((isset($right_edit) && ($right_edit == "PRD"))){

	$rid = str_replace("PRD", "", $right_val_edit);		
	$rproduct = new Product((int)$rid, true, (int)$this -> context -> language -> id);

	if (Validate::isLoadedObject($rproduct))
		$rproductname =  $rproduct -> name . PHP_EOL;
}		

else{
	$rproductname = "---";

}


$this -> _html .= '	<br /><br />
<span style="text-decoration: underline; font-weight: bold;">' . $this->l('Current product') . ':</span> <span id="right_product_id_curr">'.  $rproductname . '</span>
</div>


</div>



</div>
<div class="cbottom_panel cpanel">

<label class="panel_option" for="image_bottom_panel">
<input type="radio" name="bottom_panel"  value="IMAGE" id="image_bottom_panel"  ' . (isset($bottom_edit) && ($bottom_edit == "IMAGE") ? 'checked' : '') . '/>
' . $this->l('Image') . '
</label>

<label class="panel_option" for="links_bottom_panel">
<input type="radio" name="bottom_panel"  value="LINKS" id="links_bottom_panel" ' . (isset($bottom_edit) && ($bottom_edit == "LINKS") ? 'checked' : '') . ' />
' . $this->l('Links') . '
</label>


<label class="panel_option" for="hide_bottom_panel">
<input type="radio" name="bottom_panel"  value="HIDE" id="hide_bottom_panel"  ' . ((isset($bottom_edit) && ($bottom_edit == "HIDE")) || !isset($bottom_edit) ? 'checked' : '') . '/>
' . $this->l('Hide panel') . '
</label>

<div id="bottom_image_upload" class="margin_field hide">
<input type="hidden" value="'.$bottom_val_edit.'" name="bottomimageedit">
<input id="bottom_image" type="file" name="bottom_image" onchange="readURL(this);">
<img id="bottom_image_prev" src=" ' . (isset($bottom_edit) && ($bottom_edit == "IMAGE") ? $this -> _path.'/uploads/'.$bottom_val_edit : $this -> _path . '/img/admin/your.jpg') . '" alt="your image" class="bottomimagepreview"/>';

$this->_html .= '<p>'.$this->l('Link(Redirect after image click)').'</p>';
foreach ($languages as $language)
{
	$this->_html .= '
	<div id="link_bottom_'.$language['id_lang'].'" style="display: ' . ($language['id_lang'] == $id_lang ? 'block' : 'none') . '; float: left;">
	<input type="text" name="link_bottom['.$language['id_lang'].']" id="link_bottom_'.$language['id_lang'].'" size="30" value="' . (isset($links_bottom_edit[$language['id_lang']]) ? $links_bottom_edit[$language['id_lang']] : '') . '"/>
	</div>';	
}
$this -> _html .= $this -> displayFlags($languages, (int)$id_lang, $divLangName, 'link_bottom', true);
$this->_html .= '<script type="text/javascript">

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#bottom_image_prev").attr("src", e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}
</script>					


</div>

<div id="bottom_links" class="margin_field hide">

<div style="display: none">
<label>' . $this->l('Items') . '</label>
<div class="margin-form">
<input type="text" name="bottomitems" id="bottomitemsInput" value="' . (isset($bottom_edit) && ($bottom_edit == "LINKS") ? $bottom_val_edit : '') . '" size="70" />
</div></div>

<table>
<tbody>
<tr>
<td>
<select multiple="multiple" id="bottomavailableItems" style="width: 300px; height: 160px;">';

		// BEGIN CMS
$this -> _html .= '<optgroup label="' . $this->l('CMS') . '">';
$this -> getCMSOptions(0, 1, $id_lang);
$this -> _html .= '</optgroup>';

		// BEGIN SUPPLIER
$this -> _html .= '<optgroup label="' . $this->l('Supplier') . '">';
$suppliers = Supplier::getSuppliers(false, $id_lang);
foreach ($suppliers as $supplier)
	$this -> _html .= '<option value="SUP' . $supplier['id_supplier'] . '">' . $spacer . $supplier['name'] . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Manufacturer
$this -> _html .= '<optgroup label="' . $this->l('Manufacturer') . '">';
$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
foreach ($manufacturers as $manufacturer)
	$this -> _html .= '<option value="MAN' . $manufacturer['id_manufacturer'] . '">' . $spacer . $manufacturer['name'] . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Categories
$this -> _html .= '<optgroup label="' . $this->l('Categories') . '">';
$this -> getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
$this -> _html .= '</optgroup>';

		// BEGIN Shops
if (Shop::isFeatureActive()) {
	$this -> _html .= '<optgroup label="' . $this->l('Shops') . '">';
	$shops = Shop::getShopsCollection();
	foreach ($shops as $shop) {
		if (!$shop -> setUrl() && !$shop -> getBaseURL())
			continue;
		$this -> _html .= '<option value="SHOP' . (int)$shop -> id . '">' . $spacer . $shop -> name . '</option>';
	}
	$this -> _html .= '</optgroup>';
}

		// BEGIN Products
$this -> _html .= '<optgroup label="' . $this->l('Products') . '">';
$this -> _html .= '<option value="PRODUCT" style="font-style:italic">' . $spacer . $this->l('Choose ID product') . '</option>';
$this -> _html .= '</optgroup>';

		// BEGIN Menu Top Links
$this -> _html .= '<optgroup label="' . $this->l('Menu Top Links') . '">';
$links = MegaMenuTopLinks::gets($id_lang, null, (int)Shop::getContextShopID());
foreach ($links as $link) {
	if ($link['label'] == '') {
		$link = MegaMenuTopLinks::get($link['id_linksmenutop'], $default_language, (int)Shop::getContextShopID());
		$this -> _html .= '<option value="LNK' . (int)$link[0]['id_linksmenutop'] . '">' . $spacer . $link[0]['label'] . '</option>';
	} else
	$this -> _html .= '<option value="LNK' . (int)$link['id_linksmenutop'] . '">' . $spacer . $link['label'] . '</option>';
}
$this -> _html .= '</optgroup>';

$this -> _html .= '</select><br />
<br />
<a href="#" id="bottomaddItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">' . $this->l('Add') . ' &gt;&gt;</a>
</td>
<td>
<select multiple="multiple" id="bottomitems" style="width: 300px; height: 160px;">';
$panel = "bottom";
if(isset($bottom_edit) && ($bottom_edit == "LINKS"))
	$this -> makeMenuOption($id_menu, $panel);
$this -> _html .= '</select><br/>
<br/>
<a href="#" id="bottomremoveItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; ' . $this->l('Remove') . '</a>
</td>
</tr>
</tbody>
</table>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#bottomaddItem").click(add_bottom);
	$("#bottomavailableItems").dblclick(add_bottom);
	$("#bottomremoveItem").click(remove_bottom);
	$("#bottomitems").dblclick(remove_bottom);
	function add_bottom()
	{
		$("#bottomavailableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");
			if (val == "PRODUCT")
			{
				val = prompt("' . $this->l('Set ID product') . '");
				if (val == null || val == "" || isNaN(val))
					return;
				text = "' . $this->l('Product ID') . ' "+val;
				val = "PRD"+val;
			}
			$("#bottomitems").append("<option value=\""+val+"\">"+text+"</option>");
		});
serialize_bottom();
return false;
}
function remove_bottom()
{
	$("#bottomitems option:selected").each(function(i){
		$(this).remove();
	});
serialize_bottom();
return false;
}
function serialize_bottom()
{
	var options = "";
	$("#bottomitems option").each(function(i){
		options += $(this).val()+",";
	});
$("#bottomitemsInput").val(options.substr(0, options.length - 1));
}
});
</script>
</div>
</div>
</div>
</div>';

$this -> _html .= '<div class="margin-form">';

$this -> _html .= '
<input type="submit" name="submitAddMenuTab" value="' . $this->l(' Save ') . '" class="button" />
</div>
';


$this -> _html .= '<div class="margin-form"><a style="text-decoration: underline;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">« Return </a>';

$this -> _html .= ' 	</div></form>
</fieldset><br />';

return $this -> _html;
}

private function makeMenuOption($id_menu = NULL, $panel = NULL) {
	$menu_item = $this -> getMenuItems($id_menu, $panel);
	$id_lang = (int)$this -> context -> language -> id;
	$id_shop = (int)Shop::getContextShopID();
	foreach ($menu_item as $item) {
		if (!$item)
			continue;

		preg_match($this -> pattern, $item, $values);
		$id = (int)substr($item, strlen($values[1]), strlen($item));

		switch (substr($item, 0, strlen($values[1]))) {
			case 'CAT' :
			$category = new Category((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$this -> _html .= '<option value="CAT' . $id . '">' . $category -> name . '</option>' . PHP_EOL;
			break;

			case 'PRD' :
			$product = new Product((int)$id, true, (int)$id_lang);
			if (Validate::isLoadedObject($product))
				$this -> _html .= '<option value="PRD' . $id . '">' . $product -> name . '</option>' . PHP_EOL;
			break;

			case 'CMS' :
			$cms = new CMS((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($cms))
				$this -> _html .= '<option value="CMS' . $id . '">' . $cms -> meta_title . '</option>' . PHP_EOL;
			break;

			case 'CMS_CAT' :
			$category = new CMSCategory((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$this -> _html .= '<option value="CMS_CAT' . $id . '">' . $category -> name . '</option>' . PHP_EOL;
			break;

			case 'MAN' :
			$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($manufacturer))
				$this -> _html .= '<option value="MAN' . $id . '">' . $manufacturer -> name . '</option>' . PHP_EOL;
			break;

			case 'SUP' :
			$supplier = new Supplier((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($supplier))
				$this -> _html .= '<option value="SUP' . $id . '">' . $supplier -> name . '</option>' . PHP_EOL;
			break;

			case 'LNK' :
			$link = MegaMenuTopLinks::get((int)$id, (int)$id_lang, (int)$id_shop);
			if (count($link)) {
				if (!isset($link[0]['label']) || ($link[0]['label'] == '')) {
					$default_language = Configuration::get('PS_LANG_DEFAULT');
					$link = MegaMenuTopLinks::get($link[0]['id_linksmenutop'], (int)$default_language, (int)Shop::getContextShopID());
				}
				$this -> _html .= '<option value="LNK' . $link[0]['id_linksmenutop'] . '">' . $link[0]['label'] . '</option>';
			}
			break;
			case 'SHOP' :
			$shop = new Shop((int)$id);
			if (Validate::isLoadedObject($shop))
				$this -> _html .= '<option value="SHOP' . (int)$id . '">' . $shop -> name . '</option>' . PHP_EOL;
			break;
		}
	}
}

private function getMenuItems($id_menu = NULL, $panel = NULL) {

	$tmp = MegaMenuTab::getinside($id_menu);
	switch($panel) {
		case 'leftcat' :
		$bottom = $tmp['left_val'];
		break;
		case 'leftlinks' :
		$bottom = $tmp['left_val'];
		break;
		case 'leftman' :
		$bottom = $tmp['left_val'];
		break;
		case 'bottom' :
		$bottom = $tmp['bottom_val'];
		break;
	}
	return explode(',', $bottom);
}

private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false) {
	$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;

	if ($recursive === false) {
		$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
		FROM `' . _DB_PREFIX_ . 'cms_category` bcp
		INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
		ON (bcp.`id_cms_category` = cl.`id_cms_category`)
		WHERE cl.`id_lang` = ' . (int)$id_lang . '
		AND bcp.`id_parent` = ' . (int)$parent;

		return Db::getInstance() -> executeS($sql);
	} else {
		$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
		FROM `' . _DB_PREFIX_ . 'cms_category` bcp
		INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
		ON (bcp.`id_cms_category` = cl.`id_cms_category`)
		WHERE cl.`id_lang` = ' . (int)$id_lang . '
		AND bcp.`id_parent` = ' . (int)$parent;

		$results = Db::getInstance() -> executeS($sql);
		foreach ($results as $result) {
			$sub_categories = $this -> getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
			if ($sub_categories && count($sub_categories) > 0)
				$result['sub_categories'] = $sub_categories;
			$categories[] = $result;
		}

		return isset($categories) ? $categories : false;
	}

}

private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true) {
	$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;
	$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);

	if (is_null($category -> id))
		return;

	if ($recursive) {
		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
		$spacer = str_repeat('&nbsp;', $this -> spacer_size * (int)$category -> level_depth);
	}

	$shop = (object)Shop::getShop((int)$category -> getShopID());
	$this -> _html .= '<option value="CAT' . (int)$category -> id . '">' . (isset($spacer) ? $spacer : '') . $category -> name . ' (' . $shop -> name . ')</option>';

	if (isset($children) && count($children))
		foreach ($children as $child) {
			$this -> getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
		}
	}

	private function getCategory($id_category, $i, $depth, $right, $id_lang = false, $id_shop = false) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category -> level_depth > 1)
			$category_link = $category -> getLink();
		else
			$category_link = $this -> context -> link -> getPageLink('index');

		if (is_null($category -> id))
			return;

		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);

		$is_intersected = array_intersect($category -> getGroups(), $this -> user_groups);

	

		if (!empty($is_intersected)) {
			$haschildrens = count($children);
			if($depth==1){
			if(($right=="HIDE" && ($i == 7 ||  $i == 13 || $i == 19)) ||  ($right!="HIDE" && ($i == 6 ||  $i == 11 || $i == 16)))
			$this->_menu .='</ul><ul class="row left_column_cats">';
			}
			$this->_menu .= '<li  class="col-md-2 '. (($right!="HIDE") ? ' col-md-15' : '')  . ' position_'.$i.' ' . ($haschildrens && ($depth == 2) ? 'has_submenu2' : '') . ($haschildrens && ($depth == 3) ? 'has_submenu3' : '') . '" >';
			$this -> _menu .= '<a '.($depth==1 ? 'class="mmtitle"' : '').'  href="' . $category_link . '">' . $category -> name . '</a>';

			if ($haschildrens) {
				$this -> _menu .= '<ul class="left_column_subcats depth depth'.$depth.' ' . ($haschildrens && ($depth == 2) ? 'another_cats' : '') . ($haschildrens && ($depth == 3) ? 'another_cats2' : '') .  '">';
				$y=1;
				foreach ($children as $child)
				{
					$this -> getCategory((int)$child['id_category'], $y, $depth+1, $right, (int)$id_lang, (int)$child['id_shop']);
					$y++;
				}
				$this -> _menu .= '</ul>';
			}
			$this->_menu .= '</li>';
		}
	}

	private function getCategory2($id_category, $id_lang = false, $id_shop = false) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category -> level_depth > 1)
			$category_link = $category -> getLink();
		else
			$category_link = $this -> context -> link -> getPageLink('index');

		if (is_null($category -> id))
			return;

		$selected = ($this -> page_name == 'category' && ((int)Tools::getValue('id_category') == $id_category)) ? ' class="sfHoverForce"' : '';

		$is_intersected = array_intersect($category -> getGroups(), $this -> user_groups);
		// filter the categories that the user is allowed to see and browse
		if (!empty($is_intersected)) {
			$this->_menu .= '<li '.$selected.' >';
			$this -> _menu .= '<a href="' . $category_link . '">' . $category -> name . '</a>';

			$this->_menu .= '</li>';
		}
	}


	private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false) {
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext() -> shop -> id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
		FROM `' . _DB_PREFIX_ . 'cms` c
		INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
		ON (c.`id_cms` = cs.`id_cms`)
		INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
		ON (c.`id_cms` = cl.`id_cms`)
		WHERE c.`id_cms_category` = ' . (int)$id_cms_category . '
		AND cs.`id_shop` = ' . (int)$id_shop . '
		AND cl.`id_lang` = ' . (int)$id_lang . '
		AND c.`active` = 1
		ORDER BY `position`';

		return Db::getInstance() -> executeS($sql);
	}

	private function getCMSOptions($parent = 0, $depth = 1, $id_lang = false) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;

		$categories = $this -> getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this -> getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this -> spacer_size * (int)$depth);

		foreach ($categories as $category) {
			$this -> _html .= '<option value="CMS_CAT' . $category['id_cms_category'] . '" style="font-weight: bold;">' . $spacer . $category['name'] . '</option>';
			$this -> getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
		}

		foreach ($pages as $page)
			$this -> _html .= '<option value="CMS' . $page['id_cms'] . '">' . $spacer . $page['meta_title'] . '</option>';
	}
	
	private function getCMSOptions2($parent = 0, $depth = 1, $id_lang = false, $left_val_edit = NULL) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext() -> language -> id;

		$categories = $this -> getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this -> getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this -> spacer_size * (int)$depth);

		foreach ($categories as $category) {
			$this -> getCMSOptions2($category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
		}

		foreach ($pages as $page)
			$this -> _html .= '<option ' . (isset($left_val_edit) && ($left_val_edit == "CMS_P". $page['id_cms']) ? 'selected' : '') . ' value="CMS_P' . $page['id_cms'] . '">' . $page['meta_title'] . '</option>';
	}
	
	private function makeMenuLinks($id, $vv)
	{
		
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();



		switch ($vv)
		{
			case 'CAT':
			$this->getCategory2((int)$id);
			break;

			case 'PRD':
			$product = new Product((int)$id, true, (int)$id_lang);
			if (!is_null($product->id))
				$this->_menu .= '<li><a href="'.$product->getLink().'">'.$product->name.'</a></li>'.PHP_EOL;
			break;

			case 'CMS':
			$cms = CMS::getLinks((int)$id_lang, array($id));
			if (count($cms))
				$this->_menu .= '<li><a href="'.$cms[0]['link'].'">'.$cms[0]['meta_title'].'</a></li>'.PHP_EOL;
			break;

			case 'CMS_CAT':
			$category = new CMSCategory((int)$id, (int)$id_lang);
			if (count($category))
			{
				$this->_menu .= '<li class="has_submenu2"><a href="'.$category->getLink().'">'.$category->name.'</a>';
				$this->getCMSMenuItems($category->id);
				$this->_menu .= '</li>'.PHP_EOL;
			}
			break;

			case 'MAN':
			$selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover"' : '';
			$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
			if (!is_null($manufacturer->id))
			{
				if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
					$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
				else
					$manufacturer->link_rewrite = 0;
				$link = new Link;
				$this->_menu .= '<li'.$selected.'><a href="'.$link->getManufacturerLink((int)$id, $manufacturer->link_rewrite).'">'.$manufacturer->name.'</a></li>'.PHP_EOL;
			}
			break;

			case 'SUP':
			$selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover"' : '';
			$supplier = new Supplier((int)$id, (int)$id_lang);
			if (!is_null($supplier->id))
			{
				$link = new Link;
				$this->_menu .= '<li'.$selected.'><a href="'.$link->getSupplierLink((int)$id, $supplier->link_rewrite).'">'.$supplier->name.'</a></li>'.PHP_EOL;
			}
			break;

			case 'SHOP':
			$selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover"' : '';
			$shop = new Shop((int)$id);
			if (Validate::isLoadedObject($shop))
			{
				$link = new Link;
				$this->_menu .= '<li'.$selected.'><a href="'.$shop->getBaseURL().'">'.$shop->name.'</a></li>'.PHP_EOL;
			}
			break;
			case 'LNK':
			$link = MegaMenuTopLinks::get((int)$id, (int)$id_lang, (int)$id_shop);
			if (count($link))
			{
				if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
				{
					$default_language = Configuration::get('PS_LANG_DEFAULT');
					$link = MegaMenuTopLinks::get($link[0]['id_linksmenutop'], $default_language, (int)Shop::getContextShopID());
				}
				$this->_menu .= '<li><a href="'.$link[0]['link'].'"'.(($link[0]['new_window']) ? ' target="_blank"': '').'>'.$link[0]['label'].'</a></li>'.PHP_EOL;
			}
			break;
			
		}
	}
	
	private function getManufacturerwImage($id, $i=NULL)
	{
		
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();



		$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
		if (!is_null($manufacturer->id))
		{
			if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
				$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
			else
				$manufacturer->link_rewrite = 0;
			$link = new Link;
			$mimage =  _THEME_MANU_DIR_.$manufacturer->id.'-mf_image.jpg';
			$mimagecheck = _PS_ROOT_DIR_.'/img/m/'.$manufacturer->id.'-mf_image.jpg';



			if (file_exists($mimagecheck)) {
				$this->_menu .= '<li class="col-md-2 position_'.$i.'"><a href="'.$link->getManufacturerLink((int)$id, $manufacturer->link_rewrite).'">'; 
				$this->_menu .=  '<img src="'.$mimage.'" class="img-responsive menu_logo_manufacturer" alt="'.$manufacturer->name.'" />';
				$this->_menu .= $manufacturer->name.'</a></li>'.PHP_EOL;
			}





		}
	}
	
	
	private function getProductwImage($id, $i=NULL)
	{
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		
		$product = new Product((int)$id, true, (int)$id_lang);
		$cover = Product::getCover((int)$id);
		$reduction=0;
		if(isset($product->specificPrice['reduction']))
			$reduction=$product->specificPrice['reduction'];

		$priceDisplay = Product::getTaxCalculationMethod();
		$pscatmode = (bool)Configuration::get('PS_CATALOG_MODE') || !(bool)Group::getCurrent()->show_prices;
		if (!is_null($product->id))
		{
			$this->_menu .= '<li class="position_'.$i.' product_menu_container"><div class="product-image-container"><a href="'.$product->getLink().'">';
			
			
			$image_array = array();
			$image_array = $this->getProductsImgs($product->id);		
			if (isset($image_array) && !empty($image_array))
				{
					$this->_menu .= '<img class="img-responsive img_0" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$image_array[0]['id_image'], ImageType::getFormatedName('home')).'" />';
			if (isset($image_array[1]) && !empty($image_array[1]))
				$this->_menu .= '<img class="img-responsive img_1" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$image_array[1]['id_image'], ImageType::getFormatedName('home')).'" />';
				}			
			else		
			$this->_menu .= '<img class="img-responsive img_0" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$cover['id_image'], ImageType::getFormatedName('home')).'" />';
			
			$this->_menu .= '</a></div><a href="'.$product->getLink().'">'.$product->name.'</a> <br />';
		if ($product->show_price && !(bool)$pscatmode){
			$this->_menu .= '<span class="price product-price">'.( !$priceDisplay ? Tools::displayPrice(Tools::ps_round($product->price*(0.01*$product->tax_rate)+$product->price, 2)) : Tools::displayPrice(Tools::ps_round($product->price, 2))).'</span>'.(($reduction) ? ' <span class="old_price">'.Tools::displayPrice($product->getPriceWithoutReduct(false)).'</span>' : '').'</li>'.PHP_EOL;}
		}}

		private function getProductwImage2($id, $i=NULL, $right=NULL)
	{
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		
		$product = new Product((int)$id, true, (int)$id_lang);
		$cover = Product::getCover((int)$id);
		$reduction=0;
		if(isset($product->specificPrice['reduction']))
			$reduction=$product->specificPrice['reduction'];

		$priceDisplay = Product::getTaxCalculationMethod();
		$pscatmode = (bool)Configuration::get('PS_CATALOG_MODE') || !(bool)Group::getCurrent()->show_prices;
		if (!is_null($product->id))
		{
			if(($right=="HIDE" && ($i == 7 ||  $i == 13 || $i == 19)) ||  ($right!="HIDE" && ($i == 6 ||  $i == 11 || $i == 16)))
			$this->_menu .='</ul><ul class="row left_column_products">';

			$this->_menu .= '<li class="col-md-2 '. (($right!="HIDE") ? ' col-md-15' : '')  . ' position_'.$i.' product_menu_container"><div class="product-image-container"><a href="'.$product->getLink().'">';

			$image_array = array();
			$image_array = $this->getProductsImgs($product->id);		
			if (isset($image_array) && !empty($image_array))
				{
					$this->_menu .= '<img class="img-responsive img_0" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$image_array[0]['id_image'], ImageType::getFormatedName('home')).'" />';
			if (isset($image_array[1]) && !empty($image_array[1]))
				$this->_menu .= '<img class="img-responsive img_1" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$image_array[1]['id_image'], ImageType::getFormatedName('home')).'" />';
				}			
			else		
			$this->_menu .= '<img class="img-responsive img_0" alt ="'.$product->name.'" src="'.$this->context->link->getImageLink($product->link_rewrite, (int)$product->id.'-'.(int)$cover['id_image'], ImageType::getFormatedName('home')).'" />';
			$this->_menu .= '</a></div><a href="'.$product->getLink().'">'.$product->name.'</a> <br />';
		if ($product->show_price && !(bool)$pscatmode){
			$this->_menu .= '<span class="price product-price">'.( !$priceDisplay ? Tools::displayPrice(Tools::ps_round($product->price*(0.01*$product->tax_rate)+$product->price, 2)) : Tools::displayPrice(Tools::ps_round($product->price, 2))).'</span>'.(($reduction) ? ' <span class="old_price">'.Tools::displayPrice($product->getPriceWithoutReduct(false)).'</span>' : '').'</li>'.PHP_EOL;}
		}}

		private function makeMegaMenu()
		{	

			$id_lang = (int)$this->context->language->id;
			$id_shop = (int)Shop::getContextShopID();

			$links = MegaMenuTab::getsfm($id_lang, $id_shop);
			foreach($links as $link){
				$submenu = false;
				if(($link['left']!="HIDE") || ($link['right']!="HIDE") || ($link['bottom']!="HIDE"))
					$submenu = true;
				$this->_menu .= '<li  class="mainmegamenu id_menu'.$link['id_menu'].' ' . (($submenu) ? 'has_submenu' : '') . '">';
				if($link['link']!=""){
					$this->_menu .=	'<a  ' . (($link['new_window']) ? 'target="_blank"' : '') . ' class="main_menu_link id_menu_link'.$link['id_menu'].'" href="'.$link['link'].'" style="'.(!empty($link['background_color']) ? 'background-color: '.$link['background_color'].';' : '').(!empty($link['text_color']) ? 'color: '.$link['text_color'].';' : '').'">';
				}
				else{
					$this->_menu .=	'<span  class="main_menu_link id_menu_link'.$link['id_menu'].'" style="'.(!empty($link['background_color']) ? 'background-color: '.$link['background_color'].';' : '').(!empty($link['text_color']) ? 'color: '.$link['text_color'].';' : '').'">';
				}
				if(isset($link['label_tag']) AND ($link['label_tag']!=""))
				$this->_menu .=	'<span class="label label-default menu_label_tag">'.$link['label_tag'].'</span>';	
				if(isset($link['label_icon']) AND ($link['label_icon']!=""))
					$this->_menu .=	'<img src="'.$this -> _path . '/uploads/'.$link['label_icon'].'" alt="'.$link['label'].'" class="label_icon" />';

				$this->_menu .=	$link['label'];
				if($link['link']!=""){
					$this->_menu .=	'</a>';
				}
				else{
					$this->_menu .=	'</span>';
				}


				if($submenu)
				{
					$this->_menu .='<div class="submenu submenuid'.$link['id_menu'].' clearfix"><div class="submenu_triangle"></div><div class="submenu_triangle2"></div>';
					if(($link['left']!="HIDE") || ($link['right']!="HIDE"))
						$this->_menu .='<div class="row clearfix">';
					if(($link['left']!="HIDE")){
						$this->_menu .='
						<div class="left_panel col-md-10 ' . (($link['right']=="HIDE") ? ' col-md-12 no_right_panel' : '') . '">';



						switch ($link['left'])
						{
							case 'CATE':
							$lids = explode(",", $link['left_val']);
							$this->_menu .='<ul class="row left_column_cats">';
							$i=1;
							foreach ($lids as $lid){
								preg_match($this->pattern, $lid, $value);
								$id = (int)substr($lid, strlen($value[1]), strlen($lid));
								$this->getCategory((int)$id, $i, 1, $link['right']);
								$i++;
							}
							$this->_menu .='</ul>';

							break;

							case 'LINKS':

							$lids = explode(",", $link['left_val']);
							$this->_menu .='<ul class="left_column_subcats left_column_links">';
							foreach ($lids as $lid){

								if (!$lid)
									continue;

								preg_match($this->pattern, $lid, $value);
								if(isset($value[1])){
									$id = (int)substr($lid, strlen($value[1]), strlen($lid));
									$vv = substr($lid, 0, strlen($value[1]));
									$this->makeMenuLinks($id, $vv);
								}


							}
							$this->_menu .='</ul>';




							break;

							case 'CMS_P':
							$this->_menu .='<div class="cms_page_content clearfix">';
							$id = str_replace("CMS_P", "", $link['left_val']);
							$cms = new CMS($id, $id_lang);
							$this->_menu .= $cms->content;
							$this->_menu .='</div>';

							break;


							case 'MAN':

							$lids = explode(",", $link['left_val']);
							$this->_menu .='<ul class="left_column_products left_column_manufacturers">';
							$i=1;
							foreach ($lids as $lid){	
								$id = str_replace("MAN", "", $lid);
								$this->getManufacturerwImage($id, $i);
								$i++;
							}
							$this->_menu .='</ul>';

							break;

							case 'PRD':

							$lids = explode(",", $link['left_val']);
							$this->_menu .='<ul class="row left_column_products">';
							$i=1;
							foreach ($lids as $lid){	
								$id = str_replace("PRD", "", $lid);
								$this->getProductwImage2($id, $i, $link['right']);
								$i++;
							}
							$this->_menu .='</ul>';
							break;

						}



						$this->_menu .='</div>';


					}


					if(($link['right']!="HIDE")){
						$this->_menu .='
						<div class="right_panel col-md-2 ' . (($link['left']=="HIDE") ? 'col-md-12 no_left_panel clearfix' : '') . '">';


						switch ($link['right'])
						{

							case 'PRD':
							$this->_menu .='<div class="rproduct_inner clearfix"><span class="mmtitle">	'.$this->l('We recommend').'</span>';
							$this->_menu .='<ul class="right_column_product">';
							$id = str_replace("PRD", "", $link['right_val']);
							$this->getProductwImage($id, 0);
							$this->_menu .='</ul></div>';
							break;

							case 'IMAGE':
							if(isset($link['link_right']) AND $link['link_right'] != "")
								$this->_menu .='<a href="'.$link['link_right'].'">';
							$this->_menu .='<img src="'.$this -> _path . '/uploads/'.$link['right_val'].'" class="img-responsive"/>';
							if(isset($link['link_right']) AND $link['link_right'] != "")
								$this->_menu .='</a>';
							break;

						}



						$this->_menu .='</div>
						';


					}
					if(($link['left']!="HIDE") || ($link['right']!="HIDE"))
						$this->_menu .='</div>';

					if(($link['bottom']!="HIDE")){
						$this->_menu .='<div class="bottom_panel ' . (($link['right']=="HIDE") && ($link['left']=="HIDE") ? 'no_top_panels' : '') . ' clearfix">';

						switch ($link['bottom'])
						{
							case 'IMAGE':
							if(isset($link['link_bottom']) AND $link['link_bottom'] != "")
								$this->_menu .='<a href="'.$link['link_bottom'].'">';
							$this->_menu .='<img src="'.$this -> _path . '/uploads/'.$link['bottom_val'].'" class="img-responsive bottomimage" alt="'.$link['bottom_val'].'" />';
							if(isset($link['link_bottom']) AND $link['link_bottom'] != "")
								$this->_menu .='</a>';
							break;

							case 'LINKS':

							$lids = explode(",", $link['bottom_val']);
							$this->_menu .='<ul class="bottom_column_links clearfix">';
							foreach ($lids as $lid){

								if (!$lid)
									continue;

								preg_match($this->pattern, $lid, $value);
								$id = (int)substr($lid, strlen($value[1]), strlen($lid));
								$vv = substr($lid, 0, strlen($value[1]));
								$this->makeMenuLinks($id, $vv);		
							}
							$this->_menu .='</ul>';


							break;

						}
						$this->_menu .='</div>
						';


					}



					$this->_menu .='</div>';

				}

				$this->_menu .= '</li>'.PHP_EOL;
			}

		}

		function hookdisplayAdminHomeQuickLinks() {
			echo '<li id="megamenu_block">
			<a  style="background:#F8F8F8 url(../modules/megamenuiqit/qllogo.png) no-repeat 50% 20px" href="index.php?controller=adminmodules&configure=megamenuiqit&token='.Tools::getAdminTokenLite('AdminModules').'">
			<h4>Mega Menu Configuration</h4>
			<p>Customize your dropdown menu</p>
			</a>
			</li>';
		}


		public function hookDisplayHeader($params) {
			$this -> context -> controller -> addCss($this -> _path . 'megamenuiqit.css');
			if((Configuration::get($this->name.'_mtransparent') == 1))
				$this -> context -> controller -> addCss($this -> _path . 'megamenuiqit_trans.css');
			if((Configuration::get($this->name.'_mcenter') == 1))
				$this -> context -> controller -> addCss($this -> _path . 'megamenuiqit_center.css');
			$this -> context -> controller -> addJS($this -> _path . 'megamenuiqit.js');

			$mstick  = Configuration::get($this->name.'_mstick');
			if($mstick==1){
				$this -> context -> controller -> addJS($this -> _path . 'megamenuiqit_stick.js');
			}
		}


		private function clearMenuCache()
		{
			$this->_clearCache('megamenuiqit.tpl');
		}
		public function hookActionObjectCategoryAddAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectCategoryUpdateAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectCategoryDeleteAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectCmsUpdateAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectCmsDeleteAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectCmsAddAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectSupplierUpdateAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectSupplierDeleteAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectSupplierAddAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectManufacturerUpdateAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectManufacturerDeleteAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectManufacturerAddAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectProductUpdateAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectProductDeleteAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookActionObjectProductAddAfter($params)
		{
			$this->clearMenuCache();
		}

		public function hookCategoryUpdate($params)
		{
			$this->clearMenuCache();
		}


		public function hookDisplayTop($params) {
			$sswidth  = Configuration::get($this->name.'_sswidth');
			if($sswidth==0){
				$this -> user_groups = ($this -> context -> customer -> isLogged() ? $this -> context -> customer -> getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
				if (!$this->isCached('megamenuiqit.tpl', $this->getCacheId()))
				{
					$this -> makeMegaMenu();
					$this -> makeMegaResponsiveMenu();
					$this->smarty->assign('mega_menu_width', $sswidth);
					$this->smarty->assign('mega_menu_style', Configuration::get($this->name.'_mmstyle'));
					$this->smarty->assign('mega_menu_border', Configuration::get($this->name.'_mborder'));
					$this->smarty->assign('mega_txt_color', Configuration::get($this->name.'_home_txt_color'));
					$this->smarty->assign('mega_home_bg', Configuration::get($this->name.'_home_bg'));
					$this->smarty->assign('mega_menu', $this->_menu);
					$this->smarty->assign('mega_responsivemenu', $this->_responsivemenu);
					$this -> smarty -> assign('this_path', $this -> _path);
				}
				return $this -> display(__FILE__, 'megamenuiqit.tpl', $this->getCacheId());
			}
		}

		public function getProductsImgs($product_id)
		{
			$sql = '
			(SELECT * from `'._DB_PREFIX_.'image` 
				WHERE id_product="'.$product_id.'" and cover=1)

union
(SELECT * from `'._DB_PREFIX_.'image` 
	WHERE id_product="'.$product_id.'" and cover=0 	ORDER BY `position` LIMIT 0,1 )

LIMIT 0,2
';
$result = Db::getInstance()->ExecuteS($sql);
return $result;
}

		public function hookmaxHeader($params)
		{
			$sswidth  = Configuration::get($this->name.'_sswidth');
			if($sswidth==1){
				$this -> user_groups = ($this -> context -> customer -> isLogged() ? $this -> context -> customer -> getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
				if (!$this->isCached('megamenuiqit.tpl', $this->getCacheId()))
				{
					$this -> makeMegaMenu();
					$this -> makeMegaResponsiveMenu();
					$this->smarty->assign('mega_menu', $this->_menu);
					$this->smarty->assign('mega_menu_width', $sswidth);
					$this->smarty->assign('mega_menu_style', Configuration::get($this->name.'_mmstyle'));
					$this->smarty->assign('mega_menu_border', Configuration::get($this->name.'_mborder'));
					$this->smarty->assign('mega_responsivemenu', $this->_responsivemenu);
					$this -> smarty -> assign('this_path', $this -> _path);
				}
				return $this -> display(__FILE__, 'megamenuiqit.tpl', $this->getCacheId());

			}
		}

	}
