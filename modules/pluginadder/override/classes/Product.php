<?php



class Product extends ProductCore

{

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



