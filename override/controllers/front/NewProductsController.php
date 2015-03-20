<?php



class NewProductsController extends NewProductsControllerCore

{



	/*
	* module: pluginadder
	* date: 2015-02-26 18:33:22
	* version: 1
	*/
	public function initContent()

	{



		parent::initContent();



		$this->productSort();



		
		if (!Tools::getIsset('orderway') || !Tools::getIsset('orderby'))

		{

			$this->orderBy = 'date_add';

			$this->orderWay = 'DESC';

		}



		$nbProducts = (int)Product::getNewProducts(

			$this->context->language->id,

			(isset($this->p) ? (int)($this->p) - 1 : null),

			(isset($this->n) ? (int)($this->n) : null),

			true

			);



		$this->pagination($nbProducts);



		$products = Product::getNewProducts($this->context->language->id, (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);

		$this->addColorsToProductList($products);



		

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

		



		$this->context->smarty->assign(array(

			'products' => $products,

			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),

			'nbProducts' => (int)($nbProducts),

			'homeSize' => Image::getSize(ImageType::getFormatedName('home')),

			'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')

			));



		$this->setTemplate(_PS_THEME_DIR_.'new-products.tpl');



	}

}



