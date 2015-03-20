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

class ManufactuterSlider extends Module {
	private $_html = '';
	private $user_groups;
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $page_name = '';
	private $spacer_size = '5';
	private $_postErrors = array();

	public function __construct() {
		$this -> name = 'manufactuterslider';
		$this -> tab = 'front_office_features';
		$this -> version = 1.1;
		$this -> author = 'IQIT-COMMERCE.COM';
		$this -> need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this -> displayName = $this->l('Manufactuter Slider');
		$this -> description = $this->l('Displays a block of manufacturers/brands');
	}

	public function install() {
		$this -> _clearCache('manufactuterslider.tpl');
		if (!parent::install() || !$this -> registerHook('displayHome') || !$this -> registerHook('displayHeader') || !$this -> registerHook('actionObjectManufacturerDeleteAfter') || !$this -> registerHook('actionObjectManufacturerAddAfter') || !$this -> registerHook('actionObjectManufacturerUpdateAfter'))
			return false;
		return true;

	}
	
	public function getContent() {
		if (Tools::isSubmit('submitModule')) {
			$this->_clearCache('manufactuterslider.tpl');
			

			$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && Configuration::updateValue('manufactuterslider_id', (string)implode(',', $items))))
			$errors[] =$this->l('Unable to update settings.');


			if (isset($errors) AND sizeof($errors))
				$this -> _html .= $this -> displayError(implode('<br />', $errors));
			else
				$this -> _html .= $this -> displayConfirmation($this->l('Settings updated'));

		}

		$this->_html .= $this->renderForm();
		return $this->_html;
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Menu Top Link'),
					'icon' => 'icon-link'
				),
				'input' => array(
					array(
						'type' => 'link_choice',
						'label' => '',
						'name' => 'link',
						'lang' => true,
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
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
		);
		return $helper->generateForm(array($fields_form));
	}


	public function renderChoicesSelect()
	{
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();
		
		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';

		// BEGIN Manufacturer
		$html .= '<optgroup label="'.$this->l('Manufacturer').'">';
		$manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id);
		foreach ($manufacturers as $manufacturer)
			if (!in_array($manufacturer['id_manufacturer'], $items))
				$html .= '<option value="'.$manufacturer['id_manufacturer'].'">'.$spacer.$manufacturer['name'].'</option>';
		$html .= '</optgroup>';
	
		$html .= '</select>';
		return $html;
	}

	private function makeMenuOption()
	{
		$menu_item = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$html = '<select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">';
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;
			preg_match($this->pattern, $item, $values);
			$id = (int)substr($item, strlen($values[1]), strlen($item));

	
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($manufacturer))
						$html .= '<option selected="selected" value="'.$id.'">'.$manufacturer->name.'</option>'.PHP_EOL;
		
		}
		return $html.'</select>';
	}

	private function getMenuItems()
	{
	
			$conf = Configuration::get('manufactuterslider_id');
			if (strlen($conf))
				return explode(',', Configuration::get('manufactuterslider_id'));
			else
				return array();
		
	}




public function hookDisplayHome($params) {
	
	$m_id = (Configuration::get('manufactuterslider_id'));
	$m_ids = explode(',', $m_id);
	$id_lang = (int)$this -> context -> language -> id;
	$manufacturers = array();
	



	foreach ($m_ids as  $item) {
		if (!$item)
			continue;
		$id = $item;

		$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
		if (Validate::isLoadedObject($manufacturer)){
			$manufacturers[$item]['id_manufacturer'] = $item;
			$manufacturers[$item]['name'] = $manufacturer -> name;
			$manufacturers[$item]['link_rewrite'] = $manufacturer-> link_rewrite;

		}

	}

	$this -> smarty -> assign('manufacturers', $manufacturers);
	$this -> smarty -> assign('manufacturerSize', Image::getSize('mf_image'));
	return $this -> display(__FILE__, 'manufactuterslider.tpl');
}

public function hookHeader($params) {
	$this -> context -> controller -> addCSS(($this -> _path) . 'manufactuterslider.css', 'all');
	$this -> context -> controller -> addJS(($this -> _path) . 'manufactuterslider.js');
}

public function hookActionObjectManufacturerUpdateAfter($params) {
	$this -> _clearCache('manufactuterslider.tpl');
}

public function hookActionObjectManufacturerAddAfter($params) {
	$this -> _clearCache('manufactuterslider.tpl');
}

public function hookActionObjectManufacturerDeleteAfter($params) {
	$this -> _clearCache('manufactuterslider.tpl');
}

}
