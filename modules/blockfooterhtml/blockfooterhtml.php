<?php
/*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class Blockfooterhtml extends Module
{
	public function __construct()
	{
		$this->name = 'blockfooterhtml';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Block HTML in footer');
		$this->description = $this->l('Add html block in footer');
	}
	
	public function install()
	{
		$this->_clearCache('blockfooterhtml.tpl');
		return (parent::install() 

			&& Configuration::updateValue('footerhtml_val', '')
			&& $this->registerHook('header') && $this->registerHook('footer'));
	}
	
	public function uninstall()
	{
		$this->_clearCache('blockfooterhtml.tpl');
		//Delete configuration			
		return (Configuration::deleteByName('footerhtml_val') && parent::uninstall());
	}
	
	public function getContent()
	{
		$output = '';
		// If we try to update the settings
		if (isset($_POST['submitModule']))
		{	

			Configuration::updateValue('footerhtml_val', ((isset($_POST['footerhtml_val']) && $_POST['footerhtml_val'] != '') ? $_POST['footerhtml_val'] : ''),  true);
			$output .= $this->displayConfirmation($this->l('Configuration updated'));
		}
		
		return $output.$this->renderForm();
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'textarea',
						'label' => $this->l('HTML CODE'),
						'name' => 'footerhtml_val',
						'desc' => $this->l('Put your own html code. Usefull for example for tracking codes;')
					),
				),
			'submit' => array(
				'title' => $this->l('Save'))
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'footerhtml_val' => Tools::getValue('footerhtml_val', Configuration::get('footerhtml_val'))
		);
	}

	public function hookHeader()
	{
		$this->context->controller->addCSS($this->_path.'blockfooterhtml.css', 'all');
	}
	
	public function hookFooter($params)
	{
		if (!$this->isCached('blockfooterhtml.tpl', $this->getCacheId()))
		{	
			global $smarty;

			$smarty->assign(array(
				'footerhtml_val' => Configuration::get('footerhtml_val')
				));

		}
		return $this->display(__FILE__, 'blockfooterhtml.tpl', $this->getCacheId());
		
	}
}
?>
