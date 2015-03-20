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
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class Facebookslide extends Module
{
	public function __construct()
	{
		$this->name = 'facebookslide';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Facebook slide');
		$this->description = $this->l('Show facebook likebox');
	}
	
	public function install()
	{
		$this->_clearCache('facebookslide.tpl');
		return (parent::install() AND Configuration::updateValue('fbmod_faces', '1') && Configuration::updateValue('fbmod_stream', '1')   && Configuration::updateValue('fbmod_position', '1')  && Configuration::updateValue('fbmod_link', 'https://www.facebook.com/prestashop') 
			&& $this->registerHook('displayHeader') && $this->registerHook('freeFblock'));
	}
	
	public function uninstall()
	{
		//Delete configuration		
		$this->_clearCache('facebookslide.tpl');	
		return (Configuration::deleteByName('fbmod_faces') AND Configuration::deleteByName('fbmod_stream') AND Configuration::deleteByName('fbmod_link') AND Configuration::deleteByName('fbmod_position') AND parent::uninstall());
	}
	
	public function getContent()
	{
		// If we try to update the settings
		$output = '';
		if (isset($_POST['submitModule']))
		{	
			Configuration::updateValue('fbmod_link', $_POST['fbmod_link']);
			Configuration::updateValue('fbmod_faces', $_POST['fbmod_faces']);
			Configuration::updateValue('fbmod_stream', $_POST['fbmod_stream']);		
			Configuration::updateValue('fbmod_position', $_POST['fbmod_position']);	
			$output .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('facebookslide.tpl');
		}
		
		$output .= $this->renderForm();

		return $output;
	}


	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Menu Top Link'),
					'icon' => 'icon-link'
				),
				'input' => array(
					
					array(
						'type' => 'radio',
						'label' => $this->l('Position'),
						'name' => 'fbmod_position',
						'is_bool'   => true,
						'values'    => array(                                 // $values contains the data itself.
  							array(
      						'id'    => 'active_on',                           // The content of the 'id' attribute of the <input> tag, and of the 'for' attribute for the <label> tag.
      						'value' => 1,                                     // The content of the 'value' attribute of the <input> tag.   
      						'label' => $this->l('Left')                    // The <label> for this radio button.
    						),
    						array(
      						'id'    => 'active_of',                           // The content of the 'id' attribute of the <input> tag, and of the 'for' attribute for the <label> tag.
      						'value' => 0,                                     // The content of the 'value' attribute of the <input> tag.   
      						'label' => $this->l('Right')                    // The <label> for this radio button.
    						),
  						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Facebook URL:'),
						'name' => 'fbmod_link',
						'desc' => $this->l('Example: https://www.facebook.com/prestashop'),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show fans faces'),
						'name' => 'fbmod_faces',
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
						'label' => $this->l('Show stream'),
						'name' => 'fbmod_stream',
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
				),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
				)
			),
		);
		
		if (Shop::isFeatureActive())
			$fields_form['form']['description'] = $this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops'));
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
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
			'id_language' => $this->context->language->id,
		);
		return $helper->generateForm(array($fields_form));
	}

public function getConfigFieldsValues()
	{
		return array(
			'fbmod_position' => Tools::getValue('fbmod_position', Configuration::get('fbmod_position')),
			'fbmod_link' => Tools::getValue('fbmod_link', Configuration::get('fbmod_link')),
			'fbmod_faces' => Tools::getValue('fbmod_faces', Configuration::get('fbmod_faces')),
			'fbmod_stream' => Tools::getValue('fbmod_stream', Configuration::get('fbmod_stream')),
		);
	}
	
	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'facebookslide.css', 'all');
		$this->context->controller->addJS(($this->_path).'facebookslide.js');
	}


	public function hookfreeFblock()
	{
		global $smarty;

		if (!$this->isCached('facebookslide.tpl', $this->getCacheId()))
		{

			$smarty->assign(array(
				'fbmod_link' => Configuration::get('fbmod_link'),
				'fbmod_stream' => Configuration::get('fbmod_stream'),
				'fbmod_faces' => Configuration::get('fbmod_faces'),
				'fbmod_position' => Configuration::get('fbmod_position'),
				));
		}
		return $this->display(__FILE__, 'facebookslide.tpl', $this->getCacheId());
	}
}
?>
