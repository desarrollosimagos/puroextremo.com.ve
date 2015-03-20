<?php



class SupplierController extends SupplierControllerCore

{

	protected function assignOne()

	{



		if (Configuration::get('PS_DISPLAY_SUPPLIERS'))

		{

			$this->supplier->description = Tools::nl2br(trim($this->supplier->description));

			$nbProducts = $this->supplier->getProducts($this->supplier->id, null, null, null, $this->orderBy, $this->orderWay, true);

			$this->pagination((int)$nbProducts);



			$products = $this->supplier->getProducts($this->supplier->id, $this->context->cookie->id_lang, (int)$this->p, (int)$this->n, $this->orderBy, $this->orderWay);

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



			$this->context->smarty->assign(

				array(

					'nb_products' => $nbProducts,

					'products' => $products,

					'path' => ($this->supplier->active ? Tools::safeOutput($this->supplier->name) : ''),

					'supplier' => $this->supplier,

					'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM'),

					'body_classes' => array(

						$this->php_self.'-'.$this->supplier->id,

						$this->php_self.'-'.$this->supplier->link_rewrite

					)

				)

			);

		}

		else

			Tools::redirect('index.php?controller=404');



	}

}



