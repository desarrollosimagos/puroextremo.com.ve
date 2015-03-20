<?php



class PricesDropController extends PricesDropControllerCore

{





	public function initContent()

	{



		parent::initContent();



		$this->productSort();

		$nbProducts = Product::getPricesDrop($this->context->language->id, null, null, true);

		$this->pagination($nbProducts);



		$products = Product::getPricesDrop($this->context->language->id, (int)$this->p - 1, (int)$this->n, false, $this->orderBy, $this->orderWay);

		$this->addColorsToProductList($products);



		/************************* /Images Array ******************************/

		if(method_exists('Product','getProductsImgs'))

		{

		$image_array=array();

		for($i=0;$i<$nbProducts;$i++)

		{

			if(isset($products[$i]['id_product']))

				$image_array[$products[$i]['id_product']]= Product::getProductsImgs($products[$i]['id_product']);

		}

		$this->context->smarty->assign('productimg',(isset($image_array) AND $image_array) ? $image_array : NULL);

		}

		/************************* /Images Array ******************************/



		$this->context->smarty->assign(array(

			'products' => $products,

			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),

			'nbProducts' => $nbProducts,

			'homeSize' => Image::getSize(ImageType::getFormatedName('home')),

			'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')

		));



		$this->setTemplate(_PS_THEME_DIR_.'prices-drop.tpl');



	}



}



