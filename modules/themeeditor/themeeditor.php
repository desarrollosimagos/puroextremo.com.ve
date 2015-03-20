<?php

if (!defined('_PS_VERSION_'))
	exit;

class ThemeEditor extends Module {
	
	private $systemFonts = array(
		array(
			'id_option' => 0,
			'name' => 'Arial'
			),
		array(
			'id_option' => 1,
			'name' => 'Georgia'
			),
		array(
			'id_option' => 2,
			'name' => 'Tahoma'
			),
		array(
			'id_option' => 3,
			'name' => 'Times New Roman'
			),
		array(
			'id_option' => 4,
			'name' => 'Verdana'
			)
		);
							        
	private $_html = '';
	private $user_groups;
	private $_postErrors = array();

	public function __construct() {
		$this->name = 'themeeditor';
		$this->tab = 'front_office_features';
		$this->version = '3.2';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Theme Editor module');
		$this->description = $this->l('Allows to change theme design');
	
		$this->configName = 'thmedit';

		$this->defaults = array(
		'big_responsive' => 1,
		'ajax_popup' => 1,
		'yotpo_stars' => 0, 
		'preloader' => 0,
		'top_width' => 1,
		'footer_width' => 1,
		'content_margin' => 0,
		'left_on_phones' => 1,
		'breadcrumb_width' => 1,
		'breadcrumb_bg' => 'transparent',
		'breadcrumb_color' => '#777777',
		'force_boxed' => 0,
		'content_shadow' => 0,
		'global_bg_color' => '#ffffff',
		'global_bg_type' => 2,
		'global_bg_image' => '',
		'global_bg_pattern' => 0,
		'global_bg_repeat' => 0,
		'global_bg_position' => 0,
		'global_bg_fixed' => 0,

		'top_banner_color' => '#000000',

		'top_bg_color' => '#f8f8f8',
		'top_bg_type' => 2,
		'top_bg_image' => '',
		'top_bg_pattern' => 0,
		'top_bg_repeat' => 0,
		'top_bg_position' => 0,
		'top_ddown_bg' => '#ffffff',
		'top_ddown_color' => '#777777',
		'top_txt_color' => '#777777',
		'top_link_color' => '#777777',
		'top_link_h_color' => '#333333',
		'top_border' => 0,
		'top_border_color' => '#dddddd',

		'h_wrap_bg_color' => 'transparent',
		'h_wrap_bg_type' => 2,
		'h_wrap_bg_image' => '',
		'h_wrap_bg_pattern' => 0,
		'h_wrap_bg_repeat' => 0,
		'h_wrap_bg_position' => 0,

		'header_bg_color' => '#ffffff',
		'header_bg_type' => 2,
		'header_bg_image' => '',
		'header_bg_pattern' => 0,
		'header_bg_repeat' => 0,
		'header_bg_position' => 0,
		'header_inner_border' => 1,
		'header_inner_border_color' => '#d6d4d4',
		'header_input_text'	=> '#9c9b9b',
		'header_input_bg' => '#ffffff',
		'header_link_color' => '#777777',
		'header_link_h_color' => '#333333',
		'cart_icon' => 1,
		'cart_bg' => '#333333',
		'cart_txt' => '#ffffff',
		'cart2_bg' => '#eeeeee',
		'cart2_txt' => '#777777',
		'cart_box_bg' => '#ffffff',
		'cart_box_txt' => '#777777',
		'cart_box_txt_h' => '#333333',
		'cart_box2_bg' => '#f6f6f6',
		'cart_box_border' => '#dddddd',



		'menu_bg_color' => '#000000',
		'menu_bg_type' => 2,
		'menu_bg_image' => '',
		'menu_bg_pattern' => 0,
		'menu_bg_repeat' => 0,
		'menu_bg_position' => 0,
		'menu_home_icon' => 1,
		'menu_inner_border' => 1,
		'menu_inner_border_color' => '#a2a2a2',
		'menu_border_color' => '#dddddd',
		'menu_bold' => 0,
		'menu_italics' => 0,
		'menu_uppercase' => 1,
		'menu_link_color' => '#ffffff',
		'menu_link_bg' => '#ffffff',
		'menu_link_h_color' => '#333333',
		'menu_label_color' => '#ffffff',
		'menu_label_bg' => '#f13340',
		'submenu_bg_color' => '#ffffff',
		'submenu_border_color' => '#dddddd',
		'submenu_link_color' => '#777777',
		'submenu_link_h_color' => '#333333',


		'c_wrap_bg_color' => 'transparent',
		'c_wrap_bg_type' => 2,
		'c_wrap_bg_image' => '',
		'c_wrap_bg_pattern' => 0,
		'c_wrap_bg_repeat' => 0,
		'c_wrap_bg_position' => 0,

		'content_bg_color' => '#ffffff',
		'content_bg_type' => 2,
		'content_bg_image' => '',
		'content_bg_pattern' => 0,
		'content_bg_repeat' => 0,
		'content_bg_position' => 0,
		'content_inner_border_color' => '#dddddd',
		'content_txt_color' => '#777777',
		'content_link_color' => '#777777',
		'content_link_h_color' => '#333333',
		'content_input_text'	=> '#9c9b9b',
		'content_input_bg' => '#ffffff',
		'content_input_select' => 1,
		'content_headings_color' => '#777777',
		'content_box_bg' => '#f8f8f8',
		'content_box_color' => '#777777',
		'content_bgh_status' => 0,
		'content_bgh' => '#F3F3F3',
		'content_block_border' => 0,
		'content_block_border_c' => '#EBEBEB',

		'f_wrap_width' => 1,
		'f_wrap_bg_color' => '#ffffff',
		'f_wrap_bg_type' => 2,
		'f_wrap_bg_image' => '',
		'f_wrap_bg_pattern' => 0,
		'f_wrap_bg_repeat' => 0,
		'f_wrap_bg_position' => 0,
		'f_wrap_padding' => 0,

		'footer_bg_color' => '#f8f8f8',
		'footer_bg_type' => 2,
		'footer_bg_image' => '',
		'footer_bg_pattern' => 0,
		'footer_bg_repeat' => 0,
		'footer_bg_position' => 0,
		'footer_inner_border' => 1,
		'footer_inner_border_color' => '#dddddd',
		'footer_border' => 1,	
		'footer_border_color' => '#dddddd',
		'footer_txt_color' => '#777777',
		'footer_link_color' => '#777777',
		'footer_link_h_color' => '#333333',
		'footer_input_text'	=> '#9c9b9b',
		'footer_input_bg' => '#ffffff',
		'footer_social_round' => 1,
		'footer_social_color' => '#ffffff',
		'footer_social_bg' => '#99999b',
		'footer_headings_color' => '#555454',
		'footer_bgh_status' => 0,
		'footer_bgh' => '#F3F3F3',

		'show_desc' => 0,
		'desc_style' => 1,
		'show_subcategories' => 0,
		'grid_size_lg' => 5,
		'grid_size_md' => 4,
		'grid_size_sm' => 3,
		'grid_size_ms' => 2,
		'productlist_view' => 1,
		'product_hover' => 1,
		'show_condition' => 1,
		'product_names' => 0,
		'product_reference' => 0,
		'product_grid_border' => 0,
		'product_grid_center' => 1,
		'product_border_color' => '#EBEBEB',
		'product_box_h_status' => 0,
		'product_box_h_bg' => 'transparent',
		'product_box_h_txt' => '#777777',
		'product_box_h_txt_h' => '#333333',
		'product_box_h_price' => '#f13340',
		'functional_buttons' => 1,
		'functional_buttons_bg' => '#ffffff',
		'functional_buttons_txt' => '#777777',
		'functional_buttons_txt_h' => '#333333',
		'carousel_style' => 1,
		'car_color' => '#dddddd',
		'car_color_h' => '#333333',
		'car_bg' => '#f8f8f8',
		'car_bg_h' => '#B6B6B6',
		'top_pagination' => 1,
		'show_buttons' => 1,
		'show_on_hover' => 0,
		'product_right_block' => 1,
		'product_left_size' => 4,
		'product_center_size' => 8,
		'label_uppercase' => 1,
		'label_new_bg' => '#6ad4ff',
		'label_new_txt' => '#ffffff',
		'label_sale_bg' => '#f13340',
		'label_sale_txt' => '#ffffff',
		'label_online_bg' => '#ffffff',
		'label_online_txt' => '#777777',
		'label_stock_bg' => '#46B64F',
		'label_stock_txt' => '#ffffff',
		'label_ostock_bg' => '#ffffff',
		'label_ostock_txt' => '#ff7430',
		'price_color' => '#f13340',
		'stars_color' => '#f13340',
		'default_font_s' => 13,
		'heading_font_s' => 20,
		'subheading_font_s' => 16,
		'boxtitle_font_s' => 13,
		'fboxtitle_font_s' => 13,
		'mainmenu_font_s' => 14,
		'breadcrumb_font_s' => 11,
		'pname_font_s' => 13,
		'price_font_s' => 13,
		'labels_font_s' => 9,
		'buttons_font_s' => 12,
		'font_headings_type' => 1,
		'font_headings_link' => 'http://fonts.googleapis.com/css?family=Open+Sans:400,700',
		'font_headings_name' => '\'Open Sans\', sans-serif',
		'font_headings_default' => 1,
		'font_txt_type' => 2,
		'font_txt_link' => 'http://fonts.googleapis.com/css?family=Open+Sans:400,700',
		'font_txt_name' => '\'Open Sans\', sans-serif',
		'font_txt_default' => 1,
		'headings_uppercase' => 1,
		'headings_bold' => 0,
		'headings_italics' => 0,
		'headings_center' => 0,
		'btn_uppercase' => 1,	
		'btn_rounded' => 1,
		'btn_small_bg' => '#6f6f6f',
		'btn_small_txt' => '#ffffff',
		'btn_small_bg_h' => '#575757',
		'btn_small_txt_h' => '#ffffff',
		'btn_medium_bg' => '#43b754',
		'btn_medium_txt' => '#ffffff',
		'btn_medium_bg_h' => '#3aa04c',
		'btn_medium_txt_h' => '#ffffff',
		'btn_cart_bg' => '#6f6f6f',
		'btn_cart_txt' => '#ffffff',
		'btn_cart_bg_h' => '#575757',
		'btn_cart_txt_h' => '#ffffff',
		'btn_cartp_bg' => '#6f6f6f',
		'btn_cartp_txt' => '#ffffff',
		'btn_cartp_bg_h' => '#575757',
		'btn_cartp_txt_h' => '#ffffff',
		'top_bar' => 1,
		'search_position' => 1,
		'logo_width' => 0,
		'logo_position' => 1,
		'header_padding' => 0,
		'header_increase' => 0,
		'table_bg' => '#f8f8f8',
		'table_color' => '#777777',
		'ddown_bg' => '#ffffff',
		'ddown_color' => '#777777',
		'second_footer' => 1,
		'copyright_text' => '2014 Powered by iqit-commerce.com. All Rights Reserved',
		'footer_img_disable' => 0,
		'custom_css' => '',
		'custom_js' => '',
		);
	}

	
	public function install() {
		if (parent::install() AND $this->registerHook('displayHeader') AND $this->registerHook('calculateGrid') AND $this->registerHook('displayAdminHomeQuickLinks')) {
			$this->setDefaults();
			return true;
		} else {
			return false;
		}
	}

	public function uninstall() {
		if (parent::uninstall())
		{
			foreach ($this->defaults as $default => $value) 
				Configuration::deleteByName($this->configName.'_'.$default);
			return true;
		}
		return false;	
	}

	public function setDefaults(){
		foreach ($this->defaults as $default => $value) {

			if($default == 'copyright_text')
			{
					$message_trads = array();
					foreach (Language::getLanguages(false) as $lang)
							$message_trads[(int)$lang['id_lang']] = '<p>2014 Powered by iqit-commerce.com. All Rights Reserved</p>';
					
			Configuration::updateValue($this->configName.'_'.$default, $message_trads, true);
			}
			else
			Configuration::updateValue($this->configName.'_'.$default, $value);
		}

		Configuration::updateValue('PS_QUICK_VIEW', 1);
	}

