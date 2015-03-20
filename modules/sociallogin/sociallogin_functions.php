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
/*
** Connect Social login Interface and Handle loginradius token.
*/
function loginRadiusConnect()
{
	//create object of social login class.
	$module = new sociallogin();
	include_once('LoginRadiusSDK.php');
	$secret = trim(Configuration::get('API_SECRET'));
	$lr_obj = new LoginRadius();
	//Get the user_profile of authenticate user.
	$user_profile = $lr_obj->loginRadiusGetData($secret);
	//If user is not logged in and user is authenticated then handle login functionality.
	if ($lr_obj->is_authenticated == true && !Context:: getContext()->customer->isLogged())
	{
		$lrdata = loginRadiusMappingProfileData($user_profile);
		//Check Social provider id is already exist.
		$social_id_exist = 'SELECT * FROM '.pSQL(_DB_PREFIX_.'sociallogin').' as sl INNER JOIN '.pSQL(_DB_PREFIX_.'customer')." as c
		WHERE sl.provider_id='".pSQL($lrdata['id'])."' and c.id_customer=sl.id_customer  LIMIT 0,1";
		$db_obj = Db::getInstance()->ExecuteS($social_id_exist);
		$td_user = '';
		$user_id_exist = (!empty($db_obj[0]['id_customer']) ? $db_obj[0]['id_customer'] : '');
		if ($user_id_exist >= 1)
		{
			$active_user = (!empty($db_obj['0']['active']) ? $db_obj['0']['active'] : '');
			//Verify user and provide login.
			loginRadiusVerifiedUserLogin($user_id_exist, $lrdata, $td_user);
		}
		//If Social provider is is not exist in database.
		elseif ($user_id_exist < 1)
		{
			if (!empty($lrdata['email']))
			{
				// check email address is exist in database if email is retrieved from Social network.
				$user_email_exist = Db::getInstance()->ExecuteS('SELECT * FROM '.pSQL(_DB_PREFIX_.'customer').' as c
				WHERE c.email="'.pSQL($lrdata['email']).'" LIMIT 0,1');
				$user_id = (!empty($user_email_exist['0']['id_customer']) ? $user_email_exist['0']['id_customer'] : '');
				$active_user = (!empty($user_email_exist['0']['active']) ? $user_email_exist['0']['active'] : '');
				if ($user_id >= 1)
				{
					$td_user = 'yes';
					if (deletedUser($user_email_exist))
					{
						$msg = "<p style ='color:red;'>".$module->l('Authentication failed.', 'sociallogin_functions').'</p>';
						popupVerify($msg);
						return;
					}
					if (Configuration::get('ACC_MAP') == 0)
					{
						$tbl = pSQL(_DB_PREFIX_.'sociallogin');
						$query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`,`verified`,`rand`)
						values ('".$user_id."','".pSQL($lrdata['id'])."' , '".pSQL($lrdata['provider'])."','1','') ";
						Db::getInstance()->Execute($query);
					}
					loginRadiusVerifiedUserLogin($user_id, $lrdata, $td_user);
				}
			}
			$lrdata['send_verification_email'] = 'no';
			//new user. user not found in database. set all details
			if (Configuration::get('user_require_field') == '1')
			{
				if (empty($lrdata['email']))
					$lrdata['send_verification_email'] = 'yes';
				if (Configuration::get('EMAIL_REQ') == '1' && empty($lrdata['email']))
				{
					$lrdata['email'] = emailRand($lrdata);
					$lrdata['send_verification_email'] = 'no';
				}
				//If user is not exist and then add all lrdata into cookie.
				storeInCookie($lrdata);
				//Open the popup to get require fields.
				$value = popUpWindow('', $lrdata);
				if ($value == 'noshowpopup')
					storeAndLogin($lrdata);
				return;
			}
			//Save data into cookie and open email popup.
			if (Configuration::get('EMAIL_REQ') == '0' && empty($lrdata['email']))
			{
				$lrdata['send_verification_email'] = 'yes';
				storeInCookie($lrdata);
				popUpWindow('', $lrdata);
				return;
			}
			elseif (Configuration::get('EMAIL_REQ') == '1' && empty($lrdata['email']))
				$lrdata['email'] = emailRand($lrdata);
			//Store user data into database and provide login functionality.
			storeAndLogin($lrdata);
		}
		//If user is delete and set action to provide no login to user.
		elseif (deletedUser($db_obj))
		{
			$msg = "<p style ='color:red;'><b>".$module->l('Authentication failed.', 'sociallogin_functions').'</b></p>';
			popupVerify($msg);
			return;
		}
		//If user is blocked.
		if ($active_user == 0)
		{
			$msg = "<p style ='color:red;'><b>".$module->l('User has been disbled or blocked.', 'sociallogin_functions').'</b></p>';
			popupVerify($msg);
			return;
		}
	}
}

/*
* Provide Social linking.
*/
function linking($arrdata, $user_profile)
{
	$module = new sociallogin();
	$context = Context::getContext();
	$cookie = $context->cookie;
	$cookie->lrmessage = '';
	//check User is authenticate and user data is not empty.
	if (!empty($user_profile) && isset($user_profile->ID) && $user_profile->ID != '')
	{
		//Check Social ID and  provider is in database.
		$tbl = pSQL(_DB_PREFIX_.'sociallogin');
		$getdata = Db::getInstance()->ExecuteS('SELECT * FROM '.pSQL(_DB_PREFIX_.'customer')." as c WHERE c.email='".pSQL($arrdata->email)."' LIMIT 0,1");
		$num = (!empty($getdata['0']['id_customer']) ? $getdata['0']['id_customer'] : '');
		$sql = "SELECT COUNT(*) as num from $tbl where `id_customer`='".$num."' and `Provider_name`='".pSQL($user_profile->Provider)."'";
		$row = Db::getInstance()->getRow($sql);
		if ($row['num'] == 0)
		{
			//check only social id is in database.
			$check_user_id = Db::getInstance()->ExecuteS('SELECT c.id_customer FROM '.pSQL(_DB_PREFIX_.'customer').' AS c INNER JOIN '.$tbl.'
			AS sl ON sl.id_customer=c.id_customer WHERE sl.provider_id= "'.pSQL($user_profile->ID).'"');
			if (empty($check_user_id['0']['id_customer']))
				Db::getInstance()->Execute('DELETE FROM '.$tbl."  WHERE provider_id='".pSQL($user_profile->ID)."'");
			$lr_id = Db::getInstance()->ExecuteS('SELECT provider_id FROM '.$tbl."  WHERE provider_id= '".pSQL($user_profile->ID)."'");
			//Present then show warning message.
			if (!empty($lr_id['0']['provider_id']))
				$cookie->lrmessage = $module->l('Account cannot be mapped as it already exists in database', 'sociallogin_functions');
			else
			{
				$query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`,`verified`,`rand`)
				values ('$num','".$user_profile->ID."' , '".pSQL($user_profile->Provider)."','1','') ";
				Db::getInstance()->Execute($query);
				$cookie->lrmessage = $module->l('Your account is successfully mapped', 'sociallogin_functions');
			}
		}
		//Already linked with socialid and provider.
		//Show Warning message.
		else
			$cookie->lrmessage = $module->l('Account cannot be mapped as it already exists in database', 'sociallogin_functions');
	}
	//After Linking Provide redirection.
	$loc = $cookie->currentquerystring;
	$cookie->currentquerystring = '';
	Tools::redirectLink($loc);
}

/*
* Check user is Verified and Show notification message.
*/
function loginRadiusVerifiedUserLogin($user_id, $lrdata, $td_user = '')
{
	$module = new sociallogin();
	$social_id = $lrdata['id'];
	if (verifiedUser($user_id, $social_id, $td_user))
	{
		if (Configuration::get('update_user_profile') == 0)
			updateUserProfileData($user_id, $lrdata);
		//login User.
		loginRadiusLoginUser($user_id, $social_id);
		return;
	}
	else
	{
		//User is not verified.
		$msg = $module->l('Your confirmation link has been sent to your email address. Please verify your email by clicking on
		confirmation link.', 'sociallogin_functions');
		popupVerify($msg, $social_id);
		return;
	}
}

/*
* Check user deleted or not.
*/
function deletedUser($db_obj)
{
	$deleted = $db_obj['0']['deleted'];
	if ($deleted == 1)
		return true;
	return false;
}

/*
* find user verified or not.
*/
function verifiedUser($num, $pid, $td_user)
{
	$db_obj = Db::getInstance()->ExecuteS('SELECT * FROM '.pSQL(_DB_PREFIX_.'sociallogin').' as c WHERE c.id_customer='." '$num'".'
	AND c.provider_id='." '$pid'".' LIMIT 0,1');
	$verified = $db_obj['0']['verified'];
	if ($verified == 1 || $td_user == 'yes')
		return true;
	return false;
}

/*
* Update the user profile data.
*/
function updateUserProfileData($user_id, $lrdata)
{
	$date_upd = date('Y-m-d H:i:s', time());
	$str = '';
	if (!empty($lrdata['fname']))
		$str .= "firstname='".pSQL($lrdata['fname'])."',";
	if (!empty($lrdata['lname']))
		$str .= "lastname='".pSQL($lrdata['lname'])."',";
	if (!empty($lrdata['gender']))
	{
		$gender = ((!empty($lrdata['gender'])
			&& (strpos($lrdata['gender'], 'f') !== false
				|| (trim($lrdata['gender']) == 'F'))) ? 2 : 1);
		$str .= "id_gender='".$gender."',";
	}
	if (!empty($lrdata['dob']))
	{
		$dob_arr = explode('/', $lrdata['dob']);
		$dob = $dob_arr[2].'-'.$dob_arr[0].'-'.$dob_arr[1];
		$date_of_birth = (!empty($dob) && Validate::isBirthDate($dob) ? $dob : '');
		$str .= "birthday='".$date_of_birth."',";
	}
	Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'customer SET '.$str." date_upd='$date_upd' WHERE 	id_customer	= $user_id");
}

/*
* Save logged user credentaisl to array.
*/
function loginRadiusLoginUser($user_id, $social_id)
{
	$db_obj = Db::getInstance()->ExecuteS('SELECT * FROM '.pSQL(_DB_PREFIX_.'customer').' as c WHERE c.id_customer='." '$user_id' ".' LIMIT 0,1');
	$arr = array();
	$arr['id'] = $db_obj['0']['id_customer'];
	$arr['fname'] = $db_obj['0']['firstname'];
	$arr['lname'] = $db_obj['0']['lastname'];
	$arr['email'] = $db_obj['0']['email'];
	$arr['pass'] = $db_obj['0']['passwd'];
	$arr['loginradius_id'] = $social_id;
	loginRedirect($arr);
}

/*
* Social Login Interface Script Code.
*/
function loginRadiusInterfaceScript()
{
	$context = Context::getContext();
	$cookie = $context->cookie;
	$loginradius_apikey = trim(Configuration::get('API_KEY'));
	$http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'Off' && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://');
	$loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) :
		urlencode($http.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));
	if (Context::getContext()->customer->isLogged())
	{
		if (strpos($loc, 'sociallogin') !== false)
		{
			$cookie->currentquerystring = $loc;
			$loc = urlencode($http.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		}
	}
	$interfaceiconsize = (Configuration::get('social_login_icon_size') == 1 ? 'small' : '');
	$interfacebackgroundcolor = Configuration::get('social_login_background_color');
	$interfacebackgroundcolor = (!empty($interfacebackgroundcolor) ? trim($interfacebackgroundcolor) : '');
	$interfacecolumn = Configuration::get('social_login_icon_column');
	$interfacecolumn = (!empty($interfacecolumn) && is_numeric($interfacecolumn) ? trim($interfacecolumn) : 0);
	return '<script src="//hub.loginradius.com/include/js/LoginRadius.js"></script><script type="text/javascript">
	function loginradius_interface() { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "'.$interfaceiconsize.'";
	$ui.lrinterfacebackground="'.$interfacebackgroundcolor.'";$ui.noofcolumns='.$interfacecolumn.';$ui.apikey = "'.$loginradius_apikey.'";
	$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }var options={};
	options.login=true; LoginRadius_SocialLogin.util.ready(loginradius_interface); </script>';
}

/*
* Horizontal Social Sharing Widget Script Code.
*/
function loginRadiusHorizontalShareScript()
{
	$context = Context::getContext();
	$context->controller->addJS(__PS_BASE_URI__.'modules/sociallogin/js/sharinginterface.js');
	$share_script = '';
	$horizontal_theme = Configuration::get('chooseshare') ? Configuration::get('chooseshare') : '0';
	if ($horizontal_theme == 8 || $horizontal_theme == 9)
	{
		$counter_list = unserialize(Configuration::get('socialshare_show_counter_list'));
		if (empty($counter_list))
			$counter_list = array('Pinterest Pin it', 'Facebook Like', 'Google+ Share', 'Twitter Tweet', 'Hybridshare');
		$providers = implode('","', $counter_list);
		$interface = 'simple';
		if ($horizontal_theme == '8')
			$type = 'vertical';
		else
			$type = 'horizontal';
		$share_script .= '<script type="text/javascript">LoginRadius.util.ready(function () { $SC.Providers.Selected = ["'.$providers.'"];
		$S = $SC.Interface.'.$interface.'; $S.isHorizontal = true; $S.countertype = \''.$type.'\'; $S.show("lrcounter_simplebox"); });</script>';
	}
	else
	{
		$rearrange_settings = unserialize(Configuration::get('rearrange_settings'));
		if (empty($rearrange_settings))
			$rearrange_settings = array('facebook', 'googleplus', 'twitter', 'linkedin', 'pinterest');
		$providers = implode('","', $rearrange_settings);
		if ($horizontal_theme == 2 || $horizontal_theme == 3)
			$interface = 'simpleimage';
		else
			$interface = 'horizontal';
		if ($horizontal_theme == 1 || $horizontal_theme == 3)
			$size = '16';
		else
			$size = '32';
		$loginradius_apikey = trim(Configuration::get('API_KEY'));
		$sharecounttype = (!empty($loginradius_apikey) ? ('$u.apikey="'.$loginradius_apikey.'";
		$u.sharecounttype='."'url'".';') : '$u.sharecounttype='."'url'".';');
		$share_script .= '<script type="text/javascript">LoginRadius.util.ready(function () { $i = $SS.Interface.'.$interface.';
		$SS.Providers.Top = ["'.$providers.'"];
		$u = LoginRadius.user_settings; '.$sharecounttype.' $i.size = '.$size.';$i.show("lrsharecontainer"); });</script>';
	}
	return $share_script;
}

/*
* Vertical Social Sharing Widget Script Code.
*/
function loginRadiusVerticalShareScript()
{
	$context = Context::getContext();
	$context->controller->addJS(__PS_BASE_URI__.'modules/sociallogin/js/sharinginterface.js');
	$share_script = '';
	$vertical_theme = Configuration::get('chooseverticalshare') ? Configuration::get('chooseverticalshare') : '6';
	if ($vertical_theme == 6 || $vertical_theme == 7)
	{
		$counter_list = unserialize(Configuration::get('socialshare_counter_list'));
		if (empty($counter_list))
			$counter_list = array('Pinterest Pin it', 'Facebook Like', 'Google+ Share', 'Twitter Tweet', 'Hybridshare');
		$providers = implode('","', $counter_list);
		if ($vertical_theme == 6)
			$type = 'vertical';
		else
			$type = 'horizontal';
		$share_script .= '<script type="text/javascript">LoginRadius.util.ready(function () { $SC.Providers.Selected = ["'.$providers.'"];
		$S = $SC.Interface.simple; $S.isHorizontal = false; $S.countertype = \''.$type.'\';';
		$choosesharepos = Configuration::get('choosesharepos');
		if ($choosesharepos == 0)
		{
			$position1 = 'top';
			$position2 = 'left';
		}
		else if ($choosesharepos == 1)
		{
			$position1 = 'top';
			$position2 = 'right';
		}
		else if ($choosesharepos == 2)
		{
			$position1 = 'bottom';
			$position2 = 'left';
		}
		else
		{
			$position1 = 'bottom';
			$position2 = 'right';
		}
		$offset = Configuration::get('verticalsharetopoffset');
		if (isset($offset) && trim($offset) != '' && is_numeric($offset))
			$share_script .= '$S.top = \''.trim($offset).'px\'; $S.'.$position2.' = \'0px\';$S.show("lrcounter_verticalsimplebox"); });</script>';
		else
			$share_script .= '$S.'.$position1.' = \'0px\'; $S.'.$position2.' = \'0px\';$S.show("lrcounter_verticalsimplebox"); });</script>';
	}
	else
	{
		$vertical_rearrange_settings = unserialize(Configuration::get('vertical_rearrange_settings'));
		if (empty($vertical_rearrange_settings))
			$vertical_rearrange_settings = array('facebook', 'googleplus', 'twitter', 'linkedin', 'pinterest');
		$providers = implode('","', $vertical_rearrange_settings);
		$interface = 'Simplefloat';
		if ($vertical_theme == 4)
			$size = '32';
		else
			$size = '16';
		$loginradius_apikey = trim(Configuration::get('API_KEY'));
		$sharecounttype = (!empty($loginradius_apikey) ? ('$u.apikey="'.$loginradius_apikey.'";
		$u.sharecounttype='."'url'".';') : '$u.sharecounttype='."'url'".';');
		$share_script .= '</script> <script type="text/javascript">LoginRadius.util.ready(function () { $i = $SS.Interface.'.$interface.';
		$SS.Providers.Top = ["'.$providers.'"]; $u = LoginRadius.user_settings; '.$sharecounttype.' $i.size = '.$size.';';
		$choosesharepos = Configuration::get('choosesharepos');
		if ($choosesharepos == 0)
		{
			$position1 = 'top';
			$position2 = 'left';
		}
		else if ($choosesharepos == 1)
		{
			$position1 = 'top';
			$position2 = 'right';
		}
		else if ($choosesharepos == 2)
		{
			$position1 = 'bottom';
			$position2 = 'left';
		}
		else
		{
			$position1 = 'bottom';
			$position2 = 'right';
		}
		$offset = Configuration::get('verticalsharetopoffset');
		if (isset($offset) && trim($offset) != '' && is_numeric($offset))
			$share_script .= '$i.top = \''.trim($offset).'px\'; $i.'.$position2.' = \'0px\';$i.show("lrshareverticalcontainer"); });</script>';
		else
			$share_script .= '$i.'.$position1.' = \'0px\'; $i.'.$position2.' = \'0px\';$i.show("lrshareverticalcontainer"); });</script>';
	}
	return $share_script;
}

/*
* Redirection after login.
*/
function redirectURL()
{
	$redirect = '';
	$loc = Configuration::get('LoginRadius_redirect');
	if ($loc == 'profile')
		$redirect = 'my-account.php';
	elseif ($loc == 'url')
	{
		$custom_url = Configuration::get('redirecturl');
		$redirect = !empty($custom_url) ? $custom_url : 'my-account.php';
	}
	else
	{
		if (Tools::getValue('back'))
		{
			if (_PS_VERSION_ >= 1.6)
			{
				$loc = $_SERVER['REQUEST_URI'];
				$redirect_location = explode('back=', $loc);
				$redirect = $redirect_location['1'];
			}
			else
				$redirect = Tools::getValue('back');
		}
		elseif (empty($redirect))
		{
			$http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'Off' && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://');
			$redirect = urldecode($http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		}
	}
	return $redirect;
}

/*
* Email verification link when user click on resend email buttuon.
*/
function loginRadiusResendEmailVerification($social_id)
{
	$module = new sociallogin();
	$getdata = Db::getInstance()->ExecuteS('SELECT * from '._DB_PREFIX_.'customer AS c INNER JOIN '._DB_PREFIX_."sociallogin
	AS sl ON sl.id_customer=c.id_customer  WHERE sl.provider_id='$social_id'");
	if ($getdata['0']['verified'] == 1)
	{
		$msg = $module->l('Email has been already verified. Now you can login using Social Login.', 'sociallogin_functions');
		popupVerify($msg);
	}
	else
	{
		$to = $getdata['0']['email'];
		$rand = slRandomChar();
		Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'sociallogin SET rand='.$rand." WHERE provider_id='$social_id'");
		$sub = $module->l('Verify your email id.', 'sociallogin_functions');
		$protocol_content = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
		$link = $protocol_content.$_SERVER['HTTP_HOST'].__PS_BASE_URI__."?SL_VERIFY_EMAIL=$rand&SL_PID=".$social_id.'';
		$msgg = $module->l('Please click on the following link or paste it in browser to verify your email: ', 'sociallogin_functions').$link;
		slEmail($to, $sub, $msgg, $social_id);
	}
}

/*
* Save the logged in user credentials in cookie.
*/
function loginRedirect($arr)
{
	$context = Context::getContext();
	$cookie = $context->cookie;
	$cookie->id_customer = $arr['id'];
	$cookie->customer_lastname = $arr['lname'];
	$cookie->customer_firstname = $arr['fname'];
	$cookie->logged = 1;
	$cookie->passwd = $arr['pass'];
	$cookie->email = $arr['email'];
	$cookie->loginradius_id = $arr['loginradius_id'];
	$cookie->lr_login = 'true';
	if ((empty($cookie->id_cart) || Cart::getNbProducts($cookie->id_cart) == 0))
		$cookie->id_cart = (int)Cart::lastNoneOrderedCart($cookie->id_customer);
	// OPC module compatibility
	$cart = $context->cart;
	$cart->id_address_delivery = 0;
	$cart->id_address_invoice = 0;
	$cart->update();
	$cookie->id_compare = isset($cookie->id_compare) ? $cookie->id_compare : CompareProduct::getIdCompareByIdCustomer($cookie->id_customer);
	Hook::exec('authentication');
	$redirect = redirectURL();
	Tools::redirectLink($redirect);
}

/*
* When user have Email address then check login functionaity
*/
function storeAndLogin($user_profile_data, $rand = '')
{
	$module = new sociallogin();
	$email = $user_profile_data['email'];
	$random_value = '';
	$verified = 1;
	if (!empty($rand) && $user_profile_data['send_verification_email'] == 'yes')
	{
		$random_value = $rand;
		$verified = 0;
	}
	if (!empty($user_profile_data['fname']) && !empty($user_profile_data['lname']))
		$username = $user_profile_data['fname'].' '.$user_profile_data['lname'];
	elseif (!empty($user_profile_data['fullname']))
		$username = $user_profile_data['fullname'];
	elseif (!empty($user_profile_data['profilename']))
		$username = $user_profile_data['profilename'];
	elseif (!empty($user_profile_data['nickname']))
		$username = $user_profile_data['nickname'];
	elseif (!empty($email))
	{
		$user_name = explode('@', $email);
		$username = $user_name[0];
	}
	else
		$username = $user_profile_data['id'];
	if ($user_profile_data['dob'])
	{
		$dob_arr = explode('/', $user_profile_data['dob']);
		$dob = $dob_arr[2].'-'.$dob_arr[0].'-'.$dob_arr[1];
	}
	$date_of_birth = (!empty($dob) && Validate::isBirthDate($dob) ? $dob : '');
	$password = Tools::passwdGen();
	$pass = Tools::encrypt($password);
	$fname = (!empty($user_profile_data['fname']) ? pSQL($user_profile_data['fname']) : pSQL($username));
	$fname = removeSpecial($fname);
	$lname = (!empty($user_profile_data['lname']) ? pSQL($user_profile_data['lname']) : pSQL($username));
	$lname = removeSpecial($lname);
	$newsletter = '0';
	$optin = '0';
	$gender = ((!empty($user_profile_data['gender'])
		&& (strpos($user_profile_data['gender'], 'f') !== false
			|| (trim($user_profile_data['gender']) == 'F'))) ? 2 : 1);
	$required_field_check = Db::getInstance()->ExecuteS('SELECT field_name FROM  '.pSQL(_DB_PREFIX_).'required_field');
	foreach ($required_field_check as $item)
	{
		if ($item['field_name'] == 'newsletter')
			$newsletter = '1';
		if ($item['field_name'] == 'optin')
			$optin = '1';
	}
	$customer = new CustomerCore();
	$customer->firstname = $fname;
	$customer->lastname = $lname;
	$customer->email = $email;
	$customer->id_gender = $gender;
	$customer->birthday = $date_of_birth;
	$customer->active = true;
	$customer->deleted = false;
	$customer->is_guest = false;
	$customer->passwd = $pass;
	$customer->newsletter = $newsletter;
	$customer->optin = $optin;
	if ($customer->add())
	{
		$insert_id = $customer->id;
		$tbl = pSQL(_DB_PREFIX_.'sociallogin');
		Db::getInstance()->Execute("DELETE FROM $tbl WHERE provider_id='".pSQL($user_profile_data['id'])."'");
		$query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`,`verified`,`rand`)
	values ('$insert_id','".pSQL($user_profile_data['id'])."','".pSQL($user_profile_data['provider'])."','".pSQL($verified)."','".pSQL($random_value)."') ";
		Db::getInstance()->Execute($query);
		//extra data from here later to complete
		if (Configuration::get('user_require_field') == '1')
			extraFields($user_profile_data, $insert_id, $fname, $lname);
		if (!empty($rand) && $user_profile_data['send_verification_email'] == 'yes')
		{
			$to = $email;
			$sub = $module->l('Verify your email id. ', 'sociallogin_functions');
			$protocol_content = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
			$link = $protocol_content.$_SERVER['HTTP_HOST'].__PS_BASE_URI__."?SL_VERIFY_EMAIL=$rand&SL_PID=".$user_profile_data['id'].'';
			$msg = $module->l('Please click on the following link or paste it in browser to verify your email: ', 'sociallogin_functions').$link;
			slEmail($to, $sub, $msg, $user_profile_data['id']);
		}
		else
		{
			$arr = array();
			$user = array();
			$arr['id'] = (string)$insert_id;
			$arr['lname'] = $lname;
			$arr['fname'] = $fname;
			$arr['pass'] = $pass;
			$arr['email'] = $email;
			$arr['loginradius_id'] = $user_profile_data['id'];
			$user['pass'] = $password;
			$user['fname'] = $arr['fname'];
			$user['lname'] = $arr['lname'];
			if (Configuration::get('SEND_REQ') == '1')
				adminEmail($arr);
			if (Configuration::get('user_notification') == '0')
				userNotificationEmail($arr['email'], $user);
			loginRedirect($arr);
		}
	}
	//error
	return false;
}

/*
* save the user data in cookie.
*/
function storeInCookie($user_profile_data)
{
	$context = Context::getContext();
	$cookie = $context->cookie;
	$cookie->login_radius_data = serialize($user_profile_data);
}

/*
* Show poup window for Email and Required fields.
*/
function popUpWindow($msg = '', $data = array())
{
$module = new sociallogin();
$style = 'style="padding:10px 11px 10px 30px;overflow-y:auto;height:auto;"';
$left = 'left:44%';
if (_PS_VERSION_ >= 1.6)
	$left = 'left:50%;';
$top_style = 'style=top:50%;'.$left.'';
$profilefield = unserialize(Configuration::get('profilefield'));
if (empty($profilefield))
	$profilefield[] = '3';
if (Configuration::get('user_require_field') == '1')
{
	$top_style_value = 50;
	$count_profile_field = count($profilefield);
	for ($i = 1; $i < $count_profile_field - 1; $i++)
		$top_style_value -= 5;
	$top_style = 'style=top:'.$top_style_value.'%;'.$left.'';
	$style = 'style="padding:10px 11px 10px 30px;"';
}
$profilefield = implode(';', $profilefield);
$context = Context::getContext();
$context->controller->addCSS(__PS_BASE_URI__.'modules/sociallogin/css/sociallogin_style.css');
$context->controller->addjquery();
$context->controller->addJS(__PS_BASE_URI__.'modules/sociallogin/js/popupjs.js');
$cookie = $context->cookie;
$cookie->sl_hidden = microtime();
?>
<div id="fade" class="LoginRadius_overlay">
<div id="popupouter" <?php echo $top_style; ?>>
<div id="popupinner" <?php echo $style; ?>>
<div id="textmatter"><strong>
		<?php
		if ($msg == '')
		{
			//echo "Please fill the following details to complete the registration";
			$show_msg = Configuration::get('POPUP_TITLE');
			echo $msg = (!empty($show_msg) ? $show_msg : $module->l('Please fill the following details to complete the registration', 'sociallogin_functions'));
		}
		else
			echo $msg;
		?>
	</strong></div>
<form method="post" name="validfrm" id="validfrm" action="" onsubmit="return popupvalidation();">
<?php
$html = '';
if (Configuration::get('user_require_field') == '1')
{
	if (strpos($profilefield, '1') !== false && (empty($data['fname']) || isset($data['firstname'])))
	{
		$html .= '<div>
			<span class="spantxt">'.$module->l('First Name', 'sociallogin_functions').'</span>
			<input type="text" name="SL_FNAME" id="SL_FNAME" placeholder="FirstName"
			value= "'.((Tools::getValue('SL_FNAME')) ? htmlspecialchars(Tools::getValue('SL_FNAME')) : '').'" class="inputtxt" />
			</div>';
	}
	if (strpos($profilefield, '2') !== false && (empty($data['lname']) || isset($data['lastname'])))
	{
		$html .= '<div>
			<span class="spantxt">'.$module->l('Last Name', 'sociallogin_functions').'</span>
			<input type="text" name="SL_LNAME" id="SL_LNAME" placeholder="LastName"
			value= "'.((Tools::getValue('SL_LNAME')) ? htmlspecialchars(Tools::getValue('SL_LNAME')) : '').'" class="inputtxt" />
			</div>';
	}
}
if (empty($data['email']) || $data['send_verification_email'] == 'yes')
{
	$width = '';
	if (Configuration::get('user_require_field') != '1' || (Configuration::get('user_require_field') == '1' && count(Configuration::get('profilefield')) == 0))
		$width = 'width:60px;';
	$html .= '<div><span class="spantxt" style='.$width.'>'.$module->l('Email', 'sociallogin_functions').'</span>
			<input type="text" name="SL_EMAIL" id="SL_EMAIL" placeholder="Email"
			value= "'.((Tools::getValue('SL_EMAIL')) ? htmlspecialchars(Tools::getValue('SL_EMAIL')) : '').'" class="inputtxt" />
			</div>';
}
if (Configuration::get('user_require_field') == '1')
{
	if (strpos($profilefield, '6') !== false)
	{
		$html .= '<div><span class="spantxt">'.$module->l('Address', 'sociallogin_functions').'</span>
			<input type="text" name="SL_ADDRESS" id="SL_ADDRESS" placeholder="Address"
			value= "'.((Tools::getValue('SL_ADDRESS')) ? htmlspecialchars(Tools::getValue('SL_ADDRESS')) : $data['address']).'" class="inputtxt" />
			</div>';
	}
	if (strpos($profilefield, '8') !== false)
	{
		$html .= '<div><span class="spantxt">'.$module->l('ZIP code', 'sociallogin_functions').'</span>
			<input type="text" name="SL_ZIP_CODE" id="SL_ZIP_CODE" placeholder="Zip Code"
			value= "'.((Tools::getValue('SL_ZIP_CODE')) ? htmlspecialchars(Tools::getValue('SL_ZIP_CODE')) : '').'" class="inputtxt" />
			</div>';
	}
	if (strpos($profilefield, '4') !== false)
	{
		$html .= '<div>
			<span class="spantxt">'.$module->l('City', 'sociallogin_functions').'</span><input type="text" name="SL_CITY" id="SL_CITY" placeholder="City"
			value= "'.((Tools::getValue('SL_CITY')) ? htmlspecialchars(Tools::getValue('SL_CITY')) : $data['city']).'" class="inputtxt" />
			</div>';
	}
	$countries = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'country c WHERE c.active =1');
	if (strpos($profilefield, '3') !== false)
	{
		if (is_array($countries) && !empty($countries))
		{
			$html .= '<div id="location-country-div">
					<span class="spantxt">'.$module->l('Country', 'sociallogin_functions').'</span>
					<select id="location-country" name="location_country" class="inputtxt"><option value="0">None</option>';
			foreach ($countries as $country)
			{
				$country_name = new Country($country['id_country']);
				$html .= '<option value="'.($country['iso_code']).'"'.((Tools::getValue('location_country'))
					&& (Tools::getValue('location_country') == $country['iso_code']) ? ' selected="selected"' : '').'>
					'.$country_name->name['1'].'</option>'."\n";
			}
			$html .= '</select></div>';
		}
	}
	$value = true;
	if (Tools::getValue('location_country') && strpos($profilefield, '3') !== false)
	{
		$country = new Country(Tools::getValue('location_country'));
		$value = $country->contains_states;
	}
	if (strpos($profilefield, '3') !== false && $value)
	{
		$html .= '<div id="location-state-div" style="display:none;">
		<input id="location-state" type="text" name="location-state" value="empty" />
		</div>';
	}
	elseif (strpos($profilefield, '3') !== false)
	{
		$country_id = Db::getInstance()->executeS('
			SELECT *
			FROM '._DB_PREFIX_.'country  c WHERE c.iso_code= "'.Tools::getValue('location_country').'"');
		$states = State::getStatesByIdCountry($country_id['0']['id_country']);
		if (is_array($states))
		{
			$style = '';
			if (empty($states))
				$style = 'style="display:none;"';
			$html .= '<div id="location-state-div" '.$style.'>
				<span class="spantxt">'.$module->l('State', 'sociallogin_functions').'</span>
				<select id="location-state" name="location-state" class="inputtxt">';
			if (empty($states))
				$html .= '<option value="empty">None</option>';
			foreach ($states as $state)
			{
				$state_name = new State($state['id_state']);
				$html .= '<option value="'.($state['iso_code']).'"'.(Tools::getValue('location-state')
					&& (Tools::getValue('location-state') == $state['iso_code']) ? ' selected="selected"' : '').'>'.$state_name->name.'</option>'."\n";
			}
			$html .= '</select></div>';
		}
	}
	if (strpos($profilefield, '5') !== false)
	{
		$html .= '<div><span class="spantxt">'.$module->l('Mobile Number', 'sociallogin_functions').'</span>
			<input type="text" name="SL_PHONE" id="SL_PHONE" placeholder="Mobile Number"
			value= "'.((Tools::getValue('SL_PHONE')) ? htmlspecialchars(Tools::getValue('SL_PHONE')) : $data['phonenumber']).'" class="inputtxt" />
			</div>';
	}


	if (strpos($profilefield, '7') !== false)
	{
		$html .= '<div><span class="spantxt">'.$module->l('Address Title').'</span><input type="text" name="SL_ADDRESS_ALIAS"
		id="SL_ADDRESS_ALIAS" placeholder="Please assign an address title for future reference"
		value= "'.((Tools::getValue('SL_ADDRESS_ALIAS')) ? htmlspecialchars(Tools::getValue('SL_ADDRESS_ALIAS')) : '').'" class="inputtxt" />
		</div>';
	}
}
if ($html == '')
	return 'noshowpopup';
$html .= '<div><input type="hidden" name="hidden_val" value="'.$cookie->sl_hidden.'" />
	<input type="submit" id="LoginRadius" name="LoginRadius" value="'.$module->l('Submit', 'sociallogin_functions').'"
	class="inputbutton">
	<input type="button" value="'.$module->l('Cancel', 'sociallogin_functions').'"
	class="inputbutton" onclick="window.location.href=window.location.href;" />
	</div></div>
	</form>
	</div>
	</div>
	</div>';
echo $html;
}
/*
 * Verify email-address.
 */
function verifyEmail()
{
	$module = new sociallogin();
	$tbl = pSQL(_DB_PREFIX_.'sociallogin');
	$pid = pSQL(Tools::getValue('SL_PID'));
	$rand = pSQL(Tools::getValue('SL_VERIFY_EMAIL'));
	$db = Db::getInstance()->ExecuteS('SELECT * FROM  '.pSQL(_DB_PREFIX_)."sociallogin  WHERE rand='".pSQL($rand)."' and provider_id='".pSQL($pid)."' and verified='0'");
	$num = (!empty($db['0']['id_customer']) ? $db['0']['id_customer'] : '');
	$provider_name = (!empty($db['0']['Provider_name']) ? pSQL($db['0']['Provider_name']) : '');
	if ($num < 1)
		return;
	Db::getInstance()->Execute("UPDATE $tbl SET verified='1' , rand='' WHERE rand='".pSQL($rand)."' and provider_id='".pSQL($pid)."'");
	Db::getInstance()->Execute("UPDATE $tbl SET rand='' WHERE Provider_name='".pSQL($provider_name)."' and id_customer='".pSQL($num)."'");
	$msg = $module->l('Email is verified. Now you can login using Social Login.', 'sociallogin_functions');
	popupVerify($msg);
}
/*
 * send credenntials to customer.
 */
function userNotificationEmail($email, $user)
{
	$module = new sociallogin();
	$sub = $module->l('Thank You For Registration', 'sociallogin_functions');
	$vars = array('{firstname}' => $user['fname'], '{lastname}' => $user['lname'], '{email}' => $email, '{passwd}' => $user['pass']);
	$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	Mail::Send($id_lang, 'account', $sub, $vars, $email);
}

/*
 * Notify admin when new user register.
 */
function adminEmail($arr)
{
	$email = $arr['email'];
	$module = new sociallogin();
	$sub = $module->l('New User Registration', 'sociallogin_functions');
	$msg = $module->l('New User Registered to your site<br/> E-mail address: ', 'sociallogin_functions');
	$msg .= $email;
	if (_PS_VERSION_ >= 1.6)
	{
		$vars = array('{name}' => 'admin', '{message}' => $msg, '{subject}' => $sub);
		$mail_format = 'lrsociallogin_account';
	}
	else
	{
		$vars = array('{email}' => $email, '{message}' => $msg);
		$mail_format = 'contact';
	}
	$db = Db::getInstance()->ExecuteS('SELECT * FROM  '.pSQL(_DB_PREFIX_).'employee  WHERE id_profile=1 ');
	$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	foreach ($db as $row)
	{
		$find_email = $row['email'];
		Mail::Send($id_lang, $mail_format, $sub, $vars, $find_email);
	}
}

/*
 * Send mail.
 */
function slEmail($to, $sub, $msg, $social_id)
{
	$module = new sociallogin();
	$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	if (_PS_VERSION_ >= 1.6)
	{
		$vars = array('{name}' => 'customer', '{message}' => $msg, '{subject}' => $sub);
		Mail::Send($id_lang, 'lrsociallogin_account', $sub, $vars, $to);
	}
	else
	{
		$vars = array('{email}' => $to, '{message}' => $msg);
		Mail::Send($id_lang, 'contact', $sub, $vars, $to);
	}
	$msgg = $module->l('Your confirmation link has been sent to your email address.
	Please verify your email by clicking on confirmation link.', 'sociallogin_functions');
	popupVerify($msgg, $social_id);
}
/*
 * Get random character.
 */
function slRandomChar()
{
	$char = '';
	for ($i = 0; $i < 20; $i++)
		$char .= rand(0, 9);
	return ($char);
}
/*
 * Save data after POPup call.
 */
function slDataSave($lrdata = array())
{
	$module = new sociallogin();
	$context = Context::getContext();
	$cookie = $context->cookie;
	$provider_id = pSQL($lrdata['id']);
	$provider_name = pSQL($lrdata['provider']);
	if (!Context:: getContext()->customer->isLogged())
	{
		$email = pSQL($lrdata['email']);
		$query = 'SELECT c.id_customer from '._DB_PREFIX_.'customer AS c INNER JOIN '._DB_PREFIX_.'sociallogin AS sl ON sl.id_customer=c.id_customer
		WHERE c.email="'.pSQL($email).'"';
		$query = Db::getInstance()->ExecuteS($query);
		if (!empty($query['0']['id_customer']))
		{
			$error_message = Configuration::get('ERROR_MESSAGE');
			$error_msg = "<p style ='color:red;'>".$error_message.'</p><p
								style="color: red;margin-bottom: -10px; font-size: 10px;">Email-adddress has already used.</p>';
			$lrdata['email'] = '';
			popUpWindow($error_msg, $lrdata);
			return;
		}
		else
		{
			$cookie->login_radius_data = '';
			$cookie->sl_hidden = '';
			$query1 = 'SELECT * FROM '._DB_PREFIX_."customer  WHERE email='".pSQL($email)."'";
			$query1 = Db::getInstance()->ExecuteS($query1);
			$num = (!empty($query1['0']['id_customer']) ? $query1['0']['id_customer'] : '');
			if (!empty($num))
			{
				$rand = pSQL(slRandomChar());
				$tbl = pSQL(_DB_PREFIX_.'sociallogin');
				$query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`,`rand`,`verified`)
				values ('".$num."','".pSQL($provider_id)."','".pSQL($provider_name)."','".pSQL($rand)."','0') ";
				Db::getInstance()->Execute($query);
				$sub = $module->l('Verify your email id. ', 'sociallogin_functions');
				$protocol_content = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
				$link = $protocol_content.$_SERVER['HTTP_HOST'].__PS_BASE_URI__."?SL_VERIFY_EMAIL=$rand&SL_PID=$provider_id";
				$msg = $module->l('Please click on the following link or paste it in browser to verify your email: ', 'sociallogin_functions').$link;
				$sub = $module->l('Verify your email id.', 'sociallogin_functions');
				slEmail($email, $sub, $msg, $provider_id);
				return;
			}
			else
			{
				$rand = pSQL(slRandomChar());
				storeAndLogin($lrdata, $rand);
			}
		}
	}
}
/*
 * Show Error Message.
 */
function popupVerify($msg, $social_id = '')
{
	$module = new sociallogin();
	$context = Context::getContext();
	$context->controller->addCSS(__PS_BASE_URI__.'modules/sociallogin/css/sociallogin_style.css');
	$left = 'left:44%';
	if (_PS_VERSION_ >= 1.6)
		$left = 'left:50%';
	$html = '<div id="fade" class="LoginRadius_overlay">
	<div id="popupouter" style="top:50%;'.$left.'">
	<div id="popupinner">
	<div id="textmatter">'.$msg.'';
	$html .= '</div><form method="post" name="validform_verify" action="">';
	$html .= '
	<input type="button" value="'.$module->l('Ok', 'sociallogin_functions').'"
	onclick="window.location.href=window.location.href;" class="inputbutton" />';
	if (!empty($social_id))
	{
		$html .= '
		<input type="hidden" value="'.$social_id.'" name="social_id_value" class="inputbutton" />
		<input type="submit" value="'.$module->l('Resend Email Verification', 'sociallogin_functions').'"
		name="resend_email_verification" class="inputbutton" />';
	}
	$html .= '</form></div></div></div></div>';
	echo $html;
}
/*
 * Insert popup optional fields.
 */
function extraFields($data, $insert_id, $fname, $lname, $update = '')
{
	$str = '';
	if (!empty($data['country']))
	{
		$country = $data['country'];
		$id = pSQL(getIdByCountryISO($country));
		if (!empty($id))
			$str .= "id_country='$id',";
	}
	if (!empty($data['country']) && empty($id))
	{
		$country = $data['country'];
		$id = pSQL(getIdByCountryName($country));
		if (!empty($id))
			$str .= "id_country='$id',";
	}
	elseif (empty($id))
	{
		$id = (int)Configuration::get('PS_COUNTRY_DEFAULT');
		if (!empty($id))
			$str .= "id_country='$id',";
	}
	if (isset($data['state']) && $data['state'] != 'empty' && !empty($data['state']))
	{
		$state = $data['state'];
		$iso = pSQL(getIsoByState($state));
		if (!empty($iso))
			$str .= "id_state='$iso',";
	}
	if (isset($data['city']) && !empty($data['city']))
	{
		$city = pSQL($data['city']);
		$str .= "city='$city',";
	}
	if (isset($data['zipcode']) && !empty($data['zipcode']))
	{
		$zip = trim(pSQL($data['zipcode']));
		$str .= "postcode='$zip',";
	}
	if (isset($data['address']) && !empty($data['address']))
	{
		$address = pSQL($data['address']);
		$str .= "address1='$address',";
	}
	if (isset($data['phonenumber']) && !empty($data['phonenumber']))
	{
		$phone = pSQL($data['phonenumber']);
		$str .= "phone_mobile='$phone',";
	}
	if (isset($data['addressalias']) && !empty($data['addressalias']))
	{
		$add_alias = pSQL($data['addressalias']);
		$str .= "alias='$add_alias',";
	}
	$tbl = _DB_PREFIX_.'address';
	$date = date('y-m-d h:i:s');
	if ($update == 'yes')
	{
		if (!empty($fname))
			$str .= "firstname='$fname',";
		if (!empty($lname))
			$str .= "lastname='$lname',";
		$q = "UPDATE $tbl SET ".$str." date_upd='$date' WHERE id_customer='$insert_id'";
		$q = Db::getInstance()->Execute($q);
	}
	else
	{
		$str .= "date_add='$date',date_upd='$date',";
		$fname = pSQL($fname);
		$lname = pSQL($lname);
		$q = "INSERT into $tbl SET ".$str." id_customer='$insert_id', lastname='$lname',firstname='$fname' ";
		$q = Db::getInstance()->Execute($q);
	}
}
/*
 * Get country name by Counter ISo=code.
 */
function getIdByCountryISO($iso_value)
{
	if (!empty($iso_value))
	{
		$tbl = _DB_PREFIX_.'country';
		$field = 'iso_code';
		if (isset($iso_value->Code))
			$iso_value = $iso_value->Code;
		if (isset($iso_value) && !is_array($iso_value))
		{
			$iso_value = pSQL(trim($iso_value));
			$q = "SELECT * from $tbl WHERE $field='$iso_value'";
			$q = Db::getInstance()->ExecuteS($q);
			$iso_value = '';
			$iso_value = (isset($q[0]['id_country']) ? $q[0]['id_country'] : '');
			return ($iso_value);
		}
		return '';
	}
}
/*
 * Get Counter name by ID.
 */
function getIdByCountryName($country)
{
	if (!empty($country))
	{
		if (isset($country->Name))
			$country = $country->Name;
		if (is_string($country))
		{
			$iso = '';
			$tbl = _DB_PREFIX_.'country_lang';
			$country = pSQL(trim($country));
			$q = "SELECT * from $tbl WHERE name='$country'";
			$q = Db::getInstance()->ExecuteS($q);
			if (!empty($q))
				$iso = $q[0]['id_country'];
			return $iso;
		}
	}
	return '';
}
/*
 * Get State. from ISO-code.
 */
function getIsoByState($state)
{
	if (!empty($state) && is_string($state))
	{
		$tbl = _DB_PREFIX_.'state';
		$q = "SELECT * from $tbl WHERE  iso_code ='$state'";
		$q = Db::getInstance()->ExecuteS($q);
		if (!empty($q))
		{
			$id = $q[0]['id_state'];
			return ($id);
		}
	}
	return '';
}
/*
 * remove special character from name.
 */
function removeSpecial($field)
{
	$in_str = str_replace(array('<', '>', '&', '{', '}', '*', '/', '(', '[', ']', '@', '!', ')', '&', '*', '#', '$', '%', '^', '|', '?', '+', '=',
		'"', ','), array(''), $field);
	$cur_encoding = mb_detect_encoding($in_str);
	if ($cur_encoding == 'UTF-8' && mb_check_encoding($in_str, 'UTF-8'))
		$name = $in_str;
	else
		$name = utf8_encode($in_str);
	if (!Validate::isName($name))
	{
		$len = Tools::strlen($name);
		$return_val = '';
		for ($i = 0; $i < $len; $i++)
		{
			if (ctype_alpha($name[$i]))
				$return_val .= $name[$i];
		}
		$name = $return_val;
		if (empty($name))
		{
			$letters = range('a', 'z');
			for ($i = 0; $i < 5; $i++)
				$name .= $letters[rand(0, 26)];
		}
	}
	return $name;
}

/*
* Retrieve random Email address.
*/
function emailRand($lrdata)
{
	switch ($lrdata['provider'])
	{
		case 'twitter':
			$email = $lrdata['id'].'@'.$lrdata['provider'].'.com';
			break;
		default:
			$email_id = Tools::substr($lrdata['id'], 7);
			$email_id2 = str_replace('/', '_', $email_id);
			$email = str_replace('.', '_', $email_id2).'@'.$lrdata['provider'].'.com';
			break;
	}
	return $email;
}

/*
* Map user profule data from LoginRadius profile data according to Prestashop.
*/
function loginRadiusMappingProfileData($user_profile)
{
	$lrdata = array();
	$lrdata['fullname'] = (!empty($user_profile->FullName) ? trim($user_profile->FullName) : '');
	$lrdata['profilename'] = (!empty($user_profile->ProfileName) ? trim($user_profile->ProfileName) : '');
	$lrdata['nickname'] = (!empty($user_profile->NickName) ? trim($user_profile->NickName) : '');
	$lrdata['fname'] = (!empty($user_profile->FirstName) ? trim($user_profile->FirstName) : '');
	$lrdata['lname'] = (!empty($user_profile->LastName) ? trim($user_profile->LastName) : '');
	$lrdata['id'] = (!empty($user_profile->ID) ? $user_profile->ID : '');
	$lrdata['provider'] = (!empty($user_profile->Provider) ? $user_profile->Provider : '');
	$lrdata['email'] = (count($user_profile->Email) > 0 ? $user_profile->Email[0]->Value : '');
	$lrdata['thumbnail'] = (!empty($user_profile->ImageUrl) ? trim($user_profile->ImageUrl) : '');
	if (empty($lrdata['thumbnail']) && $lrdata['provider'] == 'facebook')
		$lrdata['thumbnail'] = 'http://graph.facebook.com/'.$lrdata['id'].'/picture?type=square';
	$lrdata['dob'] = (!empty($user_profile->BirthDate) ? $user_profile->BirthDate : '');
	$lrdata['gender'] = (!empty($user_profile->Gender) ? $user_profile->Gender : '');
	$lrdata['company'] = (!empty($user_profile->Positions[1]->Company->Name) ? $user_profile->Positions[1]->Company->Name : '');
	if (empty($lrdata['company']))
		$lrdata['company'] = (!empty($user_profile->Industry) ? $user_profile->Industry : '');
	$lrdata['hometown'] = (!empty($user_profile->HomeTown) ? $user_profile->HomeTown : '');
	$lrdata['aboutme'] = (!empty($user_profile->About) ? $user_profile->About : '');
	$lrdata['website'] = (!empty($user_profile->ProfileUrl) ? $user_profile->ProfileUrl : '');
	$lrdata['state'] = (!empty($user_profile->State) ? $user_profile->State : '');
	$lrdata['city'] = (!empty($user_profile->City) ? $user_profile->City : '');
	if (empty($lrdata['city']) || $lrdata['city'] == 'unknown')
		$lrdata['city'] = (!empty($user_profile->LocalCity) && $user_profile->LocalCity != 'unknown' ? $user_profile->LocalCity : '');
	$lrdata['country'] = (!empty($user_profile->Country) ? $user_profile->Country : '');
	if (empty($lrdata['country']))
		$lrdata['country'] = (!empty($user_profile->LocalCountry) ? $user_profile->LocalCountry : '');
	$lrdata['phonenumber'] = (!empty($user_profile->PhoneNumbers['0']->PhoneNumber) ? $user_profile->PhoneNumbers['0']->PhoneNumber : '');
	$lrdata['address'] = (!empty($user_profile->Addresses['0']->Address1) ? $user_profile->Addresses['0']->Address1 : '');
	$lrdata['zipcode'] = (!empty($user_profile->Addresses['0']->PostalCode) ? $user_profile->Addresses['0']->PostalCode : '');
	return $lrdata;
}

?>
