<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2($object)
{

	Configuration::updateValue('thmedit_product_grid_border', 0);

	Configuration::updateValue('thmedit_product_grid_border', 0);

	Configuration::updateValue('thmedit_cart_box2_bg' , '#f6f6f6');
	Configuration::updateValue('thmedit_cart_box_border' , '#dddddd');

	Configuration::updateValue('thmedit_product_box_h_status' , 0);
	Configuration::updateValue('thmedit_product_box_h_bg' , 'transparent');
	Configuration::updateValue('thmedit_product_box_h_txt' , '#777777');
	Configuration::updateValue('thmedit_product_box_h_txt_h' , '#777777');
	Configuration::updateValue('thmedit_product_box_h_price' , '#f13340');


	Configuration::updateValue('thmedit_functional_buttons_bg' , '#ffffff');
	Configuration::updateValue('thmedit_functional_buttons_txt' , '#777777');
	Configuration::updateValue('thmedit_functional_buttons_txt_h' , '#333333');

	Configuration::updateValue('thmedit_menu_italics' , 0);
	Configuration::updateValue('thmedit_headings_italics' , 0);

	Configuration::updateValue('thmedit_menu_home_icon' , 1);
	Configuration::updateValue('thmedit_product_names' , 0);
	Configuration::updateValue('thmedit_product_reference' , 0);

	Configuration::updateValue('thmedit_content_block_border' , 0);
	Configuration::updateValue('thmedit_content_block_border_c' , '#EBEBEB');
	Configuration::updateValue('thmedit_product_grid_center' , 1);

	Configuration::updateValue('thmedit_logo_width' , 4);

	Configuration::updateValue('textbanners_style', 2);
	Configuration::updateValue('textbanners_border', '1');
	Configuration::updateValue('CROSSSELLING_NBR_M', 10);
	$object->registerHook('calculateGrid');
	return true;
}
