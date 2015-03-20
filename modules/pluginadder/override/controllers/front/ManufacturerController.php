<?php



class ManufacturerController extends ManufacturerControllerCore

{

	protected function assignOne()

	{



		$this->manufacturer->description = Tools::nl2br(trim($this->manufacturer->description));

		$nbProducts = $this->manufacturer->getProducts($this->manufacturer->id, null, null, null, $this->orderBy, $this->orderWay, true);

		$this->pagination((int)$nbProducts);



		$products = $this->manufacturer->getProducts($this->manufacturer->id, $this->context->language->id, (int)$this->p, (int)$this->n, $this->orderBy, $this->orderWay);

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

			'nb_products' => $nbProducts,

			'products' => $products,

			'path' => ($this->manufacturer->active ? Tools::safeOutput($this->manufacturer->name) : ''),

			'manufacturer' => $this->manufacturer,

			'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM'),

			'body_classes' => array($this->php_self.'-'.$this->manufacturer->id, $this->php_self.'-'.$this->manufacturer->link_rewrite)

		));

	}

}



