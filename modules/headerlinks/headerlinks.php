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
*  @version  Release: $Revision: 7077 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class HeaderLinks extends Module
{
	/* @var boolean error */
	protected $error = false;
	
	public function __construct()
	{
		$this->name = 'headerlinks';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this -> author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->bootstrap = true;

	 	parent::__construct();

		$this->displayName = $this->l('Add links block in header');
		$this->description = $this->l('Adds a block with additional links.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete all your links ?');
	}
	
	public function install()
	{
		$this->_clearCache('headerlinks.tpl');
		if (!parent::install() ||
			!$this->registerHook('displayNav') || !$this->registerHook('header') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'headerlinks (
			`id_blocklink` int(2) NOT NULL AUTO_INCREMENT, 
			`new_window` TINYINT(1) NOT NULL,
			PRIMARY KEY(`id_blocklink`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'headerlinks_shop (
			`id_blocklink` int(2) NOT NULL AUTO_INCREMENT, 
			`id_shop` int(2) NOT NULL,
			PRIMARY KEY(`id_blocklink`, `id_shop`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'headerlinks_lang (
			`id_blocklink` int(2) NOT NULL,
			`id_lang` int(2) NOT NULL,
			`text` varchar(64) NOT NULL,
			`url` varchar(255) NOT NULL,
			PRIMARY KEY(`id_blocklink`, `id_lang`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Configuration::updateValue('PS_headerlinks_contact', 1) ||
			!Configuration::updateValue('PS_headerlinks_sitemap', 1) ||
			!Configuration::updateValue('PS_headerlinks_TITLE', array('1' => 'Block link', '2' => 'Bloc lien')))
			return false;
		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('headerlinks.tpl');
		if (!parent::uninstall() ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'headerlinks') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'headerlinks_lang') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'headerlinks_shop') ||
			!Configuration::deleteByName('PS_headerlinks_TITLE') ||
			!Configuration::deleteByName('PS_headerlinks_contact') ||
			!Configuration::deleteByName('PS_headerlinks_sitemap'))
			return false;
		return true;
	}
	
	public function hookDisplayNav($params)
	{
		
		if (!$this->isCached('headerlinks.tpl', $this->getCacheId()))
		{
		$links = $this->getLinks();
		
		$this->smarty->assign(array(
			'headerlinks_links' => $links,
			'showsitemaplink' => Configuration::get('PS_headerlinks_sitemap'),
			'showcontactlink' => Configuration::get('PS_headerlinks_contact'),
			'title' => Configuration::get('PS_headerlinks_TITLE', $this->context->language->id),
			'url' => 'url_'.$this->context->language->id,
			'lang' => 'text_'.$this->context->language->id
		));
	}
		return $this->display(__FILE__, 'headerlinks.tpl', $this->getCacheId());
	}
	

	public function getLinks()
	{
		$result = array();
		// Get id and url

		$sql = 'SELECT b.`id_blocklink`, b.`new_window`
				FROM `'._DB_PREFIX_.'headerlinks` b';
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_ALL)
			$sql .= ' JOIN `'._DB_PREFIX_.'headerlinks_shop` bs ON b.`id_blocklink` = bs.`id_blocklink` AND bs.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).') ';
		$sql .= (int)Configuration::get('PS_headerlinks_ORDERWAY') == 1 ? ' ORDER BY `id_blocklink` DESC' : '';

		if (!$links = Db::getInstance()->executeS($sql))
			return false;
		$i = 0;
		foreach ($links as $link)
		{
			$result[$i]['id'] = $link['id_blocklink'];
			$result[$i]['newWindow'] = $link['new_window'];
			// Get multilingual text
			if (!$texts = Db::getInstance()->executeS('SELECT `id_lang`, `text`, `url` 
																	FROM '._DB_PREFIX_.'headerlinks_lang 
																	WHERE `id_blocklink`='.(int)$link['id_blocklink']))
				return false;
			foreach ($texts as $text)
			{
				$result[$i]['text_'.$text['id_lang']] = $text['text'];
				$result[$i]['url_'.$text['id_lang']] = $text['url'];
				}
			$i++;
		}
		return $result;
	}
	
	public function addLink()
	{
		if (!($languages = Language::getLanguages(true)))
			return false;
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');

		if ($id_link = Tools::getValue('id_link'))
		{
			if (!Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'headerlinks SET `new_window` = '.(isset($_POST['newWindow']) ? 1 : 0).' WHERE `id_blocklink` = '.(int)$id_link))
				return false;
			if (!Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'headerlinks_lang WHERE `id_blocklink` = '.(int)$id_link))
				return false;
				
			foreach ($languages as $language)
			{
				if (!empty($_POST['text_'.$language['id_lang']]) && !empty($_POST['url_'.$language['id_lang']]))
		 	 	{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'headerlinks_lang VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$language['id_lang']]).'\', \''.pSQL($_POST['url_'.$language['id_lang']]).'\')'))
						return false;
		 	 	}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'headerlinks_lang VALUES ('.(int)$id_link.', '.$language['id_lang'].', \''.pSQL($_POST['text_'.$id_lang_default]).'\', \''.pSQL($_POST['url_'.$id_lang_default]).'\')'))
						return false;
		}}
		else
		{
			if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'headerlinks 
														VALUES (NULL, '.((isset($_POST['newWindow']) && $_POST['newWindow']) == 'on' ? 1 : 0).')') ||
														!$id_link = Db::getInstance()->Insert_ID())
				return false;

			foreach ($languages as $language)
			{
				if (!empty($_POST['text_'.$language['id_lang']]) && !empty($_POST['url_'.$language['id_lang']]))
				{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'headerlinks_lang 
																VALUES ('.(int)$id_link.', '.(int)$language['id_lang'].', \''.pSQL($_POST['text_'.$language['id_lang']]).'\', \''.pSQL($_POST['url_'.$language['id_lang']]).'\')'))
						return false;
				}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'headerlinks_lang VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$id_lang_default]).'\', \''.pSQL($_POST['url_'.$id_lang_default]).'\')'))
						return false;
		}
		}

		Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'headerlinks_shop WHERE id_blocklink='.(int)$id_link);

		if (!Shop::isFeatureActive())
		{
			Db::getInstance()->insert('headerlinks_shop', array(
				'id_blocklink' => (int)$id_link,
				'id_shop' => (int)Context::getContext()->shop->id,
			));
		}
		else
		{
			$assos_shop = Tools::getValue('checkBoxShopAsso_configuration');
			if (empty($assos_shop))
				return false;
			foreach ($assos_shop as $id_shop => $row)
					Db::getInstance()->insert('headerlinks_shop', array(
						'id_blocklink' => (int)$id_link,
						'id_shop' => (int)$id_shop,
					));
		}
		return true;
	}

	public function deleteLink()
	{
		return (Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'headerlinks WHERE `id_blocklink` = '.(int)$_GET['id']) &&
					Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'headerlinks_shop WHERE `id_blocklink` = '.(int)$_GET['id']) &&
					Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'headerlinks_lang WHERE `id_blocklink` = '.(int)$_GET['id']));
	}

	public function updateTitle()
	{
		$languages = Language::getLanguages();
		$result = array();
		foreach ($languages as $language)
			$result[$language['id_lang']] = $_POST['title_'.$language['id_lang']];
		return Configuration::updateValue('PS_headerlinks_TITLE', $result);
	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';
	

		// Add a link

		if (Tools::isSubmit('submitLinkAdd'))
     	{
			if (empty($_POST['text_'.Configuration::get('PS_LANG_DEFAULT')]) || empty($_POST['url_'.Configuration::get('PS_LANG_DEFAULT')]))
				$this->_html .= $this->displayError($this->l('You must fill in all fields'));
			elseif (!Validate::isUrl(str_replace('http://', '', $_POST['url_'.Configuration::get('PS_LANG_DEFAULT')])))
				$this->_html .= $this->displayError($this->l('Bad URL'));
			else
				if ($this->addLink())
	     	  		$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
				else
					$this->_html .= $this->displayError($this->l('An error occurred during link creation.'));
				$this->_clearCache('headerlinks.tpl');
     	}
		// Update the block title
		elseif (Tools::isSubmit('submitTitle'))
		{
		$this->_clearCache('headerlinks.tpl');
	
			if (!Validate::isGenericName($_POST['title_'.Configuration::get('PS_LANG_DEFAULT')]))
				$this->_html .= $this->displayError($this->l('The \'title\' field is invalid'));
			elseif (!$this->updateTitle())
				$this->_html .= $this->displayError($this->l('An error occurred during title updating.'));
			else
			{
				Configuration::updateValue('PS_headerlinks_contact', (int)(Tools::getValue("contactlink")));
				Configuration::updateValue('PS_headerlinks_sitemap', (int)(Tools::getValue("sitemaplink")));
				$this->_html .= $this->displayConfirmation($this->l('The block title has been updated.'));
				
			}
		}
		// Delete a link
		elseif (Tools::isSubmit('deleteheaderlinks') && Tools::getValue('id'))
		{
			$this->_clearCache('headerlinks.tpl');
			if (!is_numeric($_GET['id']) || !$this->deleteLink())
			 	$this->_html .= $this->displayError($this->l('An error occurred during link deletion.'));
			else
			 	$this->_html .= $this->displayConfirmation($this->l('The link has been deleted.'));
		}

		if (isset($_POST['submitOrderWay']))
		{
			$this->_clearCache('headerlinks.tpl');
			if (Configuration::updateValue('PS_headerlinks_ORDERWAY', (int)(Tools::getValue('orderWay'))))
				$this->_html .= $this->displayConfirmation($this->l('Sort order updated'));
			else
				$this->_html .= $this->displayError($this->l('An error occurred during sort order set-up.'));
		}

		$this->_html .= $this->renderForm();
		$this->_html .= $this->renderList();

		return $this->_html;
	}
	
		public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'headerlinks.css', 'all');
	}
	