	public function getContent() {

		$this->context->controller->addJqueryPlugin('colorpicker');
		$this->context->controller->addCSS(($this->_path).'css/admin/admin.css');
		$this->context->controller->addJS(($this->_path).'js/admin/script.js');
		$this->context->controller->addJS(($this->_path).'js/admin/shBrushJScript.js');
	
		$errors = '';
		$id_shop = (int)$this->context->shop->id;

		if (Tools::isSubmit('save_editor')) {
			
			$this->registerHook('calculateGrid');

			foreach ($this->defaults as $default => $value) {

				if($default == 'custom_css')
				{
					if (isset($_POST[$default]))
						Configuration::updateValue($this->configName.'_'.$default, $_POST[$default]);			
				}
				elseif($default == 'copyright_text')
				{
					$message_trads = array();
					foreach ($_POST as $key => $value)
						if (preg_match('/copyright_text_/i', $key))
						{
							$id_lang = preg_split('/copyright_text_/i', $key);
							$message_trads[(int)$id_lang[1]] = $value;
						}
						Configuration::updateValue($this->configName.'_'.$default, $message_trads, true);
				}
				elseif($default == 'footer_img_disable')
				{
					if ($default == 'footer_img_disable' && isset($_FILES['footer_img_src']) && isset($_FILES['footer_img_src']['tmp_name']) && !empty($_FILES['footer_img_src']['tmp_name']))
					{
						Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
						if (file_exists(dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg'))
							unlink(dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg');
						if ($error = ImageManager::validateUpload($_FILES['footer_img_src']))
							$errors .= $error;
						elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['footer_img_src']['tmp_name'], $tmp_name))
							return false;
						elseif (!ImageManager::resize($tmp_name, dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg'))
							$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));
						if (isset($tmp_name))
							unlink($tmp_name);
					}
					if (file_exists(dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg'))
					{
						Configuration::updateValue($this->configName.'_'.$default, 0);
					}
				}
				else
				Configuration::updateValue($this->configName.'_'.$default, (Tools::getValue($default)));
			}


			Configuration::updateValue('PS_QUICK_VIEW', (int)(Tools::getValue('PS_QUICK_VIEW')));


			$this->generateCss();

			if (isset($errors) AND $errors!='')
				$this->_html .= $this -> displayError($errors);
			else
				$this->_html .= $this -> displayConfirmation($this->l('Settings updated'));

		}

		elseif (Tools::isSubmit('reset_editor')) {
			
			$this->setDefaults();
			$this->generateCss();
			$this->_html .= $this -> displayConfirmation($this->l('Settings reset'));

		}

		// Delete logo image retrocompat 1.5
		elseif (Tools::isSubmit('deleteFooterImage'))
		{
			if (!file_exists(dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg'))
				$errors .= $this->displayError($this->l('This action cannot be made.'));
			else
			{
				unlink(dirname(__FILE__).'/img/footer_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('footer_img_disable', 1);
				Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
			}
			$this->_html .= $errors;
		}

		elseif (Tools::isSubmit('exportConfiguration'))
		{
						
			$var =  array();

			foreach ($this->defaults as $default => $value) {

				if($default == 'copyright_text')
				{
					foreach (Language::getLanguages(false) as $lang)
						$var[$default][(int)$lang['id_lang']] = Tools::getValue($default.'_'.(int)$lang['id_lang'], Configuration::get($default, (int)$lang['id_lang']));
				}
				else
					$var[$default] = Configuration::get($this->configName.'_'.$default);
		}
			
		$var['PS_QUICK_VIEW'] = (int)Tools::getValue('PS_QUICK_VIEW', Configuration::get('PS_QUICK_VIEW'));

			$file_name = time().'.csv';
			$fd = fopen($this->getLocalPath().$file_name, 'w+');
			file_put_contents($this->getLocalPath().'export/'.$file_name, print_r(serialize($var), true));
			fclose($fd);
			Tools::redirect(_PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/export/'.$file_name);
		}

		elseif (Tools::isSubmit('importConfiguration'))
		{

			if(isset($_FILES['uploadConfig']) && isset($_FILES['uploadConfig']['tmp_name']))
			{
				$str = file_get_contents($_FILES['uploadConfig']['tmp_name']);
				$arr = unserialize($str);


				foreach ($arr as $default => $value) {
					if($default == 'copyright_text')
					{
						$message_trads = array();
						foreach ($_POST as $key => $value)
							if (preg_match('/copyright_text_/i', $key))
							{
								$id_lang = preg_split('/copyright_text_/i', $key);
								$message_trads[(int)$id_lang[1]] = $value;
							}
							Configuration::updateValue($this->configName.'_'.$default, $message_trads, true);
						}

						elseif($default == 'PS_QUICK_VIEW')
						Configuration::updateValue('PS_QUICK_VIEW', $value);

						elseif($default != 'footer_img_disable')
							Configuration::updateValue($this->configName.'_'.$default, $value);

					}

					$this->generateCss();

					if (isset($errors) AND $errors!='')
						$this->_html .= $this -> displayError($errors);
					else
						$this->_html .= $this -> displayConfirmation($this->l('Configuration imported'));
				}
				else
					$this->_html .= $this -> displayError($this->l('No config file'));				

			}


		$this->_html .= '<div class="panel clearfix">
		<form class="pull-left" id="importForm" method="post" enctype="multipart/form-data" action="'.$this->context->link->getAdminLink('AdminModules', false).'&importConfiguration&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">
		<div style="display:inline-block;"><input type="file" id="uploadConfig" name="uploadConfig" /></div>
	
		<button type="submit" class="btn btn-default btn-lg"><span class="icon icon-upload"></span> '.$this->l('Import configuration').'</button>
		
		</form>

		


		<a href="'.$this->context->link->getAdminLink('AdminModules', false).'&exportConfiguration&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">
		<button class="btn btn-default btn-lg pull-right"><span class="icon icon-share"></span> '.$this->l('Export configuration(Export only saved settigns, save before export)').'</button>
		</a></div>';
		$this->_html .= $this->renderForm();

		return $this->_html;
	}


	public function renderForm()
	{

		$fields_form_main = array(
			'form' => array(
				'tab_name' => 'main_tab',
				'legend' => array(
					'title' => $this->l('Main configuration'),
					'icon' => 'icon-edit'
					),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Big resolution'),
						'label' => $this->l('Enable media query for screens with min-width 1320px '),
						'name' => 'big_responsive',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Ajax pop-up'),
						'name' => 'ajax_popup',
						'desc' => $this->l('Ajax pop up after add to cart'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Quick view'),
						'name' => 'PS_QUICK_VIEW',
						'desc' => $this->l('Quick view popup'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Yotpo stars'),
						'name' => 'yotpo_stars',
						'is_bool' => true,
						'desc' => $this->l('Show yotpo module product ratings on product list pages'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Page preloader'),
						'name' => 'preloader',
						'desc' => $this->l('Show preloader until page is fully loaded'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					)
				),
			);
			
			$globalFields = $this->globalFormFields('global');
			
			$fields_form_global_design = array(
			'form' => array(
				'tab_name' => 'globa_design_tab',
				'legend' => array(
					'title' => $this->l('Global design options'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Content margin'),
						'name' => 'content_margin',
						'desc' => $this->l('Add 20px margin on top and bottom of content'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Force boxed design'),
						'name' => 'force_boxed',
						'desc' => $this->l('Top bar, footer slider will be set to content width no matter individual style'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Content shadow'),
						'name' => 'content_shadow',
						'desc' => $this->l('Enable this only with force boxed design, it will add shadow to boxed content'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'select',
						'label' => $this->l('Left column position on phones'),
						'name' => 'left_on_phones',
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Below main content')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Above main content')
								)
							),                           
    						'id' => 'id_option',                        
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'select',
						'label' => $this->l('Breadcrumb width'),
						'name' => 'breadcrumb_width',
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Content width')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Full width')
								)
							),                          
    						'id' => 'id_option',                       
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'color',
						'label' => $this->l('Breadcrumb background color'),
						'name' => 'breadcrumb_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Breadcrumb text color'),
						'name' => 'breadcrumb_color',
						'separator' => true,
						'size' => 30,
					),
						array(
						'type' => 'link_url',
						'label' => $this->l('Columns configuration'),
						'desc' => $this->l('Configure columns for each shop page'),
						'name' => 'column_url',
						'separator' => true,
						'url' => 'index.php?tab=AdminThemes&id_theme='.$this->context->shop->id_theme.'&updatetheme&token='.Tools::getAdminTokenLite('AdminThemes'),
						),
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
												array(
						'type' => 'switch',
						'label' => $this->l('Fixed positon background'),
						'name' => 'global_bg_fixed',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
										
					)
				),
			);
			
			$fields_form_products = array(
			'form' => array(
				'tab_name' => 'products_tab',
				'legend' => array(
					'title' => $this->l('Product lists, Category page, Product page'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Show description of category'),
						'name' => 'show_desc',
						'row_title' => $this->l('Category page'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'select',
						'label' => $this->l('Category description style'),
						'name' => 'desc_style',
						'desc' => $this->l('You can choose style of category description if enabled'),
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Description below image')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Description inside image')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show subcategories thumbs'),
						'name' => 'show_subcategories',
						'desc' => $this->l('Show subcategories images on category page below description'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					
					array(
						'type' => 'select',
						'label' => $this->l('Default product list view'),
						'name' => 'productlist_view',
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Grid view')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('List view')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Display top pagination'),
						'name' => 'top_pagination',
						'desc' => $this->l('Possibility to hide top pagination on category pages'),
						'is_bool' => true,
							'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'select',
						'row_title' => $this->l('Product lists'),
						'label' => $this->l('Product name length'),
						'name' => 'product_names',
						'options' => array(
							'query' => array(
								array(
								'id_option' => 1,
								'name' => $this->l('Two line')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('One line')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Product reference'),
						'desc' => $this->l('Show product reference on product list'),
						'name' => 'product_reference',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'select',
						'label' => $this->l('Product grid size for large desktops'),
						'name' => 'grid_size_lg',
						'desc' => $this->l('Note: Each column enabled decrease this value by 1. After modifications of this values maybe needed change of home_default image size'),
						'options' => array(
							'query' => array(
								array(
								'id_option' => 6,
								'name' => $this->l('6 products')
								),
								array(
								'id_option' => 5,
								'name' => $this->l('5 products')
								),
								array(
								'id_option' => 4,
								'name' => $this->l('4 products')
								),
								array(
								'id_option' => 3,
								'name' => $this->l('3 products')
								),
								array(
								'id_option' => 2,
								'name' => $this->l('2 products')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'select',
						'label' => $this->l('Product grid size for small desktops and tablets landscape'),
						'name' => 'grid_size_md',
						'desc' => $this->l('Note: Each column enabled decrease this value by 1. After modifications of this values maybe needed change of home_default image size'),
						'options' => array(
							'query' => array(
								array(
								'id_option' => 5,
								'name' => $this->l('5 products')
								),
								array(
								'id_option' => 4,
								'name' => $this->l('4 products')
								),
								array(
								'id_option' => 3,
								'name' => $this->l('3 products')
								),
								array(
								'id_option' => 2,
								'name' => $this->l('2 products')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'select',
						'label' => $this->l('Product grid size for tablets portrait'),
						'name' => 'grid_size_sm',
						'options' => array(
							'query' => array(
								array(
								'id_option' => 4,
								'name' => $this->l('4 products')
								),
								array(
								'id_option' => 3,
								'name' => $this->l('3 products')
								),
								array(
								'id_option' => 2,
								'name' => $this->l('2 products')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'select',
						'label' => $this->l('Product grid size for phone landscape'),
						'name' => 'grid_size_ms',
						'separator' => true,
						'options' => array(
							'query' => array(
								array(
								'id_option' => 2,
								'name' => $this->l('2 products')
								),
								array(
								'id_option' => 1,
								'name' => $this->l('1 products')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Center product box'),
						'name' => 'product_grid_center',
						'desc' => $this->l('Center align of product name, price, ratins, buttons'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'select',
						'label' => $this->l('Product hover effect'),
						'name' => 'product_hover',
						'desc' => $this->l('Adds shadow/border to product on hover'),
						'options' => array(
							'query' => array(
								array(
								'id_option' => 2,
								'name' => $this->l('Border')
								),
								array(
								'id_option' => 1,
								'name' => $this->l('Box shadow')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('No effect')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'select',
						'label' => $this->l('Grid product border'),
						'name' => 'product_grid_border',
						'options' => array(
							'query' => array(
								array(
								'id_option' => 2,
								'name' => $this->l('Full border')
								),
								array(
								'id_option' => 4,
								'name' => $this->l('Bottom border')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('No borders')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'color',
						'label' => $this->l('Grid product border color'),
						'name' => 'product_border_color',
						'size' => 30,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Diffrent colors on hover'),
						'upper_separator' => true,
						'name' => 'product_box_h_status',
						'desc' => $this->l('Enable product hover color  options'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),

					array(
						'type' => 'color',
						'label' => $this->l('Hovered product box background color'),
						'name' => 'product_box_h_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Hovered product box text color'),
						'name' => 'product_box_h_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Hovered product box hover text color'),
						'name' => 'product_box_h_txt_h',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Hovered product box price color'),
						'name' => 'product_box_h_price',
						'size' => 30,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Functional buttons'),
						'upper_separator' => true,
						'name' => 'functional_buttons',
						'desc' => $this->l('Quick view, compare and wishlist on product grid and sliders'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'color',
						'label' => $this->l('Functional buttons background color'),
						'name' => 'functional_buttons_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Functional buttons link color'),
						'name' => 'functional_buttons_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Functional buttons link hover color'),
						'name' => 'functional_buttons_txt_h',
						'size' => 30,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show add cart buttons'),
						'name' => 'show_buttons',
						'desc' => $this->l('Show add cart button on product listings'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show additional info only on hover'),
						'name' => 'show_on_hover',
						'separator' => true,
						'desc' => $this->l('Show product ratings and add cart button only on hover'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'select',
						'row_title' => $this->l('Product  carousels'),
						'label' => $this->l('Product carousel style'),
						'name' => 'carousel_style',
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Next/Prev arrows on products line')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Next/Prev arrows on block title line')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'color',
						'label' => $this->l('Carousel arrow color'),
						'name' => 'car_color',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Carousel arrow hover color'),
						'name' => 'car_color_h',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Carousel arrow bg(if title style)'),
						'name' => 'car_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Carousel arrow hover bg(if title style)'),
						'name' => 'car_bg_h',
						'size' => 30,
						'separator' => true,
						),
						
						array(
						'type' => 'switch',
						'label' => $this->l('Show product contidion on product page'),
						'name' => 'show_condition',
						'row_title' => $this->l('Product  page'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show additional right block on product page'),
						'name' => 'product_right_block',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'select',
						'label' => $this->l('Product page image container width(grid)'),
						'name' => 'product_left_size',
						'desc' => '<strong>'.$this->l('Value from this field + value from field below must be equal to 12').'</strong>',
						'options' => array(
							'query' => array(
								array(
								'id_option' => 6,
								'name' => '6'
								),
								array(
								'id_option' => 5,
								'name' => '5'
								),
							array(
								'id_option' => 4,
								'name' => '4'
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'select',
						'label' => $this->l('Product page info container width(grid)'),
						'name' => 'product_center_size',
						'desc' => '<strong>'.$this->l('Value from this field + value from field above must be equal to 12').'</strong> '.$this->l('Additionl right column enabled will automaticly decrease this value by 3'),
						'separator' => true,
						'options' => array(
							'query' => array(
								array(
								'id_option' => 8,
								'name' => '8'
								),
								array(
								'id_option' => 7,
								'name' => '7'
								),
							array(
								'id_option' => 6,
								'name' => '6'
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Labels letter uppercase'),
						'row_title' => $this->l('Labels, prices, ratings'),
						'name' => 'label_uppercase',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),	
					array(
						'type' => 'color',
						'label' => $this->l('New product label background'),
						'name' => 'label_new_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('New product label text color'),
						'name' => 'label_new_txt',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Sale product label background'),
						'name' => 'label_sale_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Sale product label text color'),
						'name' => 'label_sale_txt',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Online product label background'),
						'name' => 'label_online_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Online product label text color'),
						'name' => 'label_online_txt',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('In stock product label background'),
						'name' => 'label_stock_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('In stock product label text color'),
						'name' => 'label_stock_txt',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Out of stock product label background'),
						'name' => 'label_ostock_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Out of stock product label text color'),
						'name' => 'label_ostock_txt',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Product price color'),
						'name' => 'price_color',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Product rating stars color'),
						'name' => 'stars_color',
						'desc' => $this->l('Apply only to productcomments module, not Yotpo module'),
						'size' => 30,
					),
					)
				),
			);

			$fields_form_fonts = array(
			'form' => array(
				'tab_name' => 'fonts_tab',
				'legend' => array(
					'title' => $this->l('Fonts and Typo'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Menu main link font size'),
						'name' => 'mainmenu_font_s',
						'row_title' => $this->l('Font sizes'),
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Page heading font size'),
						'name' => 'heading_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Page Subheading font size'),
						'name' => 'subheading_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Block title font size'),
						'name' => 'boxtitle_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Footer block title font size'),
						'name' => 'fboxtitle_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Breadcrumb font size'),
						'name' => 'breadcrumb_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Product lists product name size'),
						'name' => 'pname_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Product price size on product lists'),
						'name' => 'price_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Product labels size(sale, new, etc)'),
						'name' => 'labels_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Buttons font size'),
						'name' => 'buttons_font_s',
						'suffix'=> 'px',
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Default font size'),
						'name' => 'default_font_s',
						'suffix'=> 'px',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Headings font type'),
						'name' => 'font_headings_type',
						'desc' => $this->l('You can choose beteween google fonts and system fonts'),
						'row_title' => $this->l('Headings fonts'),
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Google fonts')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Default')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'text',
						'label' => $this->l('Google font url'),
						'desc' => $this->l('Example: http://fonts.googleapis.com/css?family=Open+Sans:400,700 ').'Add 400 and 700 font weigh if exist. If you need adds latin-ext or cyrilic too. <a href="https://www.google.com/fonts" target="_blank">'.$this->l('Check google font database').'</a>',
						'name' => 'font_headings_link',
						'preffix_wrapper' => 'heading_google_wrapper',
						'wrapper_hidden' => true,
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Google font family'),
						'name' => 'font_headings_name',
						'desc' => $this->l('Example: \'Open Sans\', sans-serif '),
						'suffix_wrapper' => true,
						'size' => 30,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Default font'),
						'name' => 'font_headings_default',
						'preffix_wrapper' => 'heading_default_wrapper',
						'suffix_wrapper' => true,
						'wrapper_hidden' => true,
						'options' => array(
							'query' => $this->systemFonts,                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Headings uppercase letters'),
						'name' => 'headings_uppercase',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Headings bold font'),
						'name' => 'headings_bold',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Headings italics font'),
						'name' => 'headings_italics',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'switch',
						'label' => $this->l('Center main headings'),
						'name' => 'headings_center',
						'is_bool' => true,
						'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'select',
						'label' => $this->l('Normal text font type'),
						'name' => 'font_txt_type',
						'row_title' => $this->l('Normal text fonts'),
						'options' => array(
							'query' => array(array(
								'id_option' => 2,
								'name' => $this->l('Same as header')
								),
							array(
								'id_option' => 1,
								'name' => $this->l('Google fonts')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Default')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'text',
						'label' => $this->l('Google font url'),
						'desc' => $this->l('Example: http://fonts.googleapis.com/css?family=Open+Sans:400,700 ').'Add 400 and 700 font weigh if exist.  If you need adds latin-ext or cyrilic too. <a href="https://www.google.com/fonts" target="_blank">'.$this->l('Check google font database').'</a>',
						'name' => 'font_txt_link',
						'preffix_wrapper' => 'txt_google_wrapper',
						'wrapper_hidden' => true,
						'size' => 30,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Google font family'),
						'name' => 'font_txt_name',
						'desc' => $this->l('Example: \'Open Sans\', sans-serif '),
						'separator' => true,
						'suffix_wrapper' => true,
						'size' => 30,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Default font'),
						'name' => 'font_txt_default',
						'separator' => true,
						'preffix_wrapper' => 'txt_default_wrapper',
						'suffix_wrapper' => true,
						'wrapper_hidden' => true,
						'options' => array(
							'query' => $this->systemFonts,                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
										)
				),
			);
			
			$fields_form_buttons = array(
			'form' => array(
				'tab_name' => 'buttons_tab',
				'legend' => array(
					'title' => $this->l('Buttons'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Rounded corners'),
						'name' => 'btn_rounded',
						'desc' => $this->l('Rounded corners of all buttons'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Buttons uppercase letters'),
						'name' => 'btn_uppercase',
						'is_bool' => true,
						'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'color',
						'label' => $this->l('Default button background'),
						'name' => 'btn_small_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Default button text color'),
						'name' => 'btn_small_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Default button hover background'),
						'name' => 'btn_small_bg_h',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Default button hover text color'),
						'name' => 'btn_small_txt_h',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Action/confirm button background'),
						'name' => 'btn_medium_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Action/confirm button text color'),
						'name' => 'btn_medium_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Action/confirm button hover background'),
						'name' => 'btn_medium_bg_h',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Action/confirm button hover text color'),
						'name' => 'btn_medium_txt_h',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart button background'),
						'name' => 'btn_cart_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart button text color'),
						'name' => 'btn_cart_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart button hover background'),
						'name' => 'btn_cart_bg_h',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart button text hover'),
						'name' => 'btn_cart_txt_h',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart product page button background'),
						'name' => 'btn_cartp_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart product page button text color'),
						'name' => 'btn_cartp_txt',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart product page button hover background'),
						'name' => 'btn_cartp_bg_h',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Add to cart product page button text hover'),
						'name' => 'btn_cartp_txt_h',
					
						'size' => 30,
					),
					)
				),
			);
			
			$globalFields = $this->globalFormFields('top');

			$fields_form_topbar = array(
			'form' => array(
				'tab_name' => 'topbar_tab',
				'legend' => array(
					'title' => $this->l('Top bar'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'color',
						'label' => $this->l('Top banner background color'),
						'name' => 'top_banner_color',
						'size' => 30,
						),
						array(
						'type' => 'switch',
						'label' => $this->l('Enable top bar(top navigation)'),
						'name' => 'top_bar',
						'desc' => $this->l('Place where languages, compare and wishlist is showed'),
						'is_bool' => true,
						'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'select',
						'label' => $this->l('Top nav width'),
						'name' => 'top_width',
						'desc' => $this->l('Choose width of top menu links bar'),
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Full width')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Content width')
								)
							),                           
    						'id' => 'id_option',                           
    						'name' => 'name'
    						)
						),
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],

						$globalFields['txt_color'],
						$globalFields['link_color'],
						$globalFields['link_h_color'],
						$globalFields['border'],
						$globalFields['border_color'],
						array(
						'type' => 'color',
						'label' => $this->l('Dropdown background color'),
						'name' => 'top_ddown_bg',
						'desc' => $this->l('Languages and curriences'),
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Dropdown text color'),
						'name' => 'top_ddown_color',
						'desc' => $this->l('Languages and curriences'),
						'size' => 30,
					),
					)
				),
			);
				
			

			$globalFields = $this->globalFormFields('h_wrap');

			$fields_form_headerw = array(
			'form' => array(
				'tab_name' => 'headerw_tab',
				'legend' => array(
					'title' => $this->l('Header wrapper'),
					'icon' => 'icon-edit'
					),

				'input' => array(
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
					)
				),
			);

			$globalFields = $this->globalFormFields('header');

			$fields_form_header = array(
			'form' => array(
				'tab_name' => 'header_tab',
				'legend' => array(
					'title' => $this->l('Header'),
					'icon' => 'icon-edit'
					),

				'input' => array(
						array(
						'type' => 'switch',
						'label' => $this->l('Additional 20 px bottom padding'),
						'name' => 'header_padding',
						'desc' => $this->l('Beteween menu and header container. This option is helpfull with background image'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'text',
						'label' => $this->l('Header height increase'),
						'name' => 'header_increase',
						'suffix' => 'px',
						'desc' => $this->l('I more than 0, increase header with filled pixels. It will also increse place for logo. You can also decrease header size by minus value'),
						'size' => 20,   
						),
						array(
						'type' => 'select',
						'label' => $this->l('Logo container width'),
						'name' => 'logo_width',
						'desc' => $this->l('I you want use bigger logo change this value'),
						'options' => array(
							'query' => array(
								array(
								'id_option' => 0,
								'name' => $this->l('Grid size - 4(normal)')
								),
								array(
								'id_option' => 2,
								'name' => $this->l('Grid size - 6(large)')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'select',
						'label' => $this->l('Logo position'),
						'name' => 'logo_position',
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Left')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Center')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'select',
						'label' => $this->l('Search position'),
						'name' => 'search_position',
						'separator' => true,
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Header')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Right side of menu')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
						$globalFields['inner_border'],
						$globalFields['inner_border_color'],
						$globalFields['input_text'],
						$globalFields['input_bg'],
						$globalFields['link_color'],
						$globalFields['link_h_color'],
						array(
						'type' => 'select',
						'label' => $this->l('Cart icon'),
						'upper_separator' => true,
						'name' => 'cart_icon',
						'row_title' => $this->l('Cart'),
						'desc' => $this->l('Icon style'),
						'options' => array(
							'query' => array(
								array(
								'id_option' => 2,
								'name' => $this->l('Bag icon')
								),
								array(
								'id_option' => 1,
								'name' => $this->l('Cart icon')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('No icon')
								)
							),                           
    						'id' => 'id_option',                           
    						'name' => 'name'
    						)
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart title bg'),
						'name' => 'cart_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart title text'),
						'name' => 'cart_txt',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart info bg'),
						'name' => 'cart2_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart info text'),
						'name' => 'cart2_txt',
						'size' => 30,
						'separator' => true,
						),
						array(
						'type' => 'color',
						'row_title' => $this->l('Cart block(box)'),
						'label' => $this->l('Cart box background color'),
						'desc' => $this->l('Cart box is shown when you hover on cart'),
						'name' => 'cart_box_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart box text color'),
						'desc' => $this->l('Cart box is shown when you hover on cart'),
						'name' => 'cart_box_txt',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart box hover text'),
						'desc' => $this->l('Cart box is shown when you hover on cart'),
						'name' => 'cart_box_txt_h',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart box button container background'),
						'desc' => $this->l('Cart box is shown when you hover on cart'),
						'name' => 'cart_box2_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Cart box border color'),
						'desc' => $this->l('Cart box is shown when you hover on cart'),
						'name' => 'cart_box_border',
						'size' => 30,
						),


					)
				),
			);
		
			$globalFields = $this->globalFormFields('menu');
			$globalFields2 = $this->globalFormFields('submenu');

			$fields_form_menu = array(
			'form' => array(
				'tab_name' => 'menu_tab',
				'legend' => array(
					'title' => $this->l('Menu'),
					'icon' => 'icon-edit'
					),

				'input' => array(
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
						array(
						'type' => 'switch',
						'label' => $this->l('Show home menu icon'),
						'name' => 'menu_home_icon',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						$globalFields['inner_border'],
						$globalFields['inner_border_color'],
						$globalFields['border_color'],
						array(
						'type' => 'switch',
						'label' => $this->l('Menu main links bold'),
						'name' => 'menu_bold',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'switch',
						'label' => $this->l('Menu main links italics'),
						'name' => 'menu_italics',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						array(
						'type' => 'switch',
						'label' => $this->l('Menu main links uppercase'),
						'name' => 'menu_uppercase',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
						
						$globalFields['link_color'],
						array(
						'type' => 'color',
						'label' => $this->l('Link hover background color'),
						'name' => 'menu_link_bg',
						'size' => 30,
						),
						$globalFields['link_h_color'],
						array(
						'type' => 'color',
						'label' => $this->l('Label tag color'),
						'name' => 'menu_label_color',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Label tag background color'),
						'name' => 'menu_label_bg',
						'size' => 30,
						),
					)
				),
			);
		
		$fields_form_submenu = array(
			'form' => array(
				'tab_name' => 'submenu_tab',
				'legend' => array(
					'title' => $this->l('Submenu'),
					'icon' => 'icon-edit'
					),

				'input' => array(
			
						$globalFields2['bg_color'],
						$globalFields2['border_color'],
						$globalFields2['link_color'],
						$globalFields2['link_h_color'],
					)
				),
			);
		
		$globalFields = $this->globalFormFields('c_wrap');
			
			$fields_form_contentw = array(
			'form' => array(
				'tab_name' => 'contentw_tab',
				'legend' => array(
					'title' => $this->l('Content wrapper'),
					'icon' => 'icon-edit'
					),

				'input' => array(
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
					)
				),
			);

			$globalFields = $this->globalFormFields('content');

			$fields_form_content = array(
			'form' => array(
				'tab_name' => 'content_tab',
				'legend' => array(
					'title' => $this->l('Content'),
					'icon' => 'icon-edit'
					),

				'input' => array(
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
						$globalFields['inner_border_color'],
						$globalFields['txt_color'],
						$globalFields['link_color'],
						$globalFields['link_h_color'],
						$globalFields['input_text'],
						$globalFields['input_bg'],
						array(
						'type' => 'select',
						'label' => $this->l('Select box and radio buttons color'),
						'name' => 'content_input_select',
						'options' => array(
							'query' => array(
								array(
								'id_option' => 1,
								'name' => $this->l('White')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Black')
								)
							),                           
    						'id' => 'id_option',                           
    						'name' => 'name'
    						)
						),
						
						array(
						'type' => 'color',
						'label' => $this->l('Headings color'),
						'name' => 'content_headings_color',
						'size' => 30,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Headings and block title background status'),
						'name' => 'content_bgh_status',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'color',
						'label' => $this->l('Headings and block titlebackground color'),
						'name' => 'content_bgh',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Columns block border'),
						'name' => 'content_block_border',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'color',
						'label' => $this->l('Columns block border color'),
						'name' => 'content_block_border_c',
						'separator' => true,
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Table heading background'),
						'name' => 'table_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Table heading text color'),
						'name' => 'table_color',
						'size' => 30,
						'separator' => true,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Box background'),
						'desc' => $this->l('For example background of form  or table footer'),
						'name' => 'content_box_bg',
						'size' => 30,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Box background text color'),
						'name' => 'content_box_color',
						'size' => 30,
						'separator' => true,
					),
						array(
						'type' => 'color',
						'label' => $this->l('Dropdown background color'),
						'name' => 'ddown_bg',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Dropdown text color'),
						'name' => 'ddown_color',
						'size' => 30,
						'separator' => true,
					),
					)
				),
			);

			$file = dirname(__FILE__).'/img/footer_logo_'.(int)$this->context->shop->id.'.jpg';
			$footerlogo = (file_exists($file) ? '<img src="'.$this->_path.'img/footer_logo_'.(int)$this->context->shop->id.'.jpg">' : '');


			$globalFields = $this->globalFormFields('f_wrap');
			
			$fields_form_footerw = array(
			'form' => array(
				'tab_name' => 'footerw_tab',
				'legend' => array(
					'title' => $this->l('Footer wrapper'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Additional top and bottom 20px padding'),
						'name' => 'f_wrap_padding',
						'is_bool' => true,
						'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Circle')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Square')
								)
							),
						),
						array(
						'type' => 'select',
						'label' => $this->l('Footer wrapper width'),
						'name' => 'f_wrap_width',
						'desc' => $this->l('Choose width of footer'),
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Full width')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Content width')
								)
							),                           
    						'id' => 'id_option',                           
    						'name' => 'name'
    						)
						),

						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
					)
				),
			);

			$globalFields = $this->globalFormFields('footer');

			$fields_form_footer = array(
			'form' => array(
				'tab_name' => 'footer_tab',
				'legend' => array(
					'title' => $this->l('Footer'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Footer width'),
						'name' => 'footer_width',
						'desc' => $this->l('Choose width of footer'),
						'options' => array(
							'query' => array(array(
								'id_option' => 1,
								'name' => $this->l('Full width')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Content width')
								)
							),                           
    						'id' => 'id_option',                           
    						'name' => 'name'
    						)
						),
						$globalFields['bg_color'],
						$globalFields['bg_type'],
						$globalFields['bg_image'],
						$globalFields['bg_repeat'],
						$globalFields['bg_position'],
						$globalFields['bg_pattern'],
						$globalFields['border'],
						$globalFields['border_color'],
						$globalFields['inner_border'],
						$globalFields['inner_border_color'],
						$globalFields['txt_color'],
						$globalFields['link_color'],
						$globalFields['link_h_color'],
						$globalFields['input_text'],
						$globalFields['input_bg'],
						array(
						'type' => 'color',
						'row_title' => $this->l('Footer block titles'),
						'upper_separator' => true,
						'label' => $this->l('Footer block title color'),
						'size' => 30,
						'name' => 'footer_headings_color',
						),
						array(
						'type' => 'switch',
						'label' => $this->l('Footer block title background status'),
						'name' => 'footer_bgh_status',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'color',
						'label' => $this->l('Footer block title background color'),
						'name' => 'footer_bgh',
						'separator' => true,
						'size' => 30,
					),
						array(
						'type' => 'switch',
						'row_title' => $this->l('Social icons'),
						'label' => $this->l('Circle or squre social icons'),
						'desc' => $this->l('If enabled circle icons, if disabled square icons'),
						'name' => 'footer_social_round',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Circle')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Square')
								)
							),
						),
						array(
						'type' => 'color',
						'label' => $this->l('Footer social icon text color'),
						'name' => 'footer_social_color',
						'size' => 30,
						),
						array(
						'type' => 'color',
						'label' => $this->l('Footer social icon background color'),
						'name' => 'footer_social_bg',
						'separator' => true,
						'size' => 30,
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show copyrights and payments logos'),
						'name' => 'second_footer',
						'is_bool' => true,
						'separator' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Copyright text'),
						'name' => 'copyright_text',
						'autoload_rte' => true,
						'lang' => true,
						'cols' => 60,
						'rows' => 30
						),
					array(
					'type' => 'file',
					'label' => $this->l('Footer image'),
					'name' => 'footer_img_src',
					'display_image' => true,
					'image' => $footerlogo,
					'delete_url' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteFooterImage=1'
				),
					)
				),
			);

			

			$fields_form_custom = array(
			'form' => array(
				'tab_name' => 'custom_tab',
				'legend' => array(
					'title' => $this->l('Custom CSS and JS code'),
					'icon' => 'icon-edit'
					),

				'input' => array(
					array(
						'type' => 'textarea',
						'label' => $this->l('Custom CSS code'),
						'id' =>'codeEditor',
						'name' => 'custom_css',
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Custom JS code'),
						'name' => 'custom_js',
						'cols' => 60,
						'rows' => 15,
						),
					)
				),
			);




			$fields_form_save = array(
			'form' => array(
				'tab_name' => 'save_tab',
				'legend' => array(
					'title' => $this->l('Save configuration'),
					'icon' => 'icon-save'
					),
				'submit' => array(
					'name' => 'save_editor',
					'class' => 'btn btn-default pull-right',
					'title' => $this->l('Save')
					),
				'buttons' => array(
				'button' => array(
					'name' => 'reset_editor',
					'type' => 'submit',
					'icon' => 'process-icon-refresh',
					'class' => 'btn btn-default pull-left',
					'title' => $this->l('Reset to default')
					),)
				),
			);
		
		if (Shop::isFeatureActive())
			$fields_form['form']['description'] = $this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops'));
		

		$helper = new HelperForm();
		$helper->show_toolbar = true;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;		
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
			);

		
		return $helper->generateForm(array($fields_form_main, $fields_form_global_design, $fields_form_products, $fields_form_fonts, $fields_form_buttons, $fields_form_topbar, $fields_form_headerw, $fields_form_header, $fields_form_menu, $fields_form_submenu, $fields_form_contentw, $fields_form_content, $fields_form_footerw, $fields_form_footer, $fields_form_custom,  $fields_form_save));
	}

	public function getConfigFieldsValues()
	{

		$var =  array();

		foreach ($this->defaults as $default => $value) {

		if($default == 'copyright_text')
		{
			foreach (Language::getLanguages(false) as $lang)
			$var[$default][(int)$lang['id_lang']] =  Configuration::get($this->configName.'_'.$default, (int)$lang['id_lang']);
		}
		else
		$var[$default] = Configuration::get($this->configName.'_'.$default);
		}
			

		$var['PS_QUICK_VIEW'] = (int)Tools::getValue('PS_QUICK_VIEW', Configuration::get('PS_QUICK_VIEW'));

		return $var;

	}


	public function globalFormFields($section)
	{

		$var =  array(
			'bg_color' => array(
						'type' => 'color',
						'label' => $this->l('Background color'),
						'name' => $section.'_bg_color',
						'size' => 30,
					),
			'bg_type' => array(
						'type' => 'select',
						'label' => $this->l('Backgrund image type'),
						'name' => $section.'_bg_type',
							'separator' => true,
						'options' => array(
							'query' => array(
								array(
								'id_option' => 2,
								'name' => $this->l('No image')
								),
								array(
								'id_option' => 1,
								'name' => $this->l('Custom image')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Patterns')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
						),
			'bg_image' => array(
						'type' => 'background_image',
						'label' => $this->l('Custom background image'),
						'name' => $section.'_bg_image',
						'selector' => $section,
						'preffix_wrapper' => $section.'_bg_image_wrapper',
						'wrapper_hidden' => true,
						'size' => 30,
					),
			'bg_repeat' =>  array(
						'type' => 'select',
						'label' => $this->l('Background repeat'),
						'name' => $section.'_bg_repeat',
						'options' => array(
							'query' => array(array(
								'id_option' => 3,
								'name' => $this->l('Repeat XY')
								),
							array(
								'id_option' => 2,
								'name' => $this->l('Repeat X')
								),
							array(
								'id_option' => 1,
								'name' => $this->l('Repeat Y')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('No repeat')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
					),
			'bg_position' =>  array(
						'type' => 'select',
						'label' => $this->l('Background position'),
						'name' => $section.'_bg_position',
						'suffix_wrapper' => true,
							'separator' => true,
						'options' => array(
							'query' => array(
							array(
								'id_option' => 2,
								'name' => $this->l('Left')
								),
							array(
								'id_option' => 1,
								'name' => $this->l('Center')
								),
							array(
								'id_option' => 0,
								'name' => $this->l('Right')
								)
							),                           
    						'id' => 'id_option',                          
    						'name' => 'name'
    						)
					),
			'bg_pattern' => array(
						'type' => 'background_pattern',
						'label' => $this->l('Background pattern'),
						'name' => $section.'_bg_pattern',
						'preffix_wrapper' => $section.'_bg_pattern_wrapper',
						'wrapper_hidden' => true,
						'suffix_wrapper' => true,
							'separator' => true,
						'size' => 30,
					),
			'txt_color' => array(
						'type' => 'color',
						'label' => $this->l('Text color'),
						'name' => $section.'_txt_color',
						'size' => 30,
					),
			'link_color' => array(
						'type' => 'color',
						'label' => $this->l('Link color'),
						'name' => $section.'_link_color',
						'size' => 30,
					),
			'link_h_color' => array(
						'type' => 'color',
						'label' => $this->l('Link hover color'),
						'name' => $section.'_link_h_color',
						'size' => 30,
					),
			'border' =>  array(
						'type' => 'switch',
						'label' => $this->l('Border status'),
						'name' => $section.'_border',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
			'inner_border' =>  array(
						'type' => 'switch',
						'label' => $this->l('Inner border status'),
						'name' => $section.'_inner_border',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
								)
							),
						),
			'border_color' => array(
						'type' => 'color',
						'label' => $this->l('Border color'),
						'name' => $section.'_border_color',
						'size' => 30,
					),
			'inner_border_color' => array(
						'type' => 'color',
						'label' => $this->l('Inner border color'),
						'name' => $section.'_inner_border_color',
						'size' => 30,
					),
			'input_text' => array(
						'type' => 'color',
						'label' => $this->l('Input text'),
						'name' => $section.'_input_text',
						'size' => 30,
					),
			'input_bg' => array(
						'type' => 'color',
						'label' => $this->l('Input background'),
						'name' => $section.'_input_bg',
						'size' => 30,
					),
		);
		return $var;

	}

	public function generateCss() {
		$css = '';

		if(!(Configuration::get($this->configName.'_big_responsive')))
			$css .= '@media (min-width: 1000px) {.container{ max-width: 1010px !important; }} ';

		if((Configuration::get($this->configName.'_content_margin')))
			$css .= 'body{padding: 20px 0;}  ';

		$css .= '.breadcrumb{background: '.Configuration::get($this->configName.'_breadcrumb_bg').'!important; color: '.Configuration::get($this->configName.'_breadcrumb_color').'!important; }  
		.breadcrumb a, .breadcrumb a:link {color: '.Configuration::get($this->configName.'_breadcrumb_color').'!important; }
		';

		if((Configuration::get($this->configName.'_force_boxed')))
			$css .= '
			#page {margin: 0 auto; }

		#page { max-width: 310px; }
		@media (min-width: 480px) {  #page {max-width: 470px; }}
		@media (min-width: 768px) { #page {max-width: 750px; }}
		@media (min-width: 1000px) and (max-width: 1319px) {  #page {max-width: 1010px; }}
		@media (min-width: 1320px) { #page {max-width: 1270px; }}
		';
		if(Configuration::get($this->configName.'_force_boxed') && !Configuration::get($this->configName.'_big_responsive'))
				$css .= '@media (min-width: 1320px) { #page {max-width: 1010px; }}';

		if((Configuration::get($this->configName.'_content_shadow')))
			$css .= '#page{
				-webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
-moz-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
			} ';

		if(!(Configuration::get($this->configName.'_top_pagination')))
			$css .= '.top-pagination-content{display: none !important;}  ';

		if(!(Configuration::get($this->configName.'_show_buttons')))
			$css .= '.flexslider_carousel .button-container, ul.product_list.grid .button-container{display: none !important;}  ';

	
		

	

		//products
		if(Configuration::get($this->configName.'_product_box_h_status'))
			$css .= 'li.ajax_block_product{
				-webkit-transition: color 0.3s ease, background 0.3s ease;
				transition: color 0.3s ease, background 0.3s ease;
			}
			li.ajax_block_product:hover{background-color: '.Configuration::get($this->configName.'_product_box_h_bg').'; }
			li.ajax_block_product:hover, li.ajax_block_product:hover a, li.ajax_block_product:hover a:link 
			{color: '.Configuration::get($this->configName.'_product_box_h_txt').' }

			li.ajax_block_product:hover a.product-name, li.ajax_block_product:hover a.product-name:link
			{color: '.Configuration::get($this->configName.'_product_box_h_txt').' !important; }
			
			li.ajax_block_product:hover a.product-name:hover
			{color: '.Configuration::get($this->configName.'_product_box_h_txt_h').' !important; }

			li.ajax_block_product:hover .price.product-price {color: '.Configuration::get($this->configName.'_product_box_h_price').'!important; }
			';
		if(Configuration::get($this->configName.'_product_hover')==2)
			$css .= '
	 li.ajax_block_product:hover{outline: 1px solid '.Configuration::get($this->configName.'_product_border_color').'!important;}
	ul.product_list.grid > li {margin-top: -9px; padding-top:9px;} 
 .product_list.list li.ajax_block_product:hover .product-container {
    border-color: transparent !important; }
  .product_list.list li.ajax_block_product:hover + li .product-container {
    border-color: transparent !important; }
		';

		if((Configuration::get($this->configName.'_show_on_hover')))
			$css .= '
			.flexslider_carousel .button-container, ul.product_list.grid .button-container,
			ul.product_list.grid  .comments_note,  .flexslider_carousel .comments_note,
			.flexslider_carousel .bottomLine .result_status, ul.product_list.grid .bottomLine .result_status{visibility: hidden;}  

			.flexslider_carousel li.ajax_block_product .hovered .button-container, ul.product_list.grid li.ajax_block_product .hovered .button-container,
			ul.product_list.grid li.ajax_block_product .hovered .comments_note,  .flexslider_carousel li.ajax_block_product .hovered .comments_note,
			.flexslider_carousel li.ajax_block_product .hovered .bottomLine .result_status, ul.product_list.grid li.ajax_block_product .hovered .bottomLine .result_status{visibility: visible;}   
			';
		if(!(Configuration::get($this->configName.'_functional_buttons')))
				$css .= '.flexslider_carousel .functional-buttons, ul.product_list.grid .functional-buttons{display: none !important;}  ';

			$css .= '.flexslider_carousel .functional-buttons, ul.product_list.grid .functional-buttons{ background-color: '.Configuration::get($this->configName.'_functional_buttons_bg').'; } 
			.flexslider_carousel .functional-buttons a, .flexslider_carousel .functional-buttons a:link, ul.product_list.grid .functional-buttons a, ul.product_list.grid .functional-buttons a:link { color: '.Configuration::get($this->configName.'_functional_buttons_txt').' !important; }
			.flexslider_carousel .functional-buttons a:hover, ul.product_list.grid .functional-buttons a:hover{ color: '.Configuration::get($this->configName.'_functional_buttons_txt_h').' !important; }
			';

		if(Configuration::get($this->configName.'_product_grid_border')==2)
			$css .= '
			.flexslider_carousel li.ajax_block_product .product-container, ul.product_list.grid li.ajax_block_product .product-container{
			-webkit-transition: border-color 0.3s ease; -moz-transition: border-color 0.3s ease; -o-transition: border-color 0.3s ease; transition: border-color 0.3s ease;
			padding: 9px; border: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; }  
			.flexslider_carousel li.ajax_block_product:hover .product-container, ul.product_list.grid li.ajax_block_product:hover .product-container{ border-color: transparent !important; }  

			';
			if(Configuration::get($this->configName.'_product_grid_border')==1)
				$css .= '


			.flexslider_carousel li.ajax_block_product .product-container:before {
  content : "";
  position: absolute;
  left    : -5px;
  bottom  : 0;
  height  : 100%;
  width   : 1px; 
  border-left: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; 

}
			.flexslider_carousel li.ajax_block_product:hover{ border-color: transparent !important; }
			ul.product_list.grid li.ajax_block_product {
 }  
								 	ul.product_list.grid li.ajax_block_product:before {
  content : "";
  position: absolute;
  left    : 10px;
  bottom  : 0;
  height  : 1px;
  width   : 100%; 
  border-bottom: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; 

}
ul.product_list.grid li.ajax_block_product:after {
  content : "";
  position: absolute;
  right    : 0px;
  bottom  : 0;
  height  : 100%;
  width   : 1px; 
  border-right: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; 

}
			';

				if(Configuration::get($this->configName.'_product_grid_border')==3)
				$css .= '
			.flexslider_carousel li.ajax_block_product .product-container{
				padding: 9px;
				border-left: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; }  
				.flexslider_carousel li.ajax_block_product:first-child .product-container {
				border-color: transparent !important; 
				} 
			ul.product_list.grid li.ajax_block_product {
				border-right: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important;
				 }  

			';

			if(Configuration::get($this->configName.'_product_grid_border')==5)
				$css .= '
			.flexslider_carousel li.ajax_block_product .product-container{
				padding: 9px;
				border-left: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; }  
				.flexslider_carousel li.ajax_block_product:first-child .product-container {
				border-color: transparent !important; 
				} 
			ul.product_list.grid li.ajax_block_product {
				margin-bottom:20px;
				border-right: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important;
				 }  

			';

					if(Configuration::get($this->configName.'_product_grid_border')==4)
				$css .= '
				 	ul.product_list.grid li.ajax_block_product:before {
  content : "";
  position: absolute;
  left    : 0px;
  bottom  : 0;
  height  : 1px;
  width   : 100%; 
  border-bottom: 1px solid '.Configuration::get($this->configName.'_product_border_color').' !important; 

}
ul.product_list.grid li.ajax_block_product{margin-bottom: 20px;}
.bottom-pagination-content{border-top: none; margin-top: 0px;}

			';

		if(!Configuration::get($this->configName.'_product_grid_center'))
			$css .= '
			.flexslider_carousel .slides li, ul.product_list.grid > li{text-align: left;}
			.is_rtl .flexslider_carousel .slides li, .is_rtl ul.product_list.grid > li{text-align: right;}
			.flexslider_carousel .slides li .star_content, ul.product_list.grid > li .star_content{margin: 0;}
			';

		if(Configuration::get($this->configName.'_product_names'))
			$css .= '.flexslider_carousel .product-name-container, ul.product_list.grid .product-name-container{
				height: '.((Configuration::get($this->configName.'_pname_font_s')+4)*2).'px;}';
		else
			$css .= '.flexslider_carousel .product-name-container, ul.product_list.grid .product-name-container{
				height: '.((Configuration::get($this->configName.'_pname_font_s')+4)).'px;}';

		if(Configuration::get($this->configName.'_product_reference'))
			$css .= '.flexslider_carousel .product-reference, ul.product_list.grid .product-reference{
				height: '.((Configuration::get($this->configName.'_default_font_s')+5)).'px; display: block;}';
		else
			$css .= '.flexslider_carousel .product-reference, ul.product_list.grid .product-reference{display: none !important;}';


		//carousel
			$css .= '
			.flexslider_carousel .direction-nav a{ color: '.Configuration::get($this->configName.'_car_color').' !important; }
			.flexslider_carousel .direction-nav a:hover{ color: '.Configuration::get($this->configName.'_car_color_h').' !important;  }
		';

		if(!Configuration::get($this->configName.'_carousel_style'))
			$css .= '
			.flexslider_carousel .direction-nav a{ background: '.Configuration::get($this->configName.'_car_bg').' !important;  }
			.flexslider_carousel .direction-nav a:hover{ background: '.Configuration::get($this->configName.'_car_bg_h').' !important;  }
		';


		if(!Configuration::get($this->configName.'_show_condition'))
			$css .= '#product_condition{ display: none; }';


		//labels
		$css .= '
		.new-label{color: '.Configuration::get($this->configName.'_label_new_txt').' !important; background: '.Configuration::get($this->configName.'_label_new_bg').' !important;} 
		.sale-label, .price-percent-reduction, #reduction_percent, #reduction_amount{color: '.Configuration::get($this->configName.'_label_sale_txt').' !important; background: '.Configuration::get($this->configName.'_label_sale_bg').' !important;}  
		.online-label{color: '.Configuration::get($this->configName.'_label_online_txt').' !important; background: '.Configuration::get($this->configName.'_label_online_bg').' !important;}   
		#availability_value, .cart_avail .label-success{color: '.Configuration::get($this->configName.'_label_stock_txt').' !important; background: '.Configuration::get($this->configName.'_label_stock_bg').' !important;} 
		ul.product_list .availability span.out-of-stock, .flexslider_carousel .availability span.out-of-stock, #availability_statut #availability_value.warning_inline,
		#last_quantities, ul.product_list .availability .available-dif, .flexslider_carousel .availability .available-dif
		{color: '.Configuration::get($this->configName.'_label_ostock_txt').' !important; background: '.Configuration::get($this->configName.'_label_ostock_bg').' !important;}   
		.price.product-price, .our_price_display, .special-price{color: '.Configuration::get($this->configName.'_price_color').' !important; }
		div.star.star_on:after, div.star.star_hover:after{color: '.Configuration::get($this->configName.'_stars_color').' !important;}  

		.yotpo .yotpo-bottomline .icon-star, .yotpo .yotpo-bottomline .icon-half-star, .yotpo .yotpo-bottomline .icon-empty-star{color: '.Configuration::get($this->configName.'_stars_color').' !important;}  
		';

		if(!(Configuration::get($this->configName.'_label_uppercase')))
			$css .= '.new-label, .sale-label, .online-label, ul.product_list .availability span, .flexslider_carousel .availability span{text-transform: none !important;}  ';

		//buttons
		if(!(Configuration::get($this->configName.'_btn_rounded')))
			$css .= '.btn, .box-info-product .exclusive{-webkit-border-radius: 0px !important; -moz-border-radius: 0px !important; border-radius: 0px !important;}  ';

		if(!(Configuration::get($this->configName.'_btn_uppercase')))
			$css .= '.btn, .shopping_cart > a:first-child span.cart_name, .box-info-product .exclusive{text-transform: none !important;}  ';

		$css .='
		.button.button-small{color: '.Configuration::get($this->configName.'_btn_small_txt').' !important; background: '.Configuration::get($this->configName.'_btn_small_bg').' !important;}
		.button.button-small:hover{color: '.Configuration::get($this->configName.'_btn_small_txt_h').'!important; background: '.Configuration::get($this->configName.'_btn_small_bg_h').'!important;}

		.button.button-medium{color: '.Configuration::get($this->configName.'_btn_medium_txt').' !important; background: '.Configuration::get($this->configName.'_btn_medium_bg').'!important;}
		.button.button-medium:hover{color: '.Configuration::get($this->configName.'_btn_medium_txt_h').'!important; background: '.Configuration::get($this->configName.'_btn_medium_bg_h').' !important;}

		.button.ajax_add_to_cart_button, .button.lnk_view{color: '.Configuration::get($this->configName.'_btn_cart_txt').' !important; background: '.Configuration::get($this->configName.'_btn_cart_bg').' !important;}
		.button.ajax_add_to_cart_button:hover, .button.lnk_view:hover{color: '.Configuration::get($this->configName.'_btn_cart_txt_h').' !important; background: '.Configuration::get($this->configName.'_btn_cart_bg_h').'!important;}
		.box-info-product .exclusive{color: '.Configuration::get($this->configName.'_btn_cartp_txt').'!important; background: '.Configuration::get($this->configName.'_btn_cartp_bg').' !important;}
		.box-info-product .exclusive:hover{color: '.Configuration::get($this->configName.'_btn_cartp_txt_h').' !important; background: '.Configuration::get($this->configName.'_btn_cartp_bg_h').'!important;}
		';




		//font

		$css .= '
		#megamenuiqit .main_menu_link{font-size: '.Configuration::get($this->configName.'_mainmenu_font_s').'px;} 
		.page-heading, .pb-center-column h1{font-size: '.Configuration::get($this->configName.'_heading_font_s').'px; line-height: '.Configuration::get($this->configName.'_heading_font_s').'px; } 
		.page-subheading{font-size: '.Configuration::get($this->configName.'_subheading_font_s').'px; line-height: '.Configuration::get($this->configName.'_subheading_font_s').'px;} 
		.nav-tabs > li > a, .block .title_block, .block h4, h3.page-product-heading{font-size: '.Configuration::get($this->configName.'_boxtitle_font_s').'px; line-height: '.Configuration::get($this->configName.'_boxtitle_font_s').'px;}
		
		.footer-container #footer h4, .footer-container #footer h4 a{font-size: '.Configuration::get($this->configName.'_fboxtitle_font_s').'px; line-height: '.Configuration::get($this->configName.'_fboxtitle_font_s').'px;}
		body, .form-control{font-size: '.Configuration::get($this->configName.'_default_font_s').'px; line-height: '.(Configuration::get($this->configName.'_fboxtitle_font_s')+5).'px;} 
		
		#center-layered-nav .layered_filter_center .layered_subtitle_heading, #center-layered-nav .layeredSortBy{font-size: '.Configuration::get($this->configName.'_default_font_s').'px;} 
		.pb-center-column .product-title{ font-size: '.(Configuration::get($this->configName.'_default_font_s')-2).'px;}
		.product-name{ font-size: '.(Configuration::get($this->configName.'_pname_font_s')).'px; line-height:'.(Configuration::get($this->configName.'_pname_font_s')+4).'px; }
		.price.product-price{ font-size: '.(Configuration::get($this->configName.'_price_font_s')).'px;}
		.online-label, .new-label, .sale-label, ul.product_list .availability span, .flexslider_carousel .availability span{ font-size: '.(Configuration::get($this->configName.'_labels_font_s')).'px;}
		.breadcrumb{ font-size: '.(Configuration::get($this->configName.'_breadcrumb_font_s')).'px;}
		.btn{ font-size: '.(Configuration::get($this->configName.'_buttons_font_s')).'px;  line-height: '.(Configuration::get($this->configName.'_buttons_font_s')+3).'px;}
		';




		if(!(Configuration::get($this->configName.'_headings_uppercase')))
			$css .= 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .nav-tabs > li > a, .mmtitle, .block .title_block, .block h4 {text-transform: none !important;}  ';

		if((Configuration::get($this->configName.'_headings_bold')))
			$css .= 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .nav-tabs > li > a, .mmtitle, .block .title_block, .block h4{font-weight: bold;}  ';

		if((Configuration::get($this->configName.'_headings_italics')))
			$css .= 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .nav-tabs > li > a, .mmtitle, .block .title_block, .block h4{font-style:italic}  ';

		if((Configuration::get($this->configName.'_headings_center')))
			$css .= '#index .block .title_block, #index .block h4, .page-heading, h3.page-product-heading, #editorial_block_center h1, #editorial_block_center h2{text-align: center;}  
			.nav-tabs > li{float: none; display: inline-block; }
			.page-heading span.heading-counter{display: block; float: none; margin-top: 4px; font-size:'.(Configuration::get($this->configName.'_default_font_s')-1).'px;}
			.product-title {text-align: center; margin-bottom: 15px; font-size:'.(Configuration::get($this->configName.'_default_font_s')-1).'px;}
			.product-title  p {display: inline;}
			.product-title h1{border: none !important; margin: 0px; padding-bottom: 4px;}
			.imglog{position: absolute; right: 20px; top: 50%; margin-top: -15px;}
			#order-opc .page-heading.step-num span{position: static; line-height: 0px;}
			.pb-center-column{margin-top: -15px;}
			.product-title .productlistRating {text-align: center;}
			.product-title .productlistRating .star {display:inline-block!important; float: none !important; margin: 0px -1px; top: 1px;}
			.product-title .productlistRating .nb-comments{margin-left: 2px;}
			.nav-tabs{text-align: center;}';
		
		
		
		if(!(Configuration::get($this->configName.'_menu_home_icon')))
			$css .= '.megamenu_home{display: none !important;}   ';

		if((Configuration::get($this->configName.'_menu_bold')))
			$css .= '#megamenuiqit .main_menu_link{font-weight: bold !important;}  ';
		else
			$css .= '#megamenuiqit .main_menu_link{font-weight: normal !important;}  ';

		if((Configuration::get($this->configName.'_menu_italics')))
			$css .= '#megamenuiqit .main_menu_link{font-style:italic !important;}  ';

		if((Configuration::get($this->configName.'_menu_uppercase')))
			$css .= '#megamenuiqit .main_menu_link{text-transform: uppercase !important;}  ';
		else
			$css .= '#megamenuiqit .main_menu_link{text-transform: none !important;}  ';

		$font_headings_type = Configuration::get($this->configName.'_font_headings_type');
		$font_headings_name = Configuration::get($this->configName.'_font_headings_name');
		$font_headings_default = Configuration::get($this->configName.'_font_headings_default');

		$font_txt_type = Configuration::get($this->configName.'_font_txt_type');
		$font_txt_name = Configuration::get($this->configName.'_font_txt_name');
		$font_txt_default = Configuration::get($this->configName.'_font_txt_default');


		if($font_txt_type==2)
		{	
			if($font_headings_type==1)
			$css .= 'body{font-family: '.$font_headings_name.' !important;}  ';
			if($font_headings_type==0)
			$css .= 'body{font-family: '.$this->systemFonts[$font_headings_default]['name'].', Arial, Helvetica, sans-serif !important;}  ';
		}
		else
		{	
			if($font_txt_type==1)
			$css .= 'body, .page-heading span.heading-counter, #center-layered-nav .layered_subtitle_heading{font-family: '.$font_txt_name.' !important;}  ';
			if($font_txt_type==0)
			$css .= 'body, .page-heading span.heading-counter, #center-layered-nav .layered_subtitle_heading{font-family: '.$this->systemFonts[$font_txt_default]['name'].', Arial, Helvetica, sans-serif !important;}  ';

			if($font_headings_type==1)
			$css .= 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .block .title_block, .nav-tabs > li > a, #megamenuiqit .mmtitle, #megamenuiqit .main_menu_link, #textbannersmodule .txttitle{font-family: '.$font_headings_name.' !important;}  ';
			if($font_headings_type==0)
			$css .= 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .block .title_block, .nav-tabs > li > a, #megamenuiqit .mmtitle, #megamenuiqit .main_menu_link, #textbannersmodule .txttitle{font-family: '.$this->systemFonts[$font_headings_default]['name'].', Arial, Helvetica, sans-serif !important;}  ';
		}

		//Main background

		$global_bg_color = Configuration::get($this->configName.'_global_bg_color');
		$global_bg_type = Configuration::get($this->configName.'_global_bg_type');

		$css .= 'body{background-color: '.$global_bg_color.' !important;} ';

		if($global_bg_type==1)
		{
			$global_bg_image = Configuration::get($this->configName.'_global_bg_image');
			$global_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_global_bg_repeat'));
			$global_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_global_bg_position'));
			$css .= 'body{
				background-image: url(' . $global_bg_image . ') !important;
				background-repeat: '.$global_bg_repeat.' !important;
				background-position: '.$global_bg_position.' !important;
			} ';

		}
		elseif($global_bg_type==0)
		{
			$global_bg_pattern = Configuration::get($this->configName.'_global_bg_pattern');
			$css .= 'body{background-image: url(../images/patterns/' . $global_bg_pattern . '.png) !important;} ';
		}
				if (Configuration::get($this->configName.'_global_bg_fixed')) {
				$css .= 'body{background-attachment:fixed;}';
				}

		//Top nav

		$top_bg_color = Configuration::get($this->configName.'_top_bg_color');
		$top_bg_type = Configuration::get($this->configName.'_top_bg_type');

		$css .= 'header .banner{background-color: '.Configuration::get($this->configName.'_top_banner_color').' !important;} ';
		$css .= 'header .nav{background-color: '.$top_bg_color.' !important;} ';

		if($top_bg_type==1)
		{
			$top_bg_image = Configuration::get($this->configName.'_top_bg_image');
			$top_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_top_bg_repeat'));
			$top_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_top_bg_position'));
			$css .= 'header .nav{
				background-image: url(' . $top_bg_image . ') !important;
				background-repeat: '.$top_bg_repeat.' !important;
				background-position: '.$top_bg_position.' !important;
			} ';

		}
		elseif($top_bg_type==0)
		{
			$top_bg_pattern = Configuration::get($this->configName.'_top_bg_pattern');
			$css .= 'header .nav{background-image: url(../images/patterns/' . $top_bg_pattern . '.png) !important;} ';
		}


		$css .= '
		header .nav{color: '.Configuration::get($this->configName.'_top_txt_color').' !important;}
		header .nav a, header .nav a:link, #slidetopcontentShower, .bt_compare, #languages-block-top div.current, #currencies-block-top div.current{color: '.Configuration::get($this->configName.'_top_link_color').' !important;} 
		header .nav a:hover, #slidetopcontentShower:hover, .bt_compare:hover, #languages-block-top div.current:hover, #currencies-block-top div.current:hover{color: '.Configuration::get($this->configName.'_top_link_h_color').' !important;}  
		';

		if(Configuration::get($this->configName.'_top_border'))
		$css .= 'header .nav{border-bottom: 1px solid '.Configuration::get($this->configName.'_top_border_color').'!important;} ';	

		$css .= '
		#languages-block-top div.current.active div, #languages-block-top div.current.active, #languages-block-top ul, #languages-block-top a, #languages-block-top a:link,
		#currencies-block-top div.current.active div, #currencies-block-top div.current.active, #currencies-block-top ul, #currencies-block-top a, #currencies-block-top a:link
		{color: '.Configuration::get($this->configName.'_top_ddown_color').' !important;  background-color: '.Configuration::get($this->configName.'_top_ddown_bg').' !important;}  ';


		//h_wrap 

		$h_wrap_bg_color = Configuration::get($this->configName.'_h_wrap_bg_color');
		$h_wrap_bg_type = Configuration::get($this->configName.'_h_wrap_bg_type');

		$css .= '.header-container{background-color: '.$h_wrap_bg_color.' !important;} ';

		if($h_wrap_bg_type==1)
		{
			$h_wrap_bg_image = Configuration::get($this->configName.'_h_wrap_bg_image');
			$h_wrap_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_h_wrap_bg_repeat'));
			$h_wrap_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_h_wrap_bg_position'));
			$css .= '.header-container{
				background-image: url(' . $h_wrap_bg_image . ') !important;
				background-repeat: '.$h_wrap_bg_repeat.' !important;
				background-position: '.$h_wrap_bg_position.' !important;
			} ';

		}
		elseif($h_wrap_bg_type==0)
		{
			$h_wrap_bg_pattern = Configuration::get($this->configName.'_h_wrap_bg_pattern');
			$css .= '.header-container{background-image: url(../images/patterns/' . $h_wrap_bg_pattern . '.png) !important;} ';
		}


		//header

		$header_bg_color = Configuration::get($this->configName.'_header_bg_color');
		$header_bg_type = Configuration::get($this->configName.'_header_bg_type');

		$css .= '.container-header{background-color: '.$header_bg_color.' !important;} ';

		if($header_bg_type==1)
		{
			$header_bg_image = Configuration::get($this->configName.'_header_bg_image');
			$header_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_header_bg_repeat'));
			$header_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_header_bg_position'));
			$css .= '.container-header{
				background-image: url(' . $header_bg_image . ') !important;
				background-repeat: '.$header_bg_repeat.' !important;
				background-position: '.$header_bg_position.' !important;
			} ';

		}
		elseif($header_bg_type==0)
		{
			$header_bg_pattern = Configuration::get($this->configName.'_header_bg_pattern');
			$css .= '.container-header{background-image: url(../images/patterns/' . $header_bg_pattern . '.png) !important;} ';
		}



	

		$css .= '.container-header .form-control{color: '.Configuration::get($this->configName.'_header_input_text').' !important; background: '.Configuration::get($this->configName.'_header_input_bg').' !important;
		border-color: '.Configuration::get($this->configName.'_header_inner_border_color').' !important;
		}
		#search_block_top .button-search:before{color: '.Configuration::get($this->configName.'_header_input_text').' !important; }
		 ';

		
		if(!(Configuration::get($this->configName.'_header_inner_border')))
				$css .= '.container-header .form-control{border: none; !important;} ';

		$css .= '.header_user_info, .header_user_info a, .header_user_info a:link {color: '.Configuration::get($this->configName.'_header_link_color').'}
		.header_user_info a:hover {color: '.Configuration::get($this->configName.'_header_link_h_color').'} ';

		//cart
		if(!(Configuration::get($this->configName.'_cart_icon')))
			$css .= '.shopping_cart > a:first-child span.cart_name:before{ display: none; !important;} ';

		if((Configuration::get($this->configName.'_cart_icon')==2))
			$css .= '.shopping_cart > a:first-child span.cart_name:before{ content: "\\e605"; font-family: "warehousefont";} ';

		$css .= '.shopping_cart > a:first-child span.cart_name, .shopping_cart > a:first-child span.cart_name:before{color: '.Configuration::get($this->configName.'_cart_txt').' ; background: '.Configuration::get($this->configName.'_cart_bg').';} ';
		$css .= '.shopping_cart .more_info{color: '.Configuration::get($this->configName.'_cart2_txt').' ; background: '.Configuration::get($this->configName.'_cart2_bg').';} ';

		$css .= '#header .cart_block{color: '.Configuration::get($this->configName.'_cart_box_txt').' ; background: '.Configuration::get($this->configName.'_cart_box_bg').';} 
		#header .cart_block a, #header .cart_block a:link{color: '.Configuration::get($this->configName.'_cart_box_txt').' ;} 
		#header .cart_block a:hover{color: '.Configuration::get($this->configName.'_cart_box_txt_h').' ;} 
		#header .cart_block, #header .cart_block *{border-color: '.Configuration::get($this->configName.'_cart_box_border').'  !important;} 
		';
		$css .= '.cart_block .cart-buttons{background: '.Configuration::get($this->configName.'_cart_box2_bg').';} ';

		if((Configuration::get($this->configName.'_header_padding')))
			$css .= '.container-header{padding-bottom: 20px;}  ';

		if((Configuration::get($this->configName.'_header_increase'))){
			$val = Configuration::get($this->configName.'_header_increase');
			$valm = Configuration::get('megamenuiqit_mborder');
			if(isset($valm) && $valm==2)
				$valm=3;
			else
				$valm = 0;
			$css .= '
			@media (min-width: 768px) {
				header .row #header_logo{height: '.(140+$val).'px;}
				header .row #header_logo img{max-height: '.(100+$val).'px;}
			#search_block_top{top: '.((140+$val)/2-13).'px;}
			#header .shopping_cart{padding-top: '.((140+$val)/2-28).'px;}
			#header .cart_block{top: '.((140+$val)/2).'px;}
				.on_menu #search_block_top{top: '.(144+$val+$valm).'px !important;}
			}
			';
		}


		//menu
		$menu_bg_color = Configuration::get($this->configName.'_menu_bg_color');
		$menu_bg_type = Configuration::get($this->configName.'_menu_bg_type');

		$css .= '.megamenuiqit{background-color: '.$menu_bg_color.' !important;} ';

		if($menu_bg_type==1)
		{
			$menu_bg_image = Configuration::get($this->configName.'_menu_bg_image');
			$menu_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_menu_bg_repeat'));
			$menu_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_menu_bg_position'));
			$css .= '.megamenuiqit{
				background-image: url(' . $menu_bg_image . ') !important;
				background-repeat: '.$menu_bg_repeat.' !important;
				background-position: '.$menu_bg_position.' !important;
			} ';

		}
		elseif($menu_bg_type==0)
		{
			$menu_bg_pattern = Configuration::get($this->configName.'_menu_bg_pattern');
			$css .= '.megamenuiqit{background-image: url(../images/patterns/' . $menu_bg_pattern . '.png) !important;} ';
		}



		$css .= '#megamenuiqit .menu_label_tag{background-color: '.Configuration::get($this->configName.'_menu_label_bg').' !important; color: '.Configuration::get($this->configName.'_menu_label_color').' !important; } 
		#megamenuiqit .menu_label_tag:after{color: '.Configuration::get($this->configName.'_menu_label_bg').' !important;}
		';

		$css .= '#megamenuiqit .main_menu_link{color: '.Configuration::get($this->configName.'_menu_link_color').' }';
		$css .= '#megamenuiqit .main_menu_link:hover, #megamenuiqit #megamenuiqit .linkHover{background-color: '.Configuration::get($this->configName.'_menu_link_bg').' !important; color: '.Configuration::get($this->configName.'_menu_link_h_color').' !important; }';

		$css .= '.megamenuiqit{border-color: '.Configuration::get($this->configName.'_menu_border_color').' !important;}';
		$css .= '#megamenuiqit > li:before{color: '.Configuration::get($this->configName.'_menu_inner_border_color').';}';

		if(!Configuration::get($this->configName.'_menu_inner_border'))
			$css .= '#megamenuiqit > li:before{display: none;} ';
		
		
		//submenu

		$submenu_bg_color = Configuration::get($this->configName.'_submenu_bg_color');
		
		$css .= '#megamenuiqit .submenu{background-color: '.$submenu_bg_color.' !important;} ';
		$css .= '#megamenuiqit .submenu_triangle{border-bottom-color: '.$submenu_bg_color.' !important; } ';

		$css .= '#megamenuiqit .submenu{border-color: '.Configuration::get($this->configName.'_submenu_border_color').' !important; } ';
		$css .= '#megamenuiqit .submenu_triangle2{border-bottom-color: '.Configuration::get($this->configName.'_submenu_border_color').' !important; } ';
		$css .= '#megamenuiqit .submenu a, #megamenuiqit .submenu a:link, #megamenuiqit .left_column_subcats li a:before{color: '.Configuration::get($this->configName.'_submenu_link_color').' !important; } ';
		$css .= '#megamenuiqit .submenu a:hover{color: '.Configuration::get($this->configName.'_submenu_link_h_color').' !important; } ';
		

		$c_wrap_bg_color = Configuration::get($this->configName.'_c_wrap_bg_color');
		$c_wrap_bg_type = Configuration::get($this->configName.'_c_wrap_bg_type');

		$css .= '.columns-container{background-color: '.$c_wrap_bg_color.' !important;} ';

		if($c_wrap_bg_type==1)
		{
			$c_wrap_bg_image = Configuration::get($this->configName.'_c_wrap_bg_image');
			$c_wrap_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_c_wrap_bg_repeat'));
			$c_wrap_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_c_wrap_bg_position'));
			$css .= '.columns-container{
				background-image: url(' . $c_wrap_bg_image . ') !important;
				background-repeat: '.$c_wrap_bg_repeat.' !important;
				background-position: '.$c_wrap_bg_position.' !important;
			} ';

		}
		elseif($c_wrap_bg_type==0)
		{
			$c_wrap_bg_pattern = Configuration::get($this->configName.'_c_wrap_bg_pattern');
			$css .= '.columns-container{background-image: url(../images/patterns/' . $c_wrap_bg_pattern . '.png) !important;} ';
		}

		//content


		$content_bg_color = Configuration::get($this->configName.'_content_bg_color');
		$content_bg_type = Configuration::get($this->configName.'_content_bg_type');

		$css .= '#columns, body.content_only{background-color: '.$content_bg_color.' !important;} body.content_only{background-image: none !important;} ';

		if($content_bg_type==1)
		{
			$content_bg_image = Configuration::get($this->configName.'_content_bg_image');
			$content_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_content_bg_repeat'));
			$content_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_content_bg_position'));
			$css .= '#columns, body.content_only{
				background-image: url(' . $content_bg_image . ') !important;
				background-repeat: '.$content_bg_repeat.' !important;
				background-position: '.$content_bg_position.' !important;
			} ';

		}
		elseif($content_bg_type==0)
		{
			$content_bg_pattern = Configuration::get($this->configName.'_content_bg_pattern');
			$css .= '#columns, body.content_only{background-image: url(../images/patterns/' . $content_bg_pattern . '.png) !important;} ';
		}


			$css .= '
			#columns .content-inner *,  #columns .content-inner .form-control, #product_comments_block_tab div.comment .comment_details, #product_comments_block_tab div.comment{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').';}  
			#quantity_wanted_p input{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').' !important;}  
			#columns .content-inner .nav-tabs > li > a{border-color: transparent;}
			#columns .content-inner #thumbs_list li a{border-color: '.$content_bg_color.';}  
			#columns .content-inner #thumbs_list li a:hover, #columns .content-inner #thumbs_list li a.shown{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').';} 
			#columns .content-inner .nav-tabs > li.active > a, #columns .content-inner .nav-tabs > li.active > a:hover, #columns .content-inner .nav-tabs > li.active > a:focus, #columns .content-inner .nav-tabs > li > a:hover{ border-color: '.Configuration::get($this->configName.'_content_inner_border_color').'; border-bottom-color: transparent;} ';
			$css .= '
			body.content_only *,  body.content_only .form-control, #product_comments_block_tab div.comment .comment_details, #product_comments_block_tab div.comment{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').';}  
			#quantity_wanted_p input{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').' !important;}  
			body.content_only .nav-tabs > li > a{border-color: transparent;}
			body.content_only #thumbs_list li a{border-color: '.$content_bg_color.';}  
			body.content_only #thumbs_list li a:hover, body.content_only #thumbs_list li a.shown{border-color: '.Configuration::get($this->configName.'_content_inner_border_color').';} 
			body.content_only .nav-tabs > li.active > a, body.content_only .nav-tabs > li.active > a:hover, body.content_only .nav-tabs > li.active > a:focus
			, body.content_only .nav-tabs > li > a:hover{ border-color: '.Configuration::get($this->configName.'_content_inner_border_color').'; border-bottom-color: transparent;} ';
			
			$css .= '.form-control, #quantity_wanted_p input, .form-control.grey{color: '.Configuration::get($this->configName.'_content_input_text').'; background-color: '.Configuration::get($this->configName.'_content_input_bg').';}  ';
			$css .= '#columns .content-inner, body.content_only{color: '.Configuration::get($this->configName.'_content_txt_color').' !important; }  

			#columns .content-inner a, #columns .content-inner a:link, body.content_only a, body.content_only a:link{color: '.Configuration::get($this->configName.'_content_link_color').'  }  
			#columns .content-inner a:hover, body.content_only a:hover{color: '.Configuration::get($this->configName.'_content_link_h_color').' }  

			#columns .content-inner .block .title_block, #columns .content-inner .block h4,
			#columns .content-inner .block .title_block a, #columns .content-inner .block h4 a,
			#columns .content-inner .nav-tabs > li > a, .pb-center-column h1, .page-heading, h3.page-product-heading
			{color: '.Configuration::get($this->configName.'_content_headings_color').' !important; }  
			
			body.content_only .block .title_block, body.content_only .block h4,
			body.content_only .block .title_block a, body.content_only .block h4 a,
			body.content_only .nav-tabs > li > a, .pb-center-column h1, .page-heading, h3.page-product-heading
			{color: '.Configuration::get($this->configName.'_content_headings_color').' !important; }  
			';

			if(!Configuration::get($this->configName.'_content_input_select'))
				$css .= 'div.selector, div.selector span, div.checker span, div.radio span, div.uploader, div.uploader span.action, div.button, div.button span
			{
				background-image: url(../images/black_uniform.png);
				color: #6b6b6b !important;
				text-shadow: none !important;
			}
			.form-control option{background:  #1F1F1F !important; color: #6b6b6b !important}
						';

		$css .= '.box, #facebook_block, #cmsinfo_block, .table tfoot tr, .ph_simpleblog .simpleblog-posts .post-content, ul.step li.step_done{background:'.Configuration::get($this->configName.'_content_box_bg').' ; color:'.Configuration::get($this->configName.'_content_box_color').' ;}   ';

		$css .= '.table > thead > tr > th{background:'.Configuration::get($this->configName.'_table_bg').' ; color:'.Configuration::get($this->configName.'_table_color').' ;}   ';
		
			$css .= '
		#center-layered-nav .layered_filter_center .active.layered_subtitle_heading,
		#center-layered-nav .layered_filter_center .active.layered_subtitle_heading div,
		#center-layered-nav .layered_filter_center > ul, #center-layered-nav a, #center-layered-nav:link{color: '.Configuration::get($this->configName.'_ddown_color').' !important; background-color: '.Configuration::get($this->configName.'_ddown_bg').' !important;}  ';
		


		if(Configuration::get($this->configName.'_content_bgh_status'))
			$css .= '
			#columns .content-inner .block .title_block, 
			#columns .content-inner .block h4,
			.pb-center-column .product-title h1,
			.page-heading, .product-title-center,
			h3.page-product-heading{ background:'.Configuration::get($this->configName.'_content_bgh').' ;  padding: 12px; }
			#order-opc .page-heading.step-num{ background:'.Configuration::get($this->configName.'_content_bgh').' ; }
			#columns .content-inner .nav-tabs > li > a{color:'.Configuration::get($this->configName.'_content_txt_color').' !important ;  }
			#columns #center-layered-nav .layered_subtitle_heading {padding: 0px !important; background: none !important; color:'.Configuration::get($this->configName.'_content_txt_color').' !important ; }
			#columns #center-layered-nav .layeredSortBy {padding: 0px !important; padding-right: 10px !important; background: none !important; color:'.Configuration::get($this->configName.'_content_txt_color').' !important ; }
				#columns .content-inner .nav-tabs > li.active > a{ background:'.Configuration::get($this->configName.'_content_bgh').'; color:'.Configuration::get($this->configName.'_content_headings_color').' !important ;  }
			';

		
		if(Configuration::get($this->configName.'_content_bgh_status') && !Configuration::get($this->configName.'_carousel_style'))
			$css .= '.flexslider_carousel .next{right: 12px; }  .flexslider_carousel .prev{right: 42px; }  .flexslider_carousel .direction-nav a{top: -31px;}';
		
		if(Configuration::get($this->configName.'_content_block_border'))
			$css .= '#left_column .block, #right_column .block{ border: 1px solid '.Configuration::get($this->configName.'_content_block_border_c').' !important;}
			#left_column .block_content, #right_column .block_content{padding: 0px 12px;}
		#left_column .block .title_block, #right_column .block .title_block{ padding: 12px; border-bottom: 1px solid '.Configuration::get($this->configName.'_content_block_border_c').' !important;}
		#columnadverts{margin-bottom: 20px;}
		#left_column #standardtweets_module, #right_column #standardtweets_module{border:none !important;}
		#left_column .layeredSortBy, #right_column .layeredSortBy{display: block; margin-bottom: 10px;}
		#left_column #layered_form > div, #right_column #layered_form > div{padding: 0px 12px;}
		#left_column #layered_form .title_block, #right_column #layered_form .title_block{padding: 6px 5px 10px 0px; background: none;}
		.blockmanufacturer{padding-bottom: 12px;}
			';


		//f_wrap 

		$f_wrap_bg_color = Configuration::get($this->configName.'_f_wrap_bg_color');
		$f_wrap_bg_type = Configuration::get($this->configName.'_f_wrap_bg_type');

		$css .= '.footer-container{background-color: '.$f_wrap_bg_color.' !important;} ';

		if($f_wrap_bg_type==1)
		{
			$f_wrap_bg_image = Configuration::get($this->configName.'_f_wrap_bg_image');
			$f_wrap_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_f_wrap_bg_repeat'));
			$f_wrap_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_f_wrap_bg_position'));
			$css .= '.footer-container{
				background-image: url(' . $f_wrap_bg_image . ') !important;
				background-repeat: '.$f_wrap_bg_repeat.' !important;
				background-position: '.$f_wrap_bg_position.' !important;
			} ';

		}
		elseif($f_wrap_bg_type==0)
		{
			$f_wrap_bg_pattern = Configuration::get($this->configName.'_f_wrap_bg_pattern');
			$css .= '.footer-container{background-image: url(../images/patterns/' . $f_wrap_bg_pattern . '.png) !important;} ';
		}

		if(Configuration::get($this->configName.'_f_wrap_padding'))
			$css .= '.footer-container{padding-top: 20px; padding-bottom: 20px;} ';
		if(Configuration::get($this->configName.'_second_footer'))
			$css .= '.footer-container{padding-bottom: 0px;} ';

		

		//footer
		$footer_bg_color = Configuration::get($this->configName.'_footer_bg_color');
		$footer_bg_type = Configuration::get($this->configName.'_footer_bg_type');

		$css .= '.footer-container .footer-container-inner{background-color: '.$footer_bg_color.' !important;} ';
		$css .= '.footer-container .footer-container-inner{border-bottom-color: '.$footer_bg_color.' !important; } ';

		if($footer_bg_type==1)
		{
			$footer_bg_image = Configuration::get($this->configName.'_footer_bg_image');
			$footer_bg_repeat = $this->convertBgRepeat(Configuration::get($this->configName.'_footer_bg_repeat'));
			$footer_bg_position = $this->convertBgPosition(Configuration::get($this->configName.'_footer_bg_position'));
			$css .= '.footer-container .footer-container-inner{
				background-image: url(' . $footer_bg_image . ') !important;
				background-repeat: '.$footer_bg_repeat.' !important;
				background-position: '.$footer_bg_position.' !important;
			} ';

		}
		elseif($footer_bg_type==0)
		{
			$footer_bg_pattern = Configuration::get($this->configName.'_footer_bg_pattern');
			$css .= '.footer-container .footer-container-inner{background-image: url(../images/patterns/' . $footer_bg_pattern . '.png) !important;} ';
		}

		if(!Configuration::get($this->configName.'_footer_social_round'))
			$css .= '#social_block_mod li a{
				-webkit-border-radius: 0px !important; 
				-moz-border-radius: 0px !important;
				-ms-border-radius: 0px !important;
				-o-border-radius: 0px !important;
				border-radius: 0px !important; 
			} ';

		$css .= '#social_block_mod li a{background-color: '.Configuration::get($this->configName.'_footer_social_bg').' !important; }
		#social_block_mod li a:before{ color: '.Configuration::get($this->configName.'_footer_social_color').' !important;}
		 ';

		

		if(!Configuration::get($this->configName.'_footer_border'))
			$css .= '.footer-container .footer-container-inner{border: none !important;} ';

		if(!Configuration::get($this->configName.'_footer_inner_border'))
			$css .= '.footer-container .footer-container-inner *{border: none !important;} .footer-container #footer h4{margin-bottom: 0px !important;} ';

		 if(Configuration::get($this->configName.'_footer_bgh_status'))
			$css .= '
			.footer-container #footer h4{ background:'.Configuration::get($this->configName.'_footer_bgh').' ;  padding: 10px; margin-bottom: 10px !important; }';

			$css .= '.footer-container .footer-container-inner{border-color: '.Configuration::get($this->configName.'_footer_border_color').'!important;} ';
			$css .= '.footer-container .footer-container-inner *{border-color: '.Configuration::get($this->configName.'_footer_inner_border_color').' !important;}  ';
			$css .= '#footer .form-control{color: '.Configuration::get($this->configName.'_footer_input_text').' ; background-color: '.Configuration::get($this->configName.'_footer_input_bg').' ;} 
			#footer #newsletter_block_left .form-group .button-small{color: '.Configuration::get($this->configName.'_footer_input_text').' !important;}

			 ';
			$css .= '.footer-container #footer, .footer-container #footer #block_contact_infos > div ul li i{color: '.Configuration::get($this->configName.'_footer_txt_color').' !important; }  

			.footer-container #footer a, .footer-container #footer a:link, .footer-container #footer .bullet li a:before{color: '.Configuration::get($this->configName.'_footer_link_color').' !important; }  
			.footer-container #footer a:hover{color: '.Configuration::get($this->configName.'_footer_link_h_color').' !important; }  

			.footer-container #footer h4, .footer-container #footer h4 a, .footer-container #footer h4 a:link, .footer-container #footer h4 a:hover{color: '.Configuration::get($this->configName.'_footer_headings_color').' !important; }  
			';
	if(Configuration::get($this->configName.'_footer_width') && !Configuration::get($this->configName.'_f_wrap_width'))
		{ $css .= '.footer-container {padding-left: 0px; padding-right: 0px;} ';}

		



		$css .= Configuration::get($this->configName.'_custom_css');
	
		
		if (Shop::getContext() == Shop::CONTEXT_GROUP)
			$myFile = $this->local_path . "css/themeeditor_g_".(int)$this->context->shop->getContextShopGroupID().".css";
		elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
			$myFile = $this->local_path . "css/themeeditor_s_".(int)$this->context->shop->getContextShopID().".css";
		
		
		
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $css);
		fclose($fh);



		$js = Configuration::get($this->configName.'_custom_js');

		if (Shop::getContext() == Shop::CONTEXT_GROUP)
			$myFile = $this->local_path . "js/front/themeeditor_g_".(int)$this->context->shop->getContextShopGroupID().".js";
		elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
			$myFile = $this->local_path . "js/front/themeeditor_s_".(int)$this->context->shop->getContextShopID().".js";
		
		
		
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $js);
		fclose($fh);

	}
	
