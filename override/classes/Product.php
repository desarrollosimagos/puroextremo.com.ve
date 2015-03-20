<?php



class Product extends ProductCore

{

	/*
	* module: pluginadder
	* date: 2015-02-26 18:33:22
	* version: 1
	*/
	public static function getProductsImgs($product_id)

    {

	$sql = '

		(SELECT * from `'._DB_PREFIX_.'image` 

		WHERE id_product="'.$product_id.'" and cover=1)



		 union

				 (SELECT * from `'._DB_PREFIX_.'image` 

		WHERE id_product="'.$product_id.'" and cover=0 	ORDER BY `position` LIMIT 0,1 )

	

		LIMIT 0,2

		';

        $result = Db::getInstance()->ExecuteS($sql);

	return $result;

    }

}



