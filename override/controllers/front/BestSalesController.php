<?php



class BestSalesController extends BestSalesControllerCore

{



	/*
	* module: pluginadder
	* date: 2015-02-26 18:33:22
	* version: 1
	*/
	public function initContent()

	{

		if (Configuration::get('PS_DISPLAY_BEST_SELLERS'))

		{

			parent::initContent();

			

			$this->productSort();

			$nbProducts = (int)ProductSale::getNbSales();

			$this->pagination($nbProducts);

			

			$products = ProductSale::getBestSales($this->context->language->id, $this->p - 1, $this->n, $this->orderBy, $this->orderWay);

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

				'nbProducts' => $nbProducts,

				'homeSize' => Image::getSize(ImageType::getFormatedName('home')),

				'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')

				));

			

			$this->setTemplate(_PS_THEME_DIR_.'best-sales.tpl');

		}

		else

			Tools::redirect('index.php?controller=404');

	}







}



