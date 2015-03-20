<?php
/**
 * NOTICE OF LICENSE
 *
 * @package   sociallogin Add Social login in your Pretashop module
 * @author    LoginRadius Team
 * @copyright Copyright 2014 www.loginradius.com - All rights reserved.
 * @license   GNU GENERAL PUBLIC LICENSE Version 2, June 1991

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

if (!defined('_PS_VERSION_'))
	exit;

class SocialLogin extends Module
{
	/**
	 * Constructor
	 **/
	public function __construct()
	{
		$this->name = 'sociallogin';
		$this->tab = 'others';
		$this->version = '2.9';
		$this->author = 'LoginRadius';
		$this->need_instance = 0;
		$this->module_key = '3afa66f922e9df102449d92b308b4532'; //Product Key //Don't change.
		parent::__construct();
		$this->displayName = $this->l('Social Login');
		$this->description = $this->l('Let your users log in and comment via their accounts with popular ID providers such as Facebook,
		Google, Twitter, Yahoo, Vkontakte and over 25 more!.');
	}

	/*
	*  Left column hook that show social login interface left side.
	*/
	public function hookLeftColumn($params, $str = '')
	{
		$context = Context::getContext();
		$cookie = $context->cookie;
		$smarty = $context->smarty;
		if (Context::getContext()->customer->isLogged())
			return;
		$loginradius_api_key = trim(Configuration::get('API_KEY'));
		$loginradius_api_secret = trim(Configuration::get('API_SECRET'));
		if (Configuration::get('enable_social_login') == '0')
		{
			if (!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $loginradius_api_key)
				|| !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $loginradius_api_secret))
			{
				$iframe = 'error';
				if ($str == 'right' || $str == '')
					$right = true;
				else
					$right = false;
				$smarty->assign('right', $right);
				$smarty->assign('iframe', $iframe);
				$smarty->assign('margin_style', '');
				return $this->display(__FILE__, 'loginradius.tpl');
			}
			elseif (!empty($loginradius_api_key) && !empty($loginradius_api_secret))
			{
				$cookie->lr_login = false;
				$margin_style = '';
				if ($str == 'margin')
					$margin_style = 'style="margin-left:8px;margin-top:5px;"';
				$title = Configuration::get('TITLE');
				$smarty->assign('sl_title', $title);
				$iframe = 'interfacecontainerdiv';
				if ($str == 'right' || $str == '')
					$right = true;
				else
				{
					$right = false;
					$iframe = 'interfacecontainerdiv';
				}
				$smarty->assign('right', $right);
				$smarty->assign('margin_style', $margin_style);
				$smarty->assign('iframe', $iframe);
				return $this->display(__FILE__, 'loginradius.tpl');
			}
		}
	}

	/*
	*  Right column hook that show social login interface right side.
	*/
	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params, 'right');
	}

	/*
	*  Account top hook that show social login interface at create an account (register ) .
	*/
	public function hookCreateAccountTop($params)
	{
		return $this->hookLeftColumn($params, 'margin');
	}

	/*
	*  Header hook that add script [Social share script, Social counter script, Social Interface script] at head .
	*/
	public function hookHeader($params)
	{
		include_once(dirname(__FILE__).'/sociallogin_functions.php');
		$script = '';
		if (Configuration::get('enable_social_login') == '0')
		{
			$loginradius_api_key = trim(Configuration::get('API_KEY'));
			$loginradius_api_secret = trim(Configuration::get('API_SECRET'));
			if (!empty($loginradius_api_key) && !empty($loginradius_api_secret))
				$script .= loginRadiusInterfaceScript();
		}
		if (Configuration::get('enable_social_sharing') == '0')
		{
			if (Configuration::get('enable_social_horizontal_sharing') == '0')
				$script .= loginRadiusHorizontalShareScript();
			if (Configuration::get('enable_social_vertical_sharing') == '0')
				$script .= loginRadiusVerticalShareScript();
		}
		return $script;
	}
	/*
	*  home hook that showing share and counter widget at home page.
	*/
	public function hookHome($params)
	{
		$context = Context::getContext();
		$smarty = $context->smarty;
		if (Configuration::get('enable_social_sharing') == '0')
		{
			if (Configuration::get('social_share_cart') == '1'
				|| Configuration::get('social_share_home') == '1'
				|| Configuration::get('social_share_product') == '1')
			{
				if (Configuration::get('enable_social_horizontal_sharing') == '0')
				{
					$sharingpretext = trim(Configuration::get('social_share_pretext'));
					$smarty->assign('sharingpretext', $sharingpretext);
					if (Configuration::get('chooseshare') != 8 && Configuration::get('chooseshare') != 9)
						$horizontal_sharing = 'lrsharecontainer';
					else
						$horizontal_sharing = 'lrcounter_simplebox';
					$smarty->assign('horizontal_sharing', $horizontal_sharing);
				}
				if (Configuration::get('enable_social_vertical_sharing') == '0')
				{
					$smarty->assign('sharingpretext', false);
					if (Configuration::get('chooseverticalshare') != 6 && Configuration::get('chooseverticalshare') != 7)
						$vertical_sharing = 'lrshareverticalcontainer';
					else
						$vertical_sharing = 'lrcounter_verticalsimplebox';
					$smarty->assign('vertical_sharing', $vertical_sharing);
				}
			}
		}
		if (Configuration::get('enable_social_sharing') == '0')
			return $this->display(__FILE__, 'sharing.tpl');
	}

	/*
	*  Invoice hook that showing share and counter widget at Invoice page.
	*/
	public function hookInvoice($params)
	{
		return $this->hookHome($params);
	}

	/*
	*  Cart hook that showing share and counter widget at Cart page.
	*/
	public function hookShoppingCart($params)
	{
		if (Configuration::get('social_share_cart') == '1')
			return $this->hookHome($params);
	}
	public function hookDisplayRightColumnProduct($params)
	{
		if (_PS_VERSION_ >= 1.6)
			return $this->hookHome($params);
	}
	public function hookDisplayCompareExtraInformation($params)
	{
		return $this->hookHome($params);
	}
	/*
	*  Product footer hook that showing share and counter widget at product footer page.
	*/
	public function hookProductFooter($params)
	{
		if (_PS_VERSION_ < 1.6)
		{
			$context = Context::getContext();
			$cookie = $context->cookie;
			/* Product informations */
			$product = new Product((int)Tools::getValue('id_product'), false, (int)$cookie->id_lang);
			$this->currentproduct = $product;
			if (Configuration::get('social_share_product') == '1')
				return $this->hookHome($params);
		}
	}

	/*
	*  Top hook that Handle login functionality.
	*/
	public function hookTop()
	{
		$context = Context::getContext();
		$cookie = $context->cookie;
		$module = new sociallogin();
		include_once('sociallogin_functions.php');
		//check user is already logged in.
		if (Context::getContext()->customer->isLogged())
		{
			include_once('LoginRadiusSDK.php');
			$loginradius_secret = trim(Configuration::get('API_SECRET'));
			$lr_obj = new LoginRadius();
			//Get the user profile data.
			$userprofile = $lr_obj->loginRadiusGetData($loginradius_secret);
			//Provide account linking when uer is laready logged in.
			if (!empty($userprofile) && Tools::getValue('token'))
				linking($cookie, $userprofile);
			//Remove account linking when user click on remove button.
			if (Tools::getValue('id_provider'))
			{
				$deletequery = 'delete from '.pSQL(_DB_PREFIX_.'sociallogin')." where provider_id ='".pSQL(Tools::getValue('id_provider'))."'";
				Db::getInstance()->Execute($deletequery);
				$cookie->lrmessage = $module->l('Your Social identity has been removed successfully', 'sociallogin');
				Tools::redirect($_SERVER['HTTP_REFERER']);
			}
		}
		//user is not logged in.
		//Retrieve token and provde login functionality.
		if (Tools::getValue('token') && !Tools::getValue('email_create'))
			loginRadiusConnect();
		//Get verification link to verify email.
		elseif (Tools::getValue('SL_VERIFY_EMAIL'))
			verifyEmail();
		//Resend email verfication if user would not get verification email.
		elseif (Tools::getValue('resend_email_verification'))
		{
			loginRadiusResendEmailVerification(Tools::getValue('social_id_value'));
			return;
		}
		//When Email popup is submitted by user.
		elseif (Tools::getValue('hidden_val'))
		{
			if ((Tools::getValue('LoginRadius')) && Tools::getValue('LoginRadius') == 'Submit' && (Tools::getValue('hidden_val') == $cookie->sl_hidden))
			{
				if (Tools::getValue('LoginRadius'))
				{
					$data = unserialize($cookie->login_radius_data);
					$profilefield = unserialize(Configuration::get('profilefield'));
					if (empty($profilefield))
						$profilefield[] = '3';
					$profilefield = implode(';', $profilefield);
					//GEt the form post value in array.
					if (Tools::getValue('SL_EMAIL'))
						$data['email'] = Tools::getValue('SL_EMAIL');
					if (Tools::getValue('SL_CITY'))
						$data['city'] = Tools::getValue('SL_CITY');
					if (Tools::getValue('location-state'))
						$data['state'] = Tools::getValue('location-state');
					if (Tools::getValue('SL_PHONE'))
						$data['phonenumber'] = Tools::getValue('SL_PHONE');
					if (Tools::getValue('SL_ADDRESS'))
						$data['address'] = Tools::getValue('SL_ADDRESS');
					if (Tools::getValue('SL_ZIP_CODE'))
						$data['zipcode'] = Tools::getValue('SL_ZIP_CODE');
					if (Tools::getValue('SL_ADDRESS_ALIAS'))
						$data['addressalias'] = Tools::getValue('SL_ADDRESS_ALIAS');
					if (Tools::getValue('location_country'))
						$data['country'] = Tools::getValue('location_country');
					if (Tools::getValue('SL_FNAME'))
					{
						$data['fname'] = Tools::getValue('SL_FNAME');
						$data['firstname'] = $data['fname'];
					}
					if (Tools::getValue('SL_LNAME'))
					{
						$data['lname'] = Tools::getValue('SL_LNAME');
						$data['lastname'] = $data['lname'];
					}
				}
				$error_message = Configuration::get('ERROR_MESSAGE');
				if (Configuration::get('user_require_field') == '1')
				{
					//If form data is empty.
					if ((empty($data['city']) && strpos($profilefield, '4') !== false) || (empty($data['state']) && Tools::getValue('location-state'))
						|| (empty($data['phonenumber']) && strpos($profilefield, '5') !== false)
						|| (empty($data['address']) && strpos($profilefield, '6') !== false) || (empty($data['zipcode'])
							&& strpos($profilefield, '8') !== false) || (empty($data['country'])
							&& strpos($profilefield, '3') !== false) || empty($data['email'])
						|| (empty($data['addressalias']) && strpos($profilefield, '7') !== false)
						|| (empty($data['fname']) && strpos($profilefield, '1') !== false)
						|| (empty($data['lname']) && strpos($profilefield, '2') !== false))
					{
						popUpWindow('<p style="color:red; padding:0px;">'.$error_message.'</p>', $data);
						return;
					}
					//Check zipcode entered is according to country.
					if (!empty($data['country']) && !empty($data['zipcode']))
					{
						//$check['email']= $data['email'];
						$postcode = trim($data['zipcode']);
						$zip_code = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'country c WHERE c.iso_code = "'.$data['country'].'"');
						if (is_array($zip_code))
						{
						$zip_code_format = isset($zip_code['0']['zip_code_format'])? $zip_code['0']['zip_code_format']: '';
						if (!empty($zip_code_format))
						{
							$zip_regexp = '/^'.$zip_code_format.'$/ui';
							$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
							$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
							$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
							$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
							$zip_regexp = str_replace('C', $data['country'], $zip_regexp);
							if (!preg_match($zip_regexp, $postcode))
							{
								//$data = unserialize($cookie->login_radius_data);
								popUpWindow('<p style="color:red; padding:0px;margin-bottom: 3px;">'.$error_message.'</p><p
								style="color: red;margin-bottom: -10px; font-size: 10px;">'.$module->l('Your zip/postal code is incorrect.').'<br />
								'.$module->l('Must be typed as follows:').' '.str_replace('C', $data['country'],
										str_replace('N', '0', str_replace('L', 'A', $zip_code_format))).'</p>', $data);
								return;
							}
						}
					}
					}
				}
				//Validate meail address is from email popup.
				else if (!Validate::isEmail($data['email']))
				{
					popUpWindow('<p style="color:red; padding:0px;">'.$error_message.'</p>', $data);
					return;
				}
				//Update/Save the data and provide login to user.
				slDataSave($data);
			}
			//Saved data (cookie)is deleted.
			else
			{
				$msgg = $module->l('Cookie has been deleted, please try again.');
				popupVerify($msgg);
			}
		}
	}

	/*
	*  customer account hook that show tpl for Social linking.
	*/
	public function hookDisplayCustomerAccount($params)
	{
		$this->smarty->assign('in_footer', false);
		return $this->display(__FILE__, 'my-account.tpl');
	}

	/*
	*  my account hook that show tpl for Social linking.
	*/
	public function hookMyAccountBlock($params)
	{
		$this->smarty->assign('in_footer', true);
		return $this->display(__FILE__, 'my-account.tpl');
	}

	/**
	 * Moves a hook to the top position
	 **/
	protected function moveLrHookPosition($hook_name, $position)
	{
		// Get the hook identifier.
		if (($id_hook = Hook::getIdByName($hook_name)) !== false)
		{
			// Load the social login  module.
			if (($module = Module::getInstanceByName($this->name)) !== false)
			{
				// Get the max position of hook .
				$sql = 'SELECT MAX(position) AS position FROM `'._DB_PREFIX_."hook_module` WHERE `id_hook` = '".$id_hook."'";
				$result = Db::getInstance()->GetRow($sql);
				if (is_array($result) && isset ($result['position']))
				{
					$way = (($result['position'] >= $position) ? 0 : 1);
					return $module->updatePosition($id_hook, $way, $position);
				}
			}
		}
		//An error occurred.
		return false;
	}


	/**
	 * Returns a list of files to install
	 **/
	protected function getFilesToInstall()
	{
		return array(
			'lrsociallogin_account.html' => array(
				'source' => _PS_MODULE_DIR_.$this->name.'/upload/mails/en/',
				'target' => _PS_MAIL_DIR_.'en/'
			),
			'lrsociallogin_account.txt' => array(
				'source' => _PS_MODULE_DIR_.$this->name.'/upload/mails/en/',
				'target' => _PS_MAIL_DIR_.'en/'
			)
		);
	}
	/*
	* Install hook that  register hook which used by social Login.
	*/
	public function install()
	{
		if (_PS_VERSION_ >= 1.6)
		{
			//Store the added files
			$files_added = array();
			// Copy controller files.
			$files = $this->getFilesToInstall();
			foreach ($files as $file_name => $file_data)
			{
				if (is_array($file_data) && !empty ($file_data['source']) && !empty ($file_data['target']))
				{
					if (!file_exists($file_data['target'].$file_name))
					{
						if (!copy($file_data['source'].$file_name, $file_data['target'].$file_name))
						{
							// Add Error
							$this->context->controller->errors[] = 'Could not copy the file '.$file_name.' to the directory '.$file_data['target'];

							// Remove the copied files.
							foreach ($files_added as $file_name)
							{
								if (file_exists($file_name))
									@unlink($file_name);
							}

							// Abort Installation.
							return false;
						}
						else
							$files_added[] = $file_data['target'].$file_name;
					}
				}
			}
		}
		if (!parent::install()
			|| !$this->registerHook('leftColumn')
			|| !$this->registerHook('createAccountTop')
			|| !$this->registerHook('rightColumn')
			|| !$this->registerHook('top')
			|| !$this->registerHook('Header')
			|| !$this->registerHook('Home')
			|| !$this->registerHook('Invoice')
			|| !$this->registerHook('ShoppingCart')
			|| !$this->registerHook('productfooter')
			|| !$this->registerHook('customerAccount')
			|| !$this->registerHook('displayRightColumnProduct')
			|| !$this->registerHook('displayCompareExtraInformation')
			|| !$this->registerHook('myAccountBlock'))
			return false;
		$hooks_array = array('leftColumn', 'rightColumn');
		foreach ($hooks_array as $value)
			$this->moveLrHookPosition($value, 1);
		//create the social Login table.
		$this->dbTbl();
		return true;
	}

	/*
	* Social Login table structure.
	*/
	public function dbTbl()
	{
		$tbl = pSQL(_DB_PREFIX_.'sociallogin');
		$create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
	`id_customer` int(10) unsigned NOT NULL COMMENT 'foreign key of customers.',
	`provider_id` varchar(100) NOT NULL,
	`Provider_name` varchar(100),
	`rand` varchar(20),
	`verified` tinyint(1) NOT NULL
	)
