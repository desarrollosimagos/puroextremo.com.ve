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

class CategorySlider extends Module {
	private $_html = '';
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

	function __construct() {
		$this -> name = 'categoryslider';
		$this -> tab = 'front_office_features';
		$this -> version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';
		$this -> need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this -> displayName = $this->l('Category slider on Hompage');
		$this -> description = $this->l('Displays products from selected category on hompage');
	}

	function install() {
		$this -> _clearCache('categoryslider.tpl');
		if (!Configuration::updateValue('CATEGORY_FEATURED_NBR', 8) || !Configuration::updateValue('CATEGORY_FEATURED_ID', 3) || !parent::install() || !$this -> registerHook('displayHome') || !$this -> registerHook('displayHeader') || !$this -> registerHook('addproduct') || !$this -> registerHook('updateproduct') || !$this -> registerHook('deleteproduct'))
			return false;
		return true;
	}

	public function getContent() {
		if (Tools::isSubmit('submitModule')) {
			$this->_clearCache('categoryslider.tpl');


				$nbr = (int)(Tools::getValue('nbr'));
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of products');
			else
				Configuration::updateValue('CATEGORY_FEATURED_NBR', (int)($nbr));

				$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && Configuration::updateValue('CATEGORY_FEATURED_ID', (string)implode(',', $items))))
				$errors[] =$this->l('Unable to update settings.');
			
			if (isset($errors) AND sizeof($errors))
				$this->_html .= $this -> displayError(implode('<br />', $errors));
			else
				$this->_html .= $this -> displayConfirmation($this->l('Settings updated'));

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
						array(
						'type' => 'text',
						'label' => $this->l('Number of products'),
						'name' => 'nbr',
						'desc' => $this->l('Number of products to show'),
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
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
		);
		return $helper->generateForm(array($fields_form));
	}

public function getConfigFieldsValues()
	{
		return array(
			'nbr' => Tools::getValue('CATEGORY_FEATURED_NBR', Configuration::get('CATEGORY_FEATURED_NBR')),
		);
	}


public function renderChoicesSelect()
	{
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();
		
		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';
		

		// BEGIN Categories
		$shop = new Shop((int)Shop::getContextShopID());
		$html .= '<optgroup label="'.$this->l('Categories').'">';	
		$html .= $this->generateCategoriesOption(
			Category::getNestedCategories(null, (int)$this->context->language->id, true), $items);
		$html .= '</optgroup>';
		
	
		$html .= '</select>';
		return $html;
	}



	private function getMenuItems()
	{
	
			$conf = Configuration::get('CATEGORY_FEATURED_ID');
			if (strlen($conf))
				return explode(',', Configuration::get('CATEGORY_FEATURED_ID'));
			else
				return array();
		
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

	
					$category = new Category((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="'.$id.'">'.$category->name.'</option>'.PHP_EOL;
		
		}
		return $html.'</select>';
	}

	private function generateCategoriesOption($categories, $items_to_skip = null)
	{
		$html = '';

		foreach ($categories as $key => $category)
		{
			if (isset($items_to_skip) && !in_array('CAT'.(int)$category['id_category'], $items_to_skip))
			{
				$shop = (object) Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);

		}

		return $html;
	}

	

	public function hookDisplayHeader($params) {
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;
		$this -> context -> controller -> addCss($this -> _path . 'categoryslider.css');
		$this -> context -> controller -> addJS($this -> _path . 'categoryslider.js');
	}

	public function hookDisplayHome($params) {

		if (!$this -> isCached('categoryslider.tpl', $this->getCacheId('categoryslider'))) {

			$cid = (Configuration::get('CATEGORY_FEATURED_ID'));
			$nb = (int)(Configuration::get('CATEGORY_FEATURED_NBR'));
			$menu_item = explode(',', $cid);
			$id_lang = (int)$this -> context -> language -> id;
			$id_shop = (int)Shop::getContextShopID();

			$categories = array();

			foreach ($menu_item as $item) {
				if (!$item)
					continue;
				$id = $item;

				$category = new Category((int)$id, $id_lang);
				if (Validate::isLoadedObject($category)) {
					$categories[$item]['id'] = $item;
					$categories[$item]['name'] = $category -> name;
					$categories[$item]['products'] = $category -> getProducts($id_lang, 1, ($nb ? $nb : 10));
					
					if(method_exists('Product','getProductsImgs'))
				{
					$image_array=array();

					for($i=0;$i<count($categories[$item]['products']);$i++)
					{
						if(isset($categories[$item]['products'][$i]['id_product']))
							$image_array[$categories[$item]['products'][$i]['id_product']]= Product::getProductsImgs($categories[$item]['products'][$i]['id_product']);				
					}
					$categories[$item]['productimg'] = (isset($image_array) AND $image_array) ? $image_array : NULL;
					}



					
				}

			}

			$this -> smarty -> assign(array('categories' => $categories, 'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'), 'homeSize' => Image::getSize(ImageType::getFormatedName('home')) ));

		}
		return $this -> display(__FILE__, 'categoryslider.tpl', $this -> getCacheId('categoryslider'));
	}

	public function hookAddProduct($params) {
		$this->_clearCache('categoryslider.tpl');
	}

	public function hookUpdateProduct($params) {
		$this->_clearCache('categoryslider.tpl');
	}

	public function hookDeleteProduct($params) {
		$this->_clearCache('categoryslider.tpl');
	}

}