	public function convertBgRepeat($value) {

			switch($value) {
				case 3 :
					$repeat_option = 'repeat';
					break;
				case 2 :
					$repeat_option = 'repeat-x';
					break;
				case 1 :
					$repeat_option = 'repeat-y';
					break;
				default :
					$repeat_option = 'no-repeat';
			}
			return  $repeat_option;
	}


	public function convertBgPosition($value) {

			switch($value) {
				case 2 :
					$position_option = 'left top';
					break;
				case 1 :
					$position_option = 'center top';
					break;
				default :
					$position_option = 'right top';
			}
			return  $position_option;
	}

	public function hookdisplayAdminHomeQuickLinks() {
		echo '<li id="themeeditor_block">
		<a  style="background:#F8F8F8 url(../modules/themeeditor/qllogo.png) no-repeat 50% 20px" href="index.php?controller=adminmodules&configure=themeeditor&token='.Tools::getAdminTokenLite('AdminModules').'">
		<h4>Theme Editor Module</h4>
		<p>Customize your theme</p>
		</a>
		</li>';
	}

	public function hookCalculateGrid($params) {

		$inc = 0;
		if ((Context::getContext()->theme->hasLeftColumn($this->context->controller->php_self)))
			$inc++;
		if ((Context::getContext()->theme->hasRightColumn($this->context->controller->php_self)))
			$inc++;

		switch ($params['size']) {
			case 'large':
			$grid = Configuration::get($this->configName.'_grid_size_lg') - $inc;
			break;
			case 'medium':
			$grid = Configuration::get($this->configName.'_grid_size_md') - $inc;
			break; 
			case 'small':
			$grid = Configuration::get($this->configName.'_grid_size_sm');
			break; 
			case 'mediumsmall':
			$grid = Configuration::get($this->configName.'_grid_size_ms');
			break;  
		}

		if($grid==5)
			$grid=15;
		else
			$grid=(12/$grid);

		return $grid;
	}

