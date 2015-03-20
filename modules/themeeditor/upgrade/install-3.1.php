<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_1($object)
{

	Configuration::updateValue('thmedit_product_grid_border', 0);
	Configuration::updateValue('thmedit_product_border_color', '#EBEBEB');
	Configuration::updateValue('thmedit_price_font_s', 13);
	Configuration::updateValue('thmedit_labels_font_s', 9);
	Configuration::updateValue('thmedit_headings_center', 0);
	Configuration::updateValue('thmedit_top_banner_color', '#000000');
	Configuration::updateValue('thmedit_force_boxed',  0);
	Configuration::updateValue('thmedit_content_shadow', 0);
	Configuration::updateValue('thmedit_show_condition',  1);
	Configuration::updateValue('thmedit_grid_size_lg', 5);
	Configuration::updateValue('thmedit_grid_size_md', 4);
	Configuration::updateValue('thmedit_grid_size_sm',  3);
	Configuration::updateValue('thmedit_grid_size_ms', 2);
	Configuration::updateValue('thmedit_breadcrumb_bg',  'transparent');
	Configuration::updateValue('thmedit_breadcrumb_color', '#777777');
	Configuration::updateValue('thmedit_product_left_size',  4);
	Configuration::updateValue('thmedit_product_center_size', 8);
	Configuration::updateValue('thmedit_content_input_select', 1);
	return true;
}