SQLQUERY;
		Db::getInstance()->Execute($create_table);
	}

	/*
	*  Login Radius Admin UI.
	*/
	public function getContent()
	{
		$html = '';
		if (Tools::isSubmit('submitKeys'))
		{
			$loginradius_api_key = trim(Tools::getValue('API_KEY'));
			$loginradius_api_secret = trim(Tools::getValue('API_SECRET'));
			if (!empty($loginradius_api_key)
				&& !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $loginradius_api_key))
				$html .= $this->displayError($this->l('Please enter a valid API Key', 'sociallogin'));
			elseif (!empty($loginradius_api_secret)
				&& !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $loginradius_api_secret))
				$html .= $this->displayError($this->l('Please enter a valid API Secret', 'sociallogin'));
			elseif ((!empty($loginradius_api_key) || !empty($loginradius_api_secret)) && ($loginradius_api_key == $loginradius_api_secret))
				$html .= $this->displayError($this->l('Please enter a valid API Key and  Secret', 'sociallogin'));
			$val = trim(Tools::getValue('LoginRadius_redirect'));
			if ($val == 'url')
				$val = trim(Tools::getValue('redirecturl'));
			//redirecturl
			Configuration::updateValue('LoginRadius_redirect', Tools::getValue('LoginRadius_redirect'));
			Configuration::updateValue('redirecturl', Tools::getValue('redirecturl'));
			Configuration::updateValue('API_KEY', trim(Tools::getValue('API_KEY')));
			Configuration::updateValue('API_SECRET', trim(Tools::getValue('API_SECRET')));
			Configuration::updateValue('TITLE', Tools::getValue('TITLE', 'Please login with'));
			Configuration::updateValue('EMAIL_REQ', (int)Tools::getValue('EMAIL_REQ'));
			Configuration::updateValue('SEND_REQ', (int)Tools::getValue('SEND_REQ'));
			Configuration::updateValue('CURL_REQ', Tools::getValue('CURL_REQ'));
			Configuration::updateValue('ACC_MAP', (int)Tools::getValue('ACC_MAP'));
			Configuration::updateValue('ERROR_MESSAGE', Tools::getValue('ERROR_MESSAGE'));
			Configuration::updateValue('POPUP_TITLE', Tools::getValue('POPUP_TITLE'));
			Configuration::updateValue('enable_social_sharing', (int)Tools::getValue('enable_social_sharing'));
			Configuration::updateValue('enable_social_horizontal_sharing', (int)Tools::getValue('enable_social_horizontal_sharing'));
			Configuration::updateValue('enable_social_vertical_sharing', (int)Tools::getValue('enable_social_vertical_sharing'));
			Configuration::updateValue('enable_social_login', (int)Tools::getValue('enable_social_login'));
			Configuration::updateValue('social_login_icon_size', (int)Tools::getValue('social_login_icon_size'));
			Configuration::updateValue('social_login_icon_column', trim(Tools::getValue('social_login_icon_column')));
			Configuration::updateValue('social_login_background_color', trim(Tools::getValue('social_login_background_color')));
			Configuration::updateValue('social_share_home', (int)Tools::getValue('social_share_home'));
			Configuration::updateValue('social_share_cart', (int)Tools::getValue('social_share_cart'));
			Configuration::updateValue('social_share_product', (int)Tools::getValue('social_share_product'));
			Configuration::updateValue('social_verticalshare_home', (int)Tools::getValue('social_verticalshare_home'));
			Configuration::updateValue('social_verticalshare_cart', (int)Tools::getValue('social_verticalshare_cart'));
			Configuration::updateValue('social_verticalshare_product', (int)Tools::getValue('social_verticalshare_product'));
			Configuration::updateValue('user_notification', Tools::getValue('user_notification'));
			Configuration::updateValue('user_require_field', Tools::getValue('user_require_field'));
			Configuration::updateValue('update_user_profile', Tools::getValue('update_user_profile'));
			Configuration::updateValue('social_share_pretext', Tools::getValue('social_share_pretext'), 'Share it now!');
			Configuration::updateValue('chooseshare', Tools::getValue('chooseshare'));
			Configuration::updateValue('chooseverticalshare', Tools::getValue('chooseverticalshare'));
			Configuration::updateValue('choosesharepos', Tools::getValue('choosesharepos'));
			Configuration::updateValue('profilefield', count(Tools::getValue('profilefield')) > 0 ? serialize(Tools::getValue('profilefield')) : '');
			Configuration::updateValue('rearrange_settings',
				count(Tools::getValue('rearrange_settings')) > 0 ? serialize(Tools::getValue('rearrange_settings')) : '');
			Configuration::updateValue('vertical_rearrange_settings',
				count(Tools::getValue('vertical_rearrange_settings')) > 0 ? serialize(Tools::getValue('vertical_rearrange_settings')) : '');
			Configuration::updateValue('socialshare_show_counter_list',
				count(Tools::getValue('socialshare_show_counter_list')) > 0 ? serialize(Tools::getValue('socialshare_show_counter_list')) : '');
			Configuration::updateValue('socialshare_counter_list',
				count(Tools::getValue('socialshare_counter_list')) > 0 ? serialize(Tools::getValue('socialshare_counter_list')) : '');
			Configuration::updateValue('verticalsharetopoffset', Tools::getValue('verticalsharetopoffset'));
			$html .= $this->displayConfirmation($this->l('Settings updated.', 'sociallogin'));
		}
		$api_key = trim(Configuration::get('API_KEY'));
		$api_secret = trim(Configuration::get('API_SECRET'));
		$title = Configuration::get('TITLE');
		$social_login_icon_column = trim(Configuration::get('social_login_icon_column'));
		$social_login_background_color = trim(Configuration::get('social_login_background_color'));
		$error_message = Configuration::get('ERROR_MESSAGE');
		$popup_title = Configuration::get('POPUP_TITLE');
		$login_radius_redirect = Configuration::get('LoginRadius_redirect');
		$redirecturl = Configuration::get('redirecturl');
		$profilefield = unserialize(Configuration::get('profilefield'));
		$user_profile_value = Configuration::get('user_require_field');
		if (empty($profilefield))
			$profilefield[] = '3';
		$profilefield = implode(';', $profilefield);
		$rearrange_settings = Configuration::get('rearrange_settings');
		$redirect = '';
		$js_val = 1;
		$checked = array();
		$checked[0] = '';
		$checked[1] = '';
		$checked[2] = '';
		if ($login_radius_redirect == 'profile')
			$checked[1] = 'checked="checked"';
		elseif ($login_radius_redirect == 'url')
		{
			$checked[2] = 'checked="checked"';
			$redirect = $redirecturl;
			$js_val = 0;
		}
		else
			$checked[0] = 'checked="checked"';
		$countericons = unserialize(Configuration::get('socialshare_show_counter_list'));
		if (empty($countericons))
			$countericons = array('Pinterest Pin it', 'Facebook Like', 'Google+ Share', 'Twitter Tweet', 'Hybridshare');
		$verticalcountericons = unserialize(Configuration::get('socialshare_counter_list'));
		if (empty($verticalcountericons))
			$verticalcountericons = array('Pinterest Pin it', 'Facebook Like', 'Google+ Share', 'Twitter Tweet', 'Hybridshare');
		$this->context->controller->addCSS(__PS_BASE_URI__.'modules/sociallogin/css/socialloginandsocialshare.css');
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/sociallogin/js/checkapi.js');
		$this->context->controller->addJQueryUI('ui.sortable');
		$html .= '
	<script type="text/javascript">
	function panelshow(id)
	{
		if (id=="first")
		{
			document.getElementById(id).style.display="block";
			document.getElementById("second").style.display="none";
			document.getElementById("third").style.display="none";
			document.getElementById("panel1").className="panel1 open";
			document.getElementById("panel2").className="panel1 closed";
			document.getElementById("panel3").className="panel3 closed";
		}
		if (id=="second")
		{
			document.getElementById("first").style.display="none";
			document.getElementById(id).style.display="block";
			document.getElementById("third").style.display="none";
			document.getElementById("panel1").className="panel1 closed";
			document.getElementById("panel2").className="panel1 open";
			document.getElementById("panel3").className="panel3 closed";
		}
		if (id=="third")
		{
			document.getElementById("first").style.display="none";
			document.getElementById("second").style.display="none";
			document.getElementById(id).style.display="block";
			document.getElementById("panel1").className="panel1 closed";
			document.getElementById("panel2").className="panel1 closed";
			document.getElementById("panel3").className="panel3 open";
		}
	}
	$(document).ready(function()
	{
		$("div.productTabs").find("a").each(function()
		{
			$(this).attr("href", "javascript:void(0)");
		});
		$("div.productTabs a").click(function()
		{
			var id = $(this).attr("id");
			$(".nav-profile").removeClass("selected");
			$(this).addClass("selected");
			$(".tab-profile").hide()
			$("."+id).show();
		});
		$(function()
		{
			$( "#sortable" ).sortable();
			$( "#sortable" ).disableSelection();
			$( "#verticalsortable" ).sortable();
			$( "#verticalsortable" ).disableSelection();
		});
	});
	function hidetextbox(hide){
		if (hide == 1)
		{
			$("#redirecturl").hide();
		}
		else
		{
			$("#redirecturl").show();
		}
	}
	window.onload = function ()
	{
		counterproviderlist('.Tools::jsonEncode($countericons).');
		sharingproviderlist();
		verticalsharingproviderlist();
		verticalcounterproviderlist('.Tools::jsonEncode($verticalcountericons).');
		Makehorivisible();
		hidetextbox('.$js_val.');
		show_profilefield('.$user_profile_value.');
	}
	</script>
	<div>';
		if ($api_key == '' && $api_secret == '')
			$html .= '<div title="warning" style="background-color: #FFFFE0;
		border:1px solid #E6DB55; margin-bottom:5px; width: 900px;padding: 4px 2px 2px 30px;
		background-image: url(../modules/sociallogin/img/warning.png);background-repeat: no-repeat;background-position:left;">
		'.$this->l('To activate the Social Login, insert LoginRadius API Key and Secret in the API Settings section below.
		 Social Sharing do not require API Key and Secret.', 'sociallogin').'</div>';
		$html .= '<div style="float:left; width:70%;">
	<div>
	<fieldset class="sociallogin_form sociallogin_form_main" style="background: none repeat scroll 0 0 #FFFFE0;border: 1px solid #E6DB55;">
	<div class="row row_title" style="color: #000000; font-weight:normal; background:none;">
	<strong>'.$this->l('Thank you for installing the LoginRadius Prestashop Extension!', 'sociallogin').'</strong>
	</div>
	<div class="row" style="color: #000000;width:90%; line-height:160%; background:none;">
	'.$this->l('To activate the extension, please configure it and manage the social networks from your LoginRadius account.
	 you do not have an account, click', 'sociallogin').'<a href="http://www.loginradius.com" target="_blank"> here
	 </a>'.$this->l('and create one for FREE!', 'sociallogin').'
	</div>
	<div class="row" style="color: #000000; width:90%; line-height:160%; background:none;">
	'.$this->l('We also have Social Plugin for', 'sociallogin').'
	<a href="http://ish.re/ALT8" target="_blank">Joomla</a>,
	<a href="http://ish.re/ADDT" target="_blank">WordPress</a>,
	<a href="http://ish.re/B46C" target="_blank">Drupal</a>,
	<a href="http://ish.re/ALTA" target="_blank">vBulletin</a>,
	<a href="http://ish.re/ALUN" target="_blank">VanillaForum</a>,
	<a href="http://ish.re/ALT6" target="_blank">Magento</a>,
	<a href="http://ish.re/ALTC" target="_blank">osCommerce</a>,
	<a href="http://ish.re/ALUL" target="_blank">X-Cart</a>,
	<a href="http://ish.re/ALUM" target="_blank">Zen-Cart</a>,
	<a href="http://ish.re/ALUP" target="_blank">DotNetNuke</a>,
	<a href="http://ish.re/A7MH" target="_blank">phpBB</a> and
	<a href="http://ish.re/A7MF" target="_blank">SMF</a>!
	</div>
	<div class="row row_button" style="background:none; border:none; background:none;">
	<div class="button2-left">
	<div class="blank" style="margin:0 0 10px 0;">
	<div class="button" style="float:left; cursor:pointer;">  <a  href="http://ish.re/4" target="_blank">
	'.$this->l('Set up my FREE account!', 'sociallogin').'</a></div>
	</div>
	</div>
	</div>
	</fieldset>
	</div>
	<form action = "'.$_SERVER['REQUEST_URI'].'" method = "post" enctype = "multipart/form-data">
	<dl id="pane" class="tabs">
	<dt class="panel1 open" id="panel1"  style="cursor:pointer;" onclick=javascript:panelshow("first") >
	<span>'.$this->l('API Settings', 'sociallogin').'</span></dt>
	<dt class="panel2 closed" id="panel2" style="cursor:pointer;" onclick=javascript:panelshow("second") >
	<span>'.$this->l('Social Login', 'sociallogin').'</span></dt>
	<dt class="panel3 closed" id="panel3" style="cursor:pointer;" onclick=javascript:panelshow("third") >
	<span>'.$this->l('Social Share', 'sociallogin').'</span></dt>
	</dl>
	<div class="current">
	<dd><div style="display:block;" id="first">
	<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2">'.$this->l('LoginRadius API Settings.', 'sociallogin').'</small></th>
	</tr>
	<tr >
	<input id="connection_url" type="hidden" value="'.__PS_BASE_URI__.'" />
	<td colspan="2" ><span class="subhead"> '.$this->l('To activate the plugin, insert LoginRadius API Key & Secret', 'sociallogin').
			'<a href="http://ish.re/9VBI"
			target="_blank" style="color: #0000ff;">
			'.$this->l('(How to get it?)', 'sociallogin').' </a></span>
	<br/><br />
	<label class="lr_api_key">'.$this->l('API Key', 'sociallogin').'
	</label><input type="text" size="50" name="API_KEY" id="API_KEY" value="'.$api_key.'" />
	<br /><br />
	<label class="lr_api_secret">'.$this->l('API Secret', 'sociallogin').'</label>
	<input type="text" name="API_SECRET" id="API_SECRET"  size="50" value="'.$api_secret.'" />
	</td>
	</tr>
	<tr class="row_white">
	<td colspan="2" ><span class="subhead">'.$this->l('What API Connection Method do you prefer to enable API communication?', 'sociallogin').'</span>
	<br /><br />
	<input type="radio" name="CURL_REQ" id="CURL_REQ" value="curl"
	'.(Configuration::get('CURL_REQ') == 'curl' || Configuration::get('CURL_REQ') == ''
			|| function_exists('curl_version') ? 'checked="checked" ' : '').' />
	'.$this->l('Use cURL (Recommended API connection method but sometimes this is disabled at hosting server.)', 'sociallogin').'
	<br /><br />
	<input type="radio" name="CURL_REQ" id="FSOCKOPEN_REQ" value="fsockopen"
	'.(Configuration::get('CURL_REQ') == 'fsockopen' ? 'checked="checked" ' : '').'/>
	'.$this->l('Use FSOCKOPEN (Choose this option, if cURL is disabled at your hosting server.)', 'sociallogin').'
	</td>
	</tr>
	<tr class="row_white">
	<td>
	<div class="row row_button" style="background:none;">
	<div class="button2-left">
	<div class="blank">
	<input type="button" class="button" name="verify_api_setting"  size="50"
	value="'.$this->l('Verify API Settings', 'sociallogin').'" onclick="MakeRequest()" style="cursor:pointer;"/>
	</a>
	</div>
	</div>
	</div>
	</td>
	<td><div id="ajaxDiv" style="font-weight:bold;"></div></td>
	</tr>
	</table>
	</dd>
	<dd><div style="display:none;" id="second">
	<table class="form-table sociallogin_table">
	<tr><th class="head" colspan="2">'.$this->l('LoginRadius Social Login Setting', 'sociallogin').'</small></th></tr>
	<tr><td colspan="2" ><span class="subhead">'.$this->l('Do you want to enable Social Login for your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="enable_social_login" value="0"
	'.(Tools::getValue('enable_social_login', Configuration::get('enable_social_login')) == 0 ? 'checked="checked"' : '').' />
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="enable_social_login" value="1"
	'.(Tools::getValue('enable_social_login', Configuration::get('enable_social_login')) == 1 ? 'checked="checked"' : '').' />
	'.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	</table>
	<table class="form-table sociallogin_table">
	<tr><th class="head" colspan="2">'.$this->l('Social Login Interface Customization', 'sociallogin').'</small></th></tr>
	<tr><td colspan="2" ><span class="subhead">'.$this->l('Select the icon size to use in the Social Login
	interface', 'sociallogin').'</span><br /><br />
	<input type="radio" name="social_login_icon_size" value="1"
	'.(Tools::getValue('social_login_icon_size', Configuration::get('social_login_icon_size')) == 1 ? 'checked="checked"' : '').' />
	'.$this->l('Small', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="social_login_icon_size" value="0"
	'.(Tools::getValue('social_login_icon_size', Configuration::get('social_login_icon_size')) == 0 ? 'checked="checked"' : '').' />
	'.$this->l('Medium', 'sociallogin').'
	</td>
	</tr>
	<tr class="row_white"><td colspan="2" ><span class="subhead">
	'.$this->l('How many social icons would you like to be displayed per row?', 'sociallogin').'</span><br /><br />
	<input type="text" name="social_login_icon_column" id="social_login_icon_column"  size="-7" value="'.$social_login_icon_column.'" />
	</td>
	</tr>
	<tr><td colspan="2" ><span class="subhead">'.$this->l('What background color would you like to use for the Social Login interface?', 'sociallogin').'
	<a title="'.$this->l('Leave empty for transparent. You can enter hexa-decimal code of the color as well as name of the color.', 'sociallogin').'"
	href="javascript:void(0)" style="text-decoration:none;color: #0088CC;">(?)</a></span><br /><br />
	<input type="text" name="social_login_background_color" id="social_login_background_color"
	size="50" value="'.$social_login_background_color.'" />
	</td>
	</tr>
	</table>
	<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2">'.$this->l('LoginRadius Basic Settings', 'sociallogin').'</small></th>
	</tr>
	<tr >
	<td colspan="2" ><span class="subhead">
	'.$this->l('Where do you want to redirect your users after successfully log in?', 'sociallogin').'</span><br /><br />
	<input name="LoginRadius_redirect" value="backpage" type="radio" onclick="javascript:hidetextbox(1);" '.$checked[0].' />
	'.$this->l('Redirect to Same page (Same as traditional login)', 'sociallogin').' <br/>
	<input name="LoginRadius_redirect" value="profile" type="radio" onclick="javascript:hidetextbox(1);" '.$checked[1].' />
	'.$this->l('Redirect to the profile', 'sociallogin').' <br/>
	<input name="LoginRadius_redirect" value="url" type="radio" onclick="javascript:hidetextbox(0);" '.$checked[2].' />
	'.$this->l('Redirect to the following url:', 'sociallogin').'<br/>
	<input style ="display:none;" type="text" name="redirecturl" id="redirecturl"  size="40" value="'.$redirect.'" />
	</td>
	</tr>
	<tr class="row_white"> <td colspan="2" ><span class="subhead">
	'.$this->l('Please enter the title to be shown on Social Login interface', 'sociallogin').'</span><br/>
	<input type="text" name="TITLE"  size="50" value="'.$title.'" /></td></tr>
	<tr >
	<td colspan="2" ><span class="subhead">
	'.$this->l('Do you want your existing user to automatically link to their social accounts in
	case their Prestashop account email address matches with their social account email ID?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="ACC_MAP" value="0" '.(!Tools::getValue('ACC_MAP', Configuration::get('ACC_MAP')) ? 'checked="checked" ' : '').'/>
	'.$this->l('YES, link social accounts to Prestashop account').'<br /><br />
	<input type="radio" name="ACC_MAP" value="1"  '.(Tools::getValue('ACC_MAP', Configuration::get('ACC_MAP')) ? 'checked="checked" ' : '').'/>
	'.$this->l('NO, I want my existing users to continue using native Prestashop login', 'sociallogin').'
	</td>
	</tr>
	<tr class="row_white">
	<td colspan="2" ><span class="subhead">
	'.$this->l('Do you want to send emails to admin after new User registrations at your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="SEND_REQ" value="1" '.(Tools::getValue('SEND_REQ', Configuration::get('SEND_REQ')) ? 'checked="checked" ' : '').'/>
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="SEND_REQ" value="0"  '.(!Tools::getValue('SEND_REQ', Configuration::get('SEND_REQ')) ? 'checked="checked" ' : '').'/>
	 '.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	<tr>
	<td colspan="2" ><span class="subhead">
	'.$this->l('Do you want to send emails to customer after new User registrations at your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="user_notification" value="0"
	 '.(!Tools::getValue('user_notification', Configuration::get('user_notification')) ? 'checked="checked" ' : '').'/>
	 '.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="user_notification" value="1"
	'.(Tools::getValue('user_notification', Configuration::get('user_notification')) ? 'checked="checked" ' : '').'/>
	'.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	<tr class="row_white">
	<td colspan="2" ><span class="subhead">
	'.$this->l('Do you want users to provide required Prestashop profile fields before completing registration process?
	(A pop-up will open asking user to provide details of the fields not coming from Social ID provider)', 'sociallogin').'</span><br /><br />
	<input type="radio" name="user_require_field" onchange="show_profilefield(this.value);" value="1"
	'.(Tools::getValue('user_require_field', Configuration::get('user_require_field')) ? 'checked="checked" ' : '').'/>
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="user_require_field"  onchange="show_profilefield(this.value);" value="0"
	'.(!Tools::getValue('user_require_field', Configuration::get('user_require_field')) ? 'checked="checked" ' : '').'/>
	'.$this->l('No', 'sociallogin').'
	<table class="form-table sociallogin_table1" id="profilefield_display" style="display:block;">
	<tr>
	<td>
	<input type="checkbox" name="profilefield[]" value="1"  '.(strpos($profilefield, '1') !== false ? 'checked="checked"' : '').' />
	'.$this->l('First Name', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="2" '.(strpos($profilefield, '2') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Last Name', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="3" '.(strpos($profilefield, '3') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Country', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="4" '.(strpos($profilefield, '4') !== false ? 'checked="checked"' : '').' />
	'.$this->l('City', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="5" '.(strpos($profilefield, '5') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Mobile Number', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="6" '.(strpos($profilefield, '6') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Address', 'sociallogin').'</td><td>
	<input type="checkbox" name="profilefield[]" value="7" '.(strpos($profilefield, '7') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Address title', 'sociallogin').'
	<input type="checkbox" name="profilefield[]" value="8" '.(strpos($profilefield, '8') !== false ? 'checked="checked"' : '').' />
	'.$this->l('Zip Code', 'sociallogin').'
	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td colspan="2" ><span class="subhead">'.$this->l('A few ID Providers do not supply users e-mail IDs as part of user profile data.
	Do you want users to provide their email IDs before completing registration process?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="EMAIL_REQ" value="0"
	'.(!Tools::getValue('EMAIL_REQ', Configuration::get('EMAIL_REQ')) ? 'checked="checked" ' : '').' />
	'.$this->l('YES, get real email IDs from the users (Ask users to enter their email IDs in a pop-up)', 'sociallogin').'<br /><br />
	<input type="radio" name="EMAIL_REQ" value="1"
	'.(Tools::getValue('EMAIL_REQ', Configuration::get('EMAIL_REQ')) ? 'checked="checked" ' : '').'/>
	'.$this->l('NO, just auto-generate random email IDs for the users', 'sociallogin').'
	</td>
	</tr>
	<tr >
	<input id="connection_url" type="hidden" value="" />
	</tr><tr class="row_white"><td>
	<span class="subhead">'.$this->l('Please enter the message to be displayed to the user in the pop-up', 'sociallogin').'</span><br /><br />
	<input type="text" name="POPUP_TITLE"  size="50"
	value="'.(empty($popup_title) ? $this->l('Please fill the following details to complete the registration', 'sociallogin') : $popup_title).'" />
	<br /></td></tr>
	<tr>
	<td>
	<span class="subhead">Please enter the message to be shown to the user in case of an invalid entry in popup</span><br /><br />
	<input type="text" name="ERROR_MESSAGE"  size="50"
	value="'.(empty($error_message) ? $this->l('Please enter the following fields', 'sociallogin') : $error_message).'" />
	</td>
	</tr>
	</table>
	<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2">'.$this->l('User Profile Data Option', 'sociallogin').'</small></th>
	</tr>
	<tr>
	<td colspan="2" ><span class="subhead">
	'.$this->l('Do you want to update User Profile Data in your Prestashop database, every time user login to your website?', 'sociallogin').'
	<a title="'.$this->l('If you disable this option,
user profile data will be saved only once when user login first time at your website, user profile details
will not be updated in your Prestashop database, even if user changes his/her social
account details.', 'sociallogin').'" href="javascript:void(0)" style="text-decoration:none">
	(<span style="color:#3CF;">?</span>)</a></span><br /><br />
	<input type="radio" name="update_user_profile" value="0"
	'.(!Tools::getValue('update_user_profile', Configuration::get('update_user_profile')) ? 'checked="checked" ' : '').' />
	'.$this->l(' Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="update_user_profile" value="1"
	'.(Tools::getValue('update_user_profile', Configuration::get('update_user_profile')) ? 'checked="checked" ' : '').'/>
	'.$this->l(' No', 'sociallogin').'
	</td>
	</tr>
	</table>
	</div></dd>
	<!-- social share -->
	<dd><div style="display:none;" id="third">
	<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2">'.$this->l('LoginRadius Social Share Settings', 'sociallogin').'</small></th>
	</tr>
	<tr><td colspan="2" ><span class="subhead">'.$this->l('Do you want to enable Social Sharing for your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="enable_social_sharing" value="0"
	'.(Tools::getValue('enable_social_sharing', Configuration::get('enable_social_sharing')) == 0 ? 'checked="checked"' : '').' />
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="enable_social_sharing" value="1"
	'.(Tools::getValue('enable_social_sharing', Configuration::get('enable_social_sharing')) == 1 ? 'checked="checked"' : '').' />
	'.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	<tr class="row_white">
	<td colspan="2" >
	<span class="subhead">'.$this->l('What Social Sharing widget theme do you want to use across your website?', 'sociallogin').'</span><br /><br />';
		$style_visible = 'style="position:absolute;border-bottom:8px solid #EBEBEB;
	  border-right:8px solid transparent; border-left:8px solid transparent; margin:19px 0 0 -106px"';
		$html .= '<a id="mymodal" href="javascript:void(0);" onclick="Makehorivisible();"><b>'.$this->l('Horizontal', 'sociallogin').'</b></a> &nbsp;|&nbsp;
	<a class="mymodal" href="javascript:void(0);" onclick="Makevertivisible();"><b>'.$this->l('Vertical', 'sociallogin').'</b></a>
	<table style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 0;border-collapse: separate !important;">
	<span id = "arrow" '.$style_visible.'></span>
	<tr id="enable_social_horizontal_sharing" style="background:#EBEBEB;"><td colspan="2" >
	<span class="subhead">'.$this->l('Do you want to enable Horizontal Social Sharing for your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="enable_social_horizontal_sharing" value="0"
	'.(Tools::getValue('enable_social_horizontal_sharing', Configuration::get('enable_social_horizontal_sharing')) == 0 ? 'checked="checked"' : '').' />
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="enable_social_horizontal_sharing" value="1"
	'.(Tools::getValue('enable_social_horizontal_sharing', Configuration::get('enable_social_horizontal_sharing')) == 1 ? 'checked="checked"' : '').' />
	'.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	<tr id ="enable_social_vertical_sharing" style="background:#EBEBEB;"><td colspan="2" ><span class="subhead">
	'.$this->l('Do you want to enable Vertical Social Sharing for your website?', 'sociallogin').'</span><br /><br />
	<input type="radio" name="enable_social_vertical_sharing" value="0"
	'.(Tools::getValue('enable_social_vertical_sharing', Configuration::get('enable_social_vertical_sharing')) == 0 ? 'checked="checked"' : '').' />
	'.$this->l('Yes', 'sociallogin').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="enable_social_vertical_sharing" value="1"
	'.(Tools::getValue('enable_social_vertical_sharing', Configuration::get('enable_social_vertical_sharing')) == 1 ? 'checked="checked"' : '').' />
	'.$this->l('No', 'sociallogin').'
	</td>
	</tr>
	<tr id="horizontal_text_label" class="row_white">
	<td colspan="2" ><span class="subhead">'.$this->l('Enter the text that you wish to be displayed above the Social Sharing Interface.
	Leave the field blank if you don`t want any text to be displayed.', 'sociallogin').'</span>
	<br/><input type="text" name="social_share_pretext" value="'.Configuration::get('social_share_pretext').'" />
	</td>
	</tr>
	<tr class="sharing_block" style="background:#EBEBEB;"><td><div class="subhead">'.$this->l('Choose a Sharing theme', 'sociallogin').'</div>';
		$html .= '<div style="padding:10px;margin:10px 0 0 0;"><div id="sharehorizontal" style=display:block>
	<label for="hori32"><input name="chooseshare" id = "hori32" '.(Configuration::get('chooseshare') == 0
			|| Configuration::get('chooseshare') == '' ? 'checked="checked"' : '').'  type="radio"  value="0"
	style="margin: 2px 10px 0 0; display: block; float: left !important;" onclick ="loginradius_horizontal_sharing();" />
	<img src = "../modules/sociallogin/img/horizonSharing32.png"/><br /><br /></label>
	<label for="hori16"><input name="chooseshare" id = "hori16" '.(Configuration::get('chooseshare') == 1 ? 'checked="checked"' : '').'
	type="radio" value="1" style="margin: 2px 10px 0 0; display: block; float: left !important;" onclick ="loginradius_horizontal_sharing();" />
	<img src = "../modules/sociallogin/img/horizonSharing16.png" /><br /><br /></label>
	<label for="horithemelarge"><input name="chooseshare" id = "horithemelarge" '.(Configuration::get('chooseshare') == 2 ? 'checked="checked"' : '').'
	 type="radio" value="2" style="margin: 2px 10px 0 0; display: block; float: left !important;" onclick ="loginradius_horizontal_simple();" />
	<img src = "../modules/sociallogin/img/single-image-theme-large.png" /><br /><br /></label>
	<label for="horithemesmall"><input name="chooseshare" id = "horithemesmall" '.(Configuration::get('chooseshare') == 3 ? 'checked="checked"' : '').'
	type="radio" value="3" style="margin: 2px 10px 0 0; display: block; float: left !important;" onclick ="loginradius_horizontal_simple();" />
	<img src = "../modules/sociallogin/img/single-image-theme-small.png" /><br /><br /></label>
<label for="hybrid-horizontal-horizontal"> 	<input name="chooseshare" id = "hybrid-horizontal-horizontal"
'.(Configuration::get('chooseshare') == 9 ? 'checked="checked"' : '').'
type="radio" value="9" style="margin: 2px 10px 0 0; display: block; float: left !important;"
onclick ="loginradius_horizontal_hybrid();" /><img src = "../modules/sociallogin/img/hybrid-horizontal-horizontal.png" /><br /><br /></label>
	<label for="hybrid-horizontal-vertical"><input name="chooseshare" id = "hybrid-horizontal-vertical"
	'.(Configuration::get('chooseshare') == 8 ? 'checked="checked"' : '').'
	type="radio" value="8" style="margin: 2px 10px 0 0; display: block; float: left !important;" onclick ="loginradius_horizontal_hybrid();" />
	<img src = "../modules/sociallogin/img/hybrid-horizontal-vertical.png" /></label>
	</div>';
		$html .= '<div id="sharevertical" style=display:none>
	<label for="vertibox32"><input name="chooseverticalshare" id = "vertibox32"  '.(Configuration::get('chooseverticalshare') == 4
			|| Configuration::get('chooseverticalshare') == '' ? 'checked="checked"' : '').'  type="radio" value="4" style="vertical-align:top"
	onclick ="loginradius_vertical_sharing();"/><img src =  "../modules/sociallogin/img/32VerticlewithBox.png" /></label>
	<label for="vertibox16"><input name="chooseverticalshare" id = "vertibox16"
	'.(Configuration::get('chooseverticalshare') == 5 ? 'checked="checked"' : '').'	type="radio" value="5" style="vertical-align:top"
	onclick ="loginradius_vertical_sharing();"/> <img src = "../modules/sociallogin/img/16VerticlewithBox.png" style="vertical-align:top"/></label>
	<label for="hybrid-verticle-vertical">	<input name="chooseverticalshare" id = "hybrid-verticle-vertical"
	'.(Configuration::get('chooseverticalshare') == 6 || Configuration::get('chooseverticalshare') == '' ? 'checked="checked"' : '').'
	type="radio" value="6" style="vertical-align:top"  onclick ="loginradius_vertical_counter();" />
	<img src =  "../modules/sociallogin/img/hybrid-verticle-vertical.png" /></label>
    <label for="hybrid-verticle-horizontal"> 	<input name="chooseverticalshare" id = "hybrid-verticle-horizontal"
    '.(Configuration::get('chooseverticalshare') == 7 ? 'checked="checked"' : '').'  type="radio" value="7" style="vertical-align:top"
    onclick ="loginradius_vertical_counter();"/> <img src = "../modules/sociallogin/img/hybrid-verticle-horizontal.png" style="vertical-align:top"/>
    </label><br /><br />
	<div style="clear:both;overflow:auto; background:#EBEBEB;; padding:10px;">
	<p style="margin:0 0 6px 0; padding:0px;color:#000000;"><strong>
	'.$this->l('Select the position of Social Sharing widget', 'sociallogin').'</strong></p>';
	$html .= '<input name="choosesharepos" id = "topleft" type="radio" '.(Configuration::get('choosesharepos') == 0
			|| Configuration::get('choosesharepos') == '' ? 'checked="checked"' : '').' value="0" />Top Left<br />
	<input name="choosesharepos" id = "topright" type="radio" '.(Configuration::get('choosesharepos') == 1 ? 'checked="checked"' : '').' value="1" />
	Top Right<br />
	<input name="choosesharepos" id = "bottomleft" type="radio" '.(Configuration::get('choosesharepos') == 2 ? 'checked="checked"' : '').' value="2" />
	Bottom Left<br />
	<input name="choosesharepos" id = "bottomright" type="radio" '.(Configuration::get('choosesharepos') == 3 ? 'checked="checked"' : '').' value="3" />
	Bottom Right<br /><br />
	<p style="margin:0 0 6px 0; padding:0px;color:#000000;"><strong>
	<strong>'.$this->l('Specify distance of vertical sharing interface from top. (Leave empty for default behaviour)', 'sociallogin').'</strong>
	<a title="'.$this->l('Enter a number (For example - 200). It will set the \'top\' CSS attribute of the interface to the value specified.
	Increase in the number pushes interface towards bottom.', 'sociallogin')
			.'" href="javascript:void(0)" style="text-decoration:none"> (<span style="color:#3CF;">?</span>)</a></strong></p>
	<input type="text" id="topoffset" name="verticalsharetopoffset"
	value="'.(is_numeric(Configuration::get('verticalsharetopoffset')) ? Configuration::get('verticalsharetopoffset').'px' : '').'" >
	</div></div></div></div>
	</td>
	</tr>
	<tr class="label_sharing_networks">
	<td colspan="2" ><span class="subhead">'.$this->l('What Sharing Networks do you want to show in the sharing widget? (All other
	sharing networks will be shown as part of LoginRadius sharing icon)', 'sociallogin').'</span><br /><div id="loginRadiusSharingLimit"
	style="color: red; display: none; margin-bottom: 5px;">You can select only 9 providers.</div>
	</td>
	</tr>
	<tr>
	<td>
	<table class="form-table sociallogin_table" id="shareprovider">
	</table>
	</td>
	</tr>
	<tr class="loginradius_rearrange_icons">
	<td colspan="2" style="background:#EBEBEB;" >
	<span class="subhead">'.$this->l('What sharing network order do you prefer for your sharing widget?', 'sociallogin').'</span><br />
	<ul id="sortable" style="height:35px;">';
		$li = '';
		$rearrange_settings = unserialize(Configuration::get('rearrange_settings'));
		if (empty($rearrange_settings))
			$rearrange_settings = array('facebook', 'googleplus', 'twitter', 'linkedin', 'pinterest');
		foreach ($rearrange_settings as $provider)
			$li .= '<li title="'.$provider.'" id="loginRadiusLI'.$provider.'" class="lrshare_iconsprite32 lrshare_'.$provider.'">
		<input type="hidden" name="rearrange_settings[]" value="'.$provider.'" />
		</li>';
		$html .= $li.'</ul>
	</td>
	</tr>
	<tr class="label_verticalsharing_networks">
	<td colspan="2" ><span class="subhead">'.$this->l('What Sharing Networks do you want to show in the sharing widget?
	(All other sharing networks will be shown as part of LoginRadius sharing icon)', 'sociallogin').'</span><br />
	<div id="loginRadiusverticalSharingLimit" style="color: red; display: none; margin-bottom: 5px;">
	You can select only 9 providers.</div>
	</td>
	</tr>
	<tr>
	<td>
	<table class="form-table sociallogin_table" id="verticalshareprovider">
	</table>
	</td>
	</tr>
	<tr class="loginradius_verticalrearrange_icons">
	<td colspan="2" >
	<span class="subhead">'.$this->l('What sharing network order do you prefer for your sharing widget?', 'sociallogin').'</span><br />
	<ul id="verticalsortable" style="height:35px;">';
		$li = '';
		$vertical_rearrange_settings = unserialize(Configuration::get('vertical_rearrange_settings'));
		if (empty($vertical_rearrange_settings))
			$vertical_rearrange_settings = array('facebook', 'googleplus', 'twitter', 'linkedin', 'pinterest');
		foreach ($vertical_rearrange_settings as $provider)
			$li .= '<li title="'.$provider.'" id="loginRadiusverticalLI'.$provider.'" class="lrshare_iconsprite32 lrshare_'.$provider.'">
		<input type="hidden" name="vertical_rearrange_settings[]" value="'.$provider.'" />
		</li>';
		$html .= $li.'</ul>
	</td>
	</tr>
	<tr class="horizontal_location">
	<td>
	<span class="subhead">'.$this->l('What area(s) do you want to show the social sharing widget?', 'sociallogin').'</span>
	<table class="form-table">
	<tr>
	<td>';
		if (Tools::getValue('social_share_home') == 0 && Tools::getValue('social_share_cart') == 0 && Tools::getValue('social_share_product') == 0)
			Configuration::updateValue('social_share_home', 1);
		$html .= '<input type="checkbox" name="social_share_home" value="1"
		'.(Tools::getValue('social_share_home', Configuration::get('social_share_home')) ? 'checked="checked"' : '').' />Show on Home page</td><td>
	<input type="checkbox" name="social_share_cart" value="1"
	'.(Tools::getValue('social_share_cart', Configuration::get('social_share_cart')) ? 'checked="checked"' : '').' />Show on Cart page</td><td>
	<input type="checkbox" name="social_share_product" value="1"
	'.(Tools::getValue('social_share_product', Configuration::get('social_share_product')) ? 'checked="checked"' : '').' />Show on Product Page
	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr class ="vertical_location">
	<td>
	<span class="subhead">'.$this->l('What area(s) do you want to show the social sharing widget?', 'sociallogin').'</span>
	<table class="form-table">
	<tr>
	<td>';
		if (Tools::getValue('social_verticalshare_home') == 0 && Tools::getValue('social_verticalshare_cart') == 0
			&& Tools::getValue('social_verticalshare_product') == 0)
			Configuration::updateValue('social_verticalshare_home', 1);
		$html .= '<input type="checkbox" name="social_verticalshare_home" value="1"
	'.(Tools::getValue('social_verticalshare_home', Configuration::get('social_verticalshare_home')) ? 'checked="checked"' : '').' />
	Show on Home page</td><td><input type="checkbox" name="social_verticalshare_cart" value="1"
	'.(Tools::getValue('social_verticalshare_cart', Configuration::get('social_verticalshare_cart')) ? 'checked="checked"' : '').' />
	Show on Cart page</td><td><input type="checkbox" name="social_verticalshare_product" value="1"
	'.(Tools::getValue('social_verticalshare_product', Configuration::get('social_verticalshare_product')) ? 'checked="checked"' : '').' />
	Show on Product Page</td></tr></table></td></tr></table></td>
	</tr>
	</table>
	</div></dd>
	<input class="button" type="submit" name="submitKeys" value="'.$this->l('Save Configuration', 'sociallogin').'" style="cursor:pointer;margin: 0 !important;"/>
	</div>
	</form>
	</div>
	<div style="float:right; width:29%;">
	<!-- Help Box -->
	<div style="background: none repeat scroll 0 0 rgb(231, 255, 224);border: 1px solid rgb(191, 231, 176); overflow:auto; margin:0 0 10px 0;">
	<h3 style="border-bottom:#000000 1px solid; margin:0px; padding:0 0 6px 0; border-bottom: 1px solid #B3E2FF; color: #000000; margin:10px;">
	'.$this->l('Help & Documentations', 'sociallogin').'</h3>
	<ul class="help_ul">
	<li><a href="http://ish.re/BBM0"
	target="_blank">
	'.$this->l('Plugin Installation, Configuration and Troubleshooting', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/9VBI" target="_blank">
	'.$this->l('How to get LoginRadius API Key & Secret', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/2BS" target="_blank">
	'.$this->l('Support Documentations', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/8PG2" target="_blank">'.$this->l('Discussion Forum', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/96M7" target="_blank">
	'.$this->l('About LoginRadius', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/96M9" target="_blank">
	'.$this->l('LoginRadius Products', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/96M8" target="_blank">
	'.$this->l('Social Plugins', 'sociallogin').'</a></li>
	<li><a href="http://ish.re/96MA" target="_blank">
	'.$this->l('Social SDKs', 'sociallogin').'</a></li>
	</ul>
	</div>
	<div style="clear:both;"></div>
	<div style="background:#EAF7FF; border: 1px solid #B3E2FF;  margin:0 0 10px 0; overflow:auto;">
	<h3 style="border-bottom:#000000 1px solid; margin:0px; padding:0 0 6px 0; border-bottom: 1px solid #B3E2FF; color: #000000; margin:10px;">
	Stay Update!</h3>
	<p align="justify" style="line-height: 19px;font-size:12px !important;margin:10px !important;color: #000000;">
	'.$this->l('To receive updates on new features, releases, etc, please connect to one of our social media pages.', 'sociallogin').'</p>
	<ul class="stay_ul">
	<li class="first">
	<iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px;
	height: 63px;" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&amp;
	href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&amp;send=false&amp;layout=box_count&amp;
	width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=9" data-original-title="Like us on Facebook"></iframe>
	</li>
	</ul>
	</div>
	<div style="clear:both;"></div>
	</div>
	</div>';
		return $html;
	}

	/*
	* Show Social linking Interface for account linking.
	*/
	public static function jsinterface()
	{
		$context = Context::getContext();
		$cookie = $context->cookie;
		$getdata = Db::getInstance()->ExecuteS('SELECT * FROM '.pSQL(_DB_PREFIX_.'customer').' as c WHERE c.email='." '$cookie->email' ".' LIMIT 0,1');
		$num = (!empty($getdata['0']['id_customer']) ? $getdata['0']['id_customer'] : '');
		$linkedprovider = Db::getInstance()->ExecuteS('SELECT * from '.pSQL(_DB_PREFIX_.'sociallogin')." where `id_customer`='".$num."'");
		if (Context::getContext()->customer->isLogged())
		{
			$http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'Off' && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://');
			$loc = urldecode($http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			if (strpos($loc, 'sociallogin') !== false)
				$cookie->currentquerystring = $loc;
		}
		if (!$cookie->lr_login)
			$cookie->loginradius_id = '';
		return $linkedprovider;
	}

	/*
	*  delete social login table form database.
	*/
	public function uninstall()
	{
		if (!parent::uninstall())
			Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'sociallogin`');
		parent::uninstall();
		if (_PS_VERSION_ >= 1.6)
		{
			// Remove controller files.
			$files = $this->getFilesToInstall();
			foreach ($files as $file_name => $file_data)
			{
				if (is_array($file_data) && !empty ($file_data['source']) && !empty ($file_data['target']))
				{
					if (file_exists($file_data['target'].$file_name))
						@unlink($file_data['target'].$file_name);
				}
			}
		}
		return true;
	}
}
?>
