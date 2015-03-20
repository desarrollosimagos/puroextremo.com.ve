<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class ProductsNavpn extends Module
{
	public function __construct()
	{
		$this->name = 'productsnavpn';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->bootstrap = true;

		parent::__construct();	

		$this->displayName = $this->l('Product page navigation arrows');
		$this->description = $this->l('Adds previous and next product links on product page ');
	}

	public function install()
	{
		return (parent::install() AND $this->registerHook('productnavs') AND  $this->registerHook('displayHeader'));
	}
	
	public function uninstall()
	{
		return (parent::uninstall());
	}

	public function getPositionInCategory($id_product, $id_category)
	{
		$result = Db::getInstance()->executeS('SELECT position
			FROM `'._DB_PREFIX_.'category_product`
			WHERE id_category = '.(int)$id_category.'
			AND id_product = '.(int)$id_product);
		if (count($result) > 0)
			return $result[0]['position'];
		return '';
	}

	public function getNextInCategory($position, $id_category)
	{
		$result = Db::getInstance()->executeS('SELECT id_product
			FROM `'._DB_PREFIX_.'category_product`
			WHERE id_category = '.(int)$id_category.'
			AND position > '.(int)$position.' ORDER BY position ASC LIMIT 1');
		if (count($result) > 0)
			return $result[0]['id_product'];
		return NULL;
	}

	public function getPreviousInCategory($position, $id_category)
	{
		$result = Db::getInstance()->executeS('SELECT id_product
			FROM `'._DB_PREFIX_.'category_product`
			WHERE id_category = '.(int)$id_category.'
			AND position < '.(int)$position.' ORDER BY position DESC LIMIT 1');
		if (count($result) > 0)
			return $result[0]['id_product'];
		return NULL;
	}
	
	public function hookDisplayHeader()
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product')
			return;
		$this->context->controller->addCSS(($this->_path).'productsnavpn.css', 'all');
	}
	
	public function hookproductnavs() {

		$id_product = (int)Tools::getValue('id_product');
		
		if (!$id_product)
			return true;
		
		$product = new Product($id_product, false, (int)Context::getContext()->language->id);
		$productInLastVisitedCategory = Product::idIsOnCategoryId($id_product, array('0' => array('id_category' => (int)$this->context->cookie->last_visited_category)));

		if ((!isset($this->context->cookie->last_visited_category) OR !$productInLastVisitedCategory) AND Validate::isLoadedObject($product))
			$this->context->cookie->last_visited_category = (int)$product->id_category_default;
		$cur_position = $this->getPositionInCategory($id_product, $this->context->cookie->last_visited_category);
		
		$nextProductId = $this->getNextInCategory($cur_position, $this->context->cookie->last_visited_category);
		$prevProductId = $this->getPreviousInCategory($cur_position, $this->context->cookie->last_visited_category);
		
		$prevLink = NULL;
		$prevName = NULL;
		$nextLink = NULL;
		$nextName = NULL;

		if (isset($prevProductId))
		{
			$cat_product = new Product($prevProductId, false, (int)Context::getContext()->language->id);
			$prevLink = $this->context->link->getProductLink($cat_product);
			$prevName = $cat_product->name;
		}

		if (isset($nextProductId))
		{
			$cat_product = new Product($nextProductId, false, (int)Context::getContext()->language->id);
			$nextLink = $this->context->link->getProductLink($cat_product);
			$nextName = $cat_product->name;
		}

		$this->context->smarty->assign(array('prevLink' => $prevLink, 'prevName' => $prevName, 'nextLink' => $nextLink, 'nextName' => $nextName));

		if ($this->context->cookie->last_visited_category == 2)
			$this->context->cookie->last_visited_category = (int)$product->id_category_default;

		return $this->display(__FILE__, 'productsnavpn.tpl');
	}
	
	
	public function hookHome($params)
	{
		$this->context->cookie->last_visited_category = 2;	
	}
}