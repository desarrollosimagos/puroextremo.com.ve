<?php



class SearchController extends SearchControllerCore

{







/*
	* module: pluginadder
	* date: 2015-02-26 18:33:23
	* version: 1
	*/
	public function initContent()

	{





		



		$query = Tools::replaceAccentedChars(urldecode(Tools::getValue('q')));

		$original_query = Tools::getValue('q');

		if ($this->ajax_search)

		{

			self::$link = new Link();

			$image = new Image();

			$searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);

			$taxes = Product::getTaxCalculationMethod();

			$currency = (int)Context::getContext()->currency->id;

			$iso_code = $this->context->language->iso_code;

			foreach ($searchResults as &$product){

			$imageID = $image->getCover($product['id_product']);

			if(isset($imageID['id_image']))

        	$imgLink = $this->context->link->getImageLink($product['prewrite'], (int)$product['id_product'].'-'.$imageID['id_image'], 'cart_default');

			else

			$imgLink = _THEME_PROD_DIR_.$iso_code."-default-cart_default.jpg";

			$product['product_link'] = $this->context->link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);

			$product['obr_thumb'] = $imgLink;

			$product['product_price'] = Product::getPriceStatic((int)$product['id_product'], false, NULL, 2);

			if ($taxes == 0 OR $taxes == 2)

				$product['product_price'] = Tools::displayPrice(Product::getPriceStatic((int)$product['id_product'], true), $currency);

			elseif ($taxes == 1)

				$product['product_price'] = Tools::displayPrice(Product::getPriceStatic((int)$product['id_product'], false), $currency);

			}

			die(Tools::jsonEncode($searchResults));

		}



		if ($this->instant_search && !is_array($query))

		{

			$this->productSort();

			$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));

			$this->p = abs((int)(Tools::getValue('p', 1)));

			$search = Search::find($this->context->language->id, $query, 1, 10, 'position', 'desc');

			Hook::exec('actionSearch', array('expr' => $query, 'total' => $search['total']));

			$nbProducts = $search['total'];

			$this->pagination($nbProducts);



			$this->addColorsToProductList($search['result']);



			

		     if(method_exists('Product','getProductsImgs'))

		    {

			$image_array=array();

			for($i=0;$i<$nbProducts;$i++)

			{	

				if(isset($search['result'][$i]['id_product']))

					$image_array[$search['result'][$i]['id_product']]= Product::getProductsImgs($search['result'][$i]['id_product']);

			}

			$this->context->smarty->assign('productimg',(isset($image_array) AND $image_array) ? $image_array : NULL);

			}

			



			$this->context->smarty->assign(array(

				'products' => $search['result'], 
				'search_products' => $search['result'],

				'nbProducts' => $search['total'],

				'search_query' => $original_query,

				'instant_search' => $this->instant_search,

				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))));

		}

		elseif (($query = Tools::getValue('search_query', Tools::getValue('ref'))) && !is_array($query))

		{

			$this->productSort();

			$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));

			$this->p = abs((int)(Tools::getValue('p', 1)));

			$original_query = $query;

			$query = Tools::replaceAccentedChars(urldecode($query));			

			$search = Search::find($this->context->language->id, $query, $this->p, $this->n, $this->orderBy, $this->orderWay);

			foreach ($search['result'] as &$product)

				$product['link'] .= (strpos($product['link'], '?') === false ? '?' : '&').'search_query='.urlencode($query).'&results='.(int)$search['total'];

			Hook::exec('actionSearch', array('expr' => $query, 'total' => $search['total']));

			$nbProducts = $search['total'];

			$this->pagination($nbProducts);



			$this->addColorsToProductList($search['result']);



			

					if(method_exists('Product','getProductsImgs'))

		{

			$image_array=array();

			for($i=0;$i<$nbProducts;$i++)

			{	

				if(isset($search['result'][$i]['id_product']))

					$image_array[$search['result'][$i]['id_product']]= Product::getProductsImgs($search['result'][$i]['id_product']);

			}

			$this->context->smarty->assign('productimg',(isset($image_array) AND $image_array) ? $image_array : NULL);

			}

			



			$this->context->smarty->assign(array(

				'products' => $search['result'], 
				'search_products' => $search['result'],

				'nbProducts' => $search['total'],

				'search_query' => $original_query,

				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))));

		}

		elseif (($tag = urldecode(Tools::getValue('tag'))) && !is_array($tag))

		{

			$nbProducts = (int)(Search::searchTag($this->context->language->id, $tag, true));

			$this->pagination($nbProducts);

			$result = Search::searchTag($this->context->language->id, $tag, false, $this->p, $this->n, $this->orderBy, $this->orderWay);

			Hook::exec('actionSearch', array('expr' => $tag, 'total' => count($result)));



			$this->addColorsToProductList($result);



			

			if(method_exists('Product','getProductsImgs'))

			{

			$image_array=array();

			for($i=0;$i<$nbProducts;$i++)

			{

				if(isset($result[$i]['id_product']))

					$image_array[$result[$i]['id_product']]= Product::getProductsImgs($result[$i]['id_product']);

			}

			$this->context->smarty->assign('productimg',(isset($image_array) AND $image_array) ? $image_array : NULL);

			}

			



			$this->context->smarty->assign(array(

				'search_tag' => $tag,

				'products' => $result, 
				'search_products' => $result,

				'nbProducts' => $nbProducts,

				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))));

		}

		else

		{

			$this->context->smarty->assign(array(

				'products' => array(),

				'search_products' => array(),

				'pages_nb' => 1,

				'nbProducts' => 0));

		}

		$this->context->smarty->assign(array('add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'), 'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')));



		$this->setTemplate(_PS_THEME_DIR_.'search.tpl');



		parent::initContent();



	}









	



}



