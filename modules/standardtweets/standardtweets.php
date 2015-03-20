<?php
class StandardTweets extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
	public function __construct()
	{
		$this->name = 'standardtweets';
		$this->tab = 'front_office_features';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->version = 1.1;
		$this->bootstrap = true;

		parent::__construct();

		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Standard tweets(TWITTER)');
		$this->description = $this->l('Display last tweets on sidebar or footer');
		$this->full_url = _MODULE_DIR_.$this->name.'/';
		
		$config = Configuration::getMultiple(array('PS_TWITTER2_USERNAME2'));
		if (empty($config['PS_TWITTER2_USERNAME2']))
			$this->warning = $this->l('Please insert your Twitter username');		
	}

	function install()
	{
		$this->_clearCache('standardtweets.tpl');
		if (!parent::install() OR !$this->registerHook('leftColumn') OR  !$this->registerHook('displayHeader')
			OR !Configuration::updateValue('PS_TWITTER2_USERNAME2', 'agencja_iqit') OR !Configuration::updateValue('PS_TWITTER2_ID', '349621608203292672')
			)
			return false;
		return true;
	}
	public function uninstall()
	{
		$this->_clearCache('standardtweets.tpl');
		if (!Configuration::deleteByName('PS_TWITTER2_USERNAME2') OR !Configuration::deleteByName('PS_TWITTER2_ID')
			OR !parent::uninstall())
			return false;
		return true;
	}	
	public function hookLeftColumn($params)
	{
		if (!$this->isCached('standardtweets.tpl', $this->getCacheId()))
		{
			global $smarty;
			$smarty->assign('username', Configuration::get('PS_TWITTER2_USERNAME2'));
			$smarty->assign('widgetid', Configuration::get('PS_TWITTER2_ID'));
			$smarty->assign('this_path', $this->_path);
		}
		return $this->display(__FILE__, 'standardtweets.tpl', $this->getCacheId());
	}
	
	public function hookFooter($params)
	{
		if (!$this->isCached('standardtweets-footer.tpl', $this->getCacheId()))
		{
			global $smarty;
			$smarty->assign('username', Configuration::get('PS_TWITTER2_USERNAME2'));
			$smarty->assign('widgetid', Configuration::get('PS_TWITTER2_ID'));
			$smarty->assign('this_path', $this->_path);
		}
		return $this->display(__FILE__, 'standardtweets-footer.tpl', $this->getCacheId());
	}
	
	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCss($this->_path.'standardtweets.css');
	}
	
	
	public function getContent()
	{
		$output ='';
		if (Tools::isSubmit('submitModule'))
		{

			if (empty($_POST['twitterusername']))
				$errors[] = $this->l('Please insert your Twitter username');		
			if (empty($_POST['twitterid']))
				$errors[] = $this->l('Please insert your Twitter widget id');

			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else{
				Configuration::updateValue('PS_TWITTER2_USERNAME2', $_POST['twitterusername']);
				Configuration::updateValue('PS_TWITTER2_ID', $_POST['twitterid']);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
				$this->_clearCache('standardtweets.tpl');
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
			'twitterid' => Tools::getValue('PS_TWITTER2_ID', Configuration::get('PS_TWITTER2_ID')),
			'twitterusername' => Tools::getValue('PS_TWITTER2_USERNAME2', Configuration::get('PS_TWITTER2_USERNAME2')),
		);
	}

	}