	public function hookHeader($params) {

		if (isset($_SERVER['HTTP_USER_AGENT']) && 
			(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
			header('X-UA-Compatible: IE=edge,chrome=1');
		
		

			$font_headings_type = Configuration::get($this->configName.'_font_headings_type');
			$font_headings_link = str_replace(array('http://','https://'), '', Configuration::get($this->configName.'_font_headings_link'));

			$font_txt_type = Configuration::get($this->configName.'_font_txt_type');
			$font_txt_link = str_replace(array('http://','https://'), '', Configuration::get($this->configName.'_font_txt_link')); 

			$fonts = array();
			if($font_txt_type==2)
			{	
				if($font_headings_type==1)
				$fonts[0] = $font_headings_link;
			}
			else
			{
				if($font_txt_type==1)
				$fonts[1] = $font_txt_link;
				if($font_headings_type==1)
				$fonts[0] = $font_headings_link;
			}
				
	
			
			

			$id_shop = (int)$this->context->shop->id;
			$preloader = Configuration::get($this->configName.'_preloader');		
			$theme_settings = array(
				"ajax_popup" => (Configuration::get($this->configName.'_ajax_popup')),
				"yotpo_stars" => (Configuration::get($this->configName.'_yotpo_stars')),
				"preloader" => $preloader,
				'is_rtl' => $this->context->language->is_rtl,
				'top_width' => (Configuration::get($this->configName.'_top_width')),
				'footer_width' => (Configuration::get($this->configName.'_footer_width')),
				'f_wrap_width' => (Configuration::get($this->configName.'_f_wrap_width')),
				'left_on_phones' => (Configuration::get($this->configName.'_left_on_phones')),
				'breadcrumb_width' => (Configuration::get($this->configName.'_breadcrumb_width')),
				'product_right_block' => (Configuration::get($this->configName.'_product_right_block')),
				'productlist_view' => (Configuration::get($this->configName.'_productlist_view')),
				'show_desc' => (Configuration::get($this->configName.'_show_desc')),
				'desc_style' => (Configuration::get($this->configName.'_desc_style')),
				'show_subcategories' => (Configuration::get($this->configName.'_show_subcategories')),
				'footer_img_src' => file_exists('modules/themeeditor/img/footer_logo_'.(int)$id_shop.'.jpg'),
				'image_path' => $this->_path.'img/footer_logo_'.(int)$id_shop.'.jpg',
				'font_include' => $fonts, 
				'grid_size_lg' => Configuration::get($this->configName.'_grid_size_lg'),
				'grid_size_md' => Configuration::get($this->configName.'_grid_size_md'),
				'grid_size_sm' =>  Configuration::get($this->configName.'_grid_size_sm'),
				'grid_size_ms' => Configuration::get($this->configName.'_grid_size_ms'),
				'product_left_size' => Configuration::get($this->configName.'_product_left_size'),
				'product_center_size' => Configuration::get($this->configName.'_product_center_size'),
				'headings_center' => Configuration::get($this->configName.'_headings_center'),
				'logo_width' => Configuration::get($this->configName.'_logo_width'),
				'logo_position' => Configuration::get($this->configName.'_logo_position'),
				'search_position' => Configuration::get($this->configName.'_search_position'),
				'second_footer' => (Configuration::get($this->configName.'_second_footer')),
				'top_bar' => (Configuration::get($this->configName.'_top_bar')),
				'copyright_text' => Configuration::get($this->configName.'_copyright_text', $this->context->language->id),
				);

						
				if(Configuration::get($this->configName.'_product_hover')==1)
					$this->context->controller->addCSS(($this->_path).'css/options/hover.css', 'all');

				if(!Configuration::get($this->configName.'_carousel_style'))
					$this->context->controller->addCSS(($this->_path).'css/options/carousel_style.css', 'all');			

				if(!Configuration::get($this->configName.'_big_responsive'))
					$this->context->controller->addCSS(($this->_path).'css/options/remove_lg.css', 'all');			
	
				if($preloader)
				{
					$this->context->controller->addJS(($this->_path).'js/front/preloader.js');	
					$this->context->controller->addCSS(($this->_path).'css/options/preloader.css', 'all');
				}


				$this->context->smarty->assign('warehouse_vars', $theme_settings);

				if (Shop::getContext() == Shop::CONTEXT_GROUP)
				$this->context->controller->addCSS(($this->_path) . 'css/themeeditor_g_'.(int)$this->context->shop->getContextShopGroupID().'.css', 'all');
				elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
				$this->context->controller->addCSS(($this->_path) . 'css/themeeditor_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
				$this->context->controller->addCSS(($this->_path) . 'css/yourcss.css', 'all');


				if (Shop::getContext() == Shop::CONTEXT_GROUP)
				$this->context->controller->addJS(($this->_path) . 'js/front/themeeditor_g_'.(int)$this->context->shop->getContextShopGroupID().'.js');
				elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
				$this->context->controller->addJS(($this->_path) . 'js/front/themeeditor_s_'.(int)$this->context->shop->getContextShopID().'.js');

	}

}