public function renderList()
	{
		$fields_list = array(
			'id' => array(
				'title' => $this->l('Id'),
				'type' => 'text',
			),
			'text_'.$this->context->language->id => array(
				'title' => $this->l('Text'),
				'type' => 'text',
			),
			'url_'.$this->context->language->id => array(
				'title' => $this->l('Url'),
				'type' => 'text',
			),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id';
		$helper->actions = array('delete');
		$helper->show_toolbar = false;

		$helper->title = $this->l('Link list');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$links = $this->getLinks();
		if (is_array($links) && count($links))
			return $helper->generateList($this->getLinks(), $fields_list);
		else
			return false;
	}

	public function renderForm()
	{
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Add a new link'),
					'icon' => 'icon-plus-sign-alt'
				),
				'input' => array(
					array(
						'type' => 'hidden',
						'name' => 'id_blocklink',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Text'),
						'name' => 'text',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Url'),
						'name' => 'url',
						'lang' => true,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Open in a new window'),
						'name' => 'newWindow',
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
					'title' => $this->l('Save'),
					'name' => 'submitLinkAdd',
				)
			),
		);

		$shops = Shop::getShops(true, null, true);
		if (Shop::isFeatureActive())
		{
			$fields_form_1['form']['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$fields_form_2 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Block title and links'),
					'icon' => 'icon-plus-sign-alt'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Title'),
						'name' => 'title',
						'lang' => true,
					),
					array(
						'type' => 'switch',
						'is_bool' => true,
						'label' => $this->l('Contact link'),
						'name' => 'contactlink',
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
						'is_bool' => true,
						'label' => $this->l('Sitemap link'),
						'name' => 'sitemaplink',
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
					'title' => $this->l('Save'),
					'name' => 'submitTitle',
				)
			),
		);

		$fields_form_3 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Order list'),
						'name' => 'orderWay',
						'options' => array(
							'query' => array(
								array(
									'id' => 0,
									'name' => $this->l('by most recent links')
								),
								array(
									'id' => 1,
									'name' => $this->l('by oldest links')
								)
							),
							'id' => 'id',
							'name' => 'name',
						)
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitOrderWay',
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = 'id_blocklink';
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_1, $fields_form_2, $fields_form_3));
	}

	public function getConfigFieldsValues()
	{
		$fields_values = array(
			'id_blocklink' => Tools::getValue('id_blocklink'),
			'newWindow' => Tools::getValue('newWindow'),
			'contactlink' => Tools::getValue('PS_headerlinks_contact', Configuration::get('PS_headerlinks_contact')),
			'sitemaplink' => Tools::getValue('PS_headerlinks_sitemap', Configuration::get('PS_headerlinks_sitemap')),
			'orderWay' => Tools::getValue('orderWay', Configuration::get('PS_headerlinks_ORDERWAY')),
		);


		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['text'][$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang']);
			$fields_values['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang']);
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('title', Configuration::get('PS_headerlinks_TITLE', $lang['id_lang']));
		}

		if (Tools::getIsset('updateblocklink') && (int)Tools::getValue('id') > 0)
			$fields_values = array_merge($fields_values, $this->getLinkById((int)Tools::getValue('id')));

		return $fields_values;
	}

}
