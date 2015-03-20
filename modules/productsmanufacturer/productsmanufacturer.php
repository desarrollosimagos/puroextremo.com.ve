<?php

if (!defined('_PS_VERSION_'))
	exit;

class ProductsManufacturer extends Module
{
	private $html;

	public function __construct()
	{
		$this->name = 'productsmanufacturer';
		$this->version = '1.0';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->tab = 'front_office_features';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Products of the same manufacturer');
		$this->description = $this->l('Adds a block on the product page that displays products from the same manufacturer.');
	}

	public function install()
	{
		Configuration::updateValue('pmslid_DISPLAY_PRICE', 0);
		Configuration::updateValue('pmslid_limit', 10);
		$this->_clearCache('productsmanufacturer.tpl');

		return (parent::install()
			&& $this->registerHook('productfooter')
			&& $this->registerHook('header')
			&& $this->registerHook('addproduct')
			&& $this->registerHook('updateproduct')
			&& $this->registerHook('deleteproduct')
		);
	}

	public function uninstall()
	{
		Configuration::deleteByName('pmslid_DISPLAY_PRICE');
		$this->_clearCache('productsmanufacturer.tpl');

		return parent::uninstall();
	}

	public function getContent()
	{
		$this->html = '';
		if (Tools::isSubmit('submitCross') &&
			Tools::getValue('pmslid_DISPLAY_PRICE') != 0 &&
			Tools::getValue('pmslid_DISPLAY_PRICE') != 1
		)
			$this->html .= $this->displayError('Invalid displayPrice.');
		elseif (Tools::isSubmit('submitCross'))
		{
			
			Configuration::updateValue('pmslid_DISPLAY_PRICE',Tools::getValue('pmslid_DISPLAY_PRICE'));
			Configuration::updateValue('pmslid_limit',Tools::getValue('pmslid_limit'));
			$this->_clearCache('productsmanufacturer.tpl');
			$this->html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
		}
		$this->html .= $this->renderForm();

		return $this->html;
	}

	private function getCurrentProduct($products, $id_current)
	{
		if ($products)
		{
			foreach ($products as $key => $product)
			{
				if ($product['id_product'] == $id_current)
					return $key;
			}
		}

		return false;
	}

	public function hookProductFooter($params)
	{
		$id_product = (int)$params['product']->id;
		$product = $params['product'];
	
		if(isset($params['product']->id_manufacturer) && $params['product']->id_manufacturer)
			{

		$cache_id = 'productsmanufacturer|'.$id_product.'|'.((int)$params['product']->id_manufacturer);

		if (!$this->isCached('productsmanufacturer.tpl', $this->getCacheId($cache_id)))
		{
			

			// Get infos
			$manufacturer_products = Manufacturer::getProducts($params['product']->id_manufacturer, $this->context->language->id, 1, Configuration::get('pmslid_limit')); /* 100 products max. */
			
			
			// Remove current product from the list
			if (is_array($manufacturer_products) && count($manufacturer_products))
			{
				foreach ($manufacturer_products as $key => $manufacturer_product)
				{
					if ($manufacturer_product['id_product'] == $id_product)
					{
						unset($manufacturer_products[$key]);
						break;
					}
				}



				$taxes = Product::getTaxCalculationMethod();
				if (!Configuration::get('pmslid_DISPLAY_PRICE'))
				{
					foreach ($manufacturer_products as $key => $manufacturer_product)
					{
						if ($manufacturer_product['id_product'] != $id_product)
						{
							$manufacturer_products[$key]['show_price'] = 0;
				
						}
					}
				}

			}

			// Display tpl
			$this->smarty->assign(
				array(
					'manufacturer_products' => $manufacturer_products,
					'ProdDisplayPrice' => Configuration::get('pmslid_DISPLAY_PRICE')
				)
			);
		}

		return $this->display(__FILE__, 'productsmanufacturer.tpl', $this->getCacheId($cache_id));
		}
	}

	public function hookHeader($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product')
			return;
		$this->context->controller->addCSS($this->_path.'css/productsmanufacturer.css', 'all');
		$this->context->controller->addJS($this->_path.'js/productsmanufacturer.js');
	}

	public function hookAddProduct($params)
	{
		$this->_clearCache('productsmanufacturer.tpl');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('productsmanufacturer.tpl');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('productsmanufacturer.tpl');
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
						'type' => 'switch',
						'label' => $this->l('Display products\' prices'),
						'desc' => $this->l('Show the prices of the products displayed in the block.'),
						'name' => 'pmslid_DISPLAY_PRICE',
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
						'type' => 'text',
						'label' => $this->l('Limit'),
						'name' => 'pmslid_limit',
						'desc' => $this->l('Maximum numer of product showed'),
						'size' => 30,
					),
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
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get(
			'PS_BO_ALLOW_EMPLOYEE_FORM_LANG'
		) : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitCross';
		$helper->currentIndex = $this->context->link->getAdminLink(
				'AdminModules',
				false
			).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
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
			'pmslid_DISPLAY_PRICE' => Tools::getValue('pmslid_DISPLAY_PRICE',Configuration::get('pmslid_DISPLAY_PRICE')),
			'pmslid_limit' => Tools::getValue('pmslid_limit', Configuration::get('pmslid_limit')),

		);
	}

}
