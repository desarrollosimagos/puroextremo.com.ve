<?php
class Lasttweets extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
	public function __construct()
	{
		$this->name = 'lasttweets';
		$this->tab = 'front_office_features';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->version = 1.0;
		$this->bootstrap = true;

		parent::__construct();

		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Twitter Module');
		$this->description = $this->l('Display last tweets on hompage');
		$this->full_url = _MODULE_DIR_.$this->name.'/';
		
		$config = Configuration::getMultiple(array('PS_TWITTER_USERNAME', 'PS_TWITTER_NB'));
		if (empty($config['PS_TWITTER_USERNAME']))
			$this->warning = $this->l('Please insert your Twitter username');		

		if (empty($config['PS_TWITTER_NB']))
			$this->warning = $this->l('Please insert your Teets number');	
	}

	function install()
	{
		$this->_clearCache('lasttweets.tpl');
		if (!parent::install() OR !$this->registerHook('displayHome') OR  !$this->registerHook('displayHeader')
			OR !Configuration::updateValue('PS_TWITTER_USERNAME', 'prestashop') OR !Configuration::updateValue('PS_TWITTER_NB', '10')
			OR !Configuration::updateValue('PS_TWITTER_ID', '349621608203292672') OR !Configuration::updateValue('PS_TWITTER_PHOTO', 1)
			)
			return false;
		return true;
	}
	public function uninstall()
	{
		$this->_clearCache('lasttweets.tpl');
		if (!Configuration::deleteByName('PS_TWITTER_USERNAME') OR !Configuration::deleteByName('PS_TWITTER_NB') OR !Configuration::deleteByName('PS_TWITTER_ID')
			OR !parent::uninstall())
			return false;
		return true;
	}	
	public function hookDisplayHome($params)
	{
		if (!$this->isCached('lasttweets.tpl', $this->getCacheId()))
		{
			global $smarty;
			$smarty->assign('username', Configuration::get('PS_TWITTER_USERNAME'));
			$smarty->assign('this_path', $this->_path);

		}
		Media::addJsDef(array('twitter_widgetid' => Configuration::get('PS_TWITTER_ID')));
		Media::addJsDef(array('twitter_numberoftweets' => Configuration::get('PS_TWITTER_NB')));
		return $this->display(__FILE__, 'lasttweets.tpl', $this->getCacheId());
	}
	
	public function hookFooter($params)
	{
		return $this->hookDisplayHome($params);
	}

	
	public function hookDisplayHeader($params)
	{
			if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;

		$this->context->controller->addCss($this->_path.'lasttweets.css');
		$this->context->controller->addJS($this->_path.'lasttweets.js');
	}
	

	public function getContent()
	{
		$output ='';
		if (Tools::isSubmit('submitModule'))
		{

			if (empty($_POST['twitterusername']))
				$errors[] = $this->l('Please insert your Twitter username');		
			if (empty($_POST['twitternumber']))
				$errors[] = $this->l('Please insert number of tweets');

			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else{
				Configuration::updateValue('PS_TWITTER_USERNAME', $_POST['twitterusername']);
				Configuration::updateValue('PS_TWITTER_ID', $_POST['twitterid']);
				Configuration::updateValue('PS_TWITTER_NB', $_POST['twitternumber']);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
				$this->_clearCache('lasttweets.tpl');
			}

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
				'description' => 		$this->l('To create a timeline you must be signed in to twitter.com and visit the').' <a href="https://twitter.com/settings/widgets"><strong>'.$this->l('widgets section of your settings page').'</strong></a> '. $this->l('From this page you can see a list of the timelines you have configured and create new timelines.
		Click the "Create new" button to build a new timeline for your website, choose the type, and complete the fields in the form; most fields are optional. The configuration is stored on our server, so once you have saved the timeline a small piece of JavaScript is generated to paste into your page, which will load the timeline'), 
				
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Twitter username'),
						'name' => 'twitterusername',
						),
					array(
						'type' => 'text',
						'label' => $this->l('Widget id'),
						'name' => 'twitterid',
						'desc' => $this->l('Create USER TIMELINE widget first to get widget id! Recommanded widget height: 250px'),
					),	
					array(
						'type' => 'text',
						'label' => $this->l('Tweets number'),
						'desc' => $this->l('Min: 1, Max: 20'),
						'name' => 'twitternumber',
						),
					),
				'submit' => array(
					'title' => $this->l('Save')
					)
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
		'twitternumber' => Tools::getValue('PS_TWITTER_NB', Configuration::get('PS_TWITTER_NB')),
		'twitterid' => Tools::getValue('PS_TWITTER_ID', Configuration::get('PS_TWITTER_ID')),
		'twitterusername' => Tools::getValue('PS_TWITTER_USERNAME', Configuration::get('PS_TWITTER_USERNAME')),
		);
}
}