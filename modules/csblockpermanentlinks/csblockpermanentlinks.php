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

if (!defined('_PS_VERSION_'))
	exit;

class CSBlockPermanentLinks extends Module
{
	public function __construct()
	{
		$this->name = 'csblockpermanentlinks';
		$this->tab = 'Orther Modules';
		$this->version = 0.1;
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		parent::__construct();
		
		$this->displayName = $this->l('CS Permanent links block');
		$this->description = $this->l('Adds a block that displays permanent links such as sitemap, contact, etc...');
	}

	public function install()
	{
			return (parent::install() && $this->registerHook('topleft') && $this->registerHook('header'));
	}

	/**
	* Returns module content for header
	*
	* @param array $params Parameters
	* @return string Content
	*/
	public function hookTopLeft($params)
	{
		return $this->display(__FILE__, 'blockpermanentlinks-header.tpl');
	}

	/**
	* Returns module content for left column
	*
	* @param array $params Parameters
	* @return string Content
	*/
	public function hookLeftColumn($params)
	{
		return $this->display(__FILE__, 'blockpermanentlinks.tpl');
	}

	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	public function hookFooter($params)
	{
		return $this->display(__FILE__, 'blockpermanentlinks-footer.tpl');
	}

	public function hookHeader($params)
	{
		global $smarty;
		$smarty->assign(array(
			'HOOK_TOP_LEFT' => Hook::Exec('topleft')
		));
		
		$this->context->controller->addCSS(($this->_path).'blockpermanentlinks.css', 'all');
	}
}


