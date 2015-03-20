<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('textbanners.php');

$context = Context::getContext();
$text_banners = new TextBanners();
$banners = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $text_banners->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'updatebannersPosition' && Tools::getValue('banners'))
{

	$banners = Tools::getValue('banners');

	foreach ($banners as $position => $id_banner)
	{
		$res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'textbanners_banners` SET `position` = '.(int)$position.'
			WHERE `id_textbanners_banners` = '.(int)$id_banner
		);

	}

	$text_banners->clearCache();
}

