<?php
if (!defined('_PS_VERSION_'))
	exit;

class csmanufacturer extends Module
{
	function __construct()
	{
		$this->name = 'csmanufacturer';
		$this->tab = 'My Blocks';
		$this->version = '1.0';
		$this->author = 'Codespot';

		parent::__construct();

		$this->displayName = $this->l('CS Slider of Manufacturer Logo');
		$this->description = $this->l('Adds Slider of Manufacturer Logo.');
	}

	function install()
	{
		if (!parent::install() || !$this->registerHook('footertop') || !$this->registerHook('header') ||
			!$this->registerHook('actionObjectManufacturerUpdateAfter') ||
			!$this->registerHook('actionObjectManufacturerDeleteAfter'))
			return false;
		return true;
	}
	public function uninstall()
	{
	 	if (parent::uninstall() == false)
	 		return false;
		$this->_clearCache('csmanufacturer.tpl');
	 	return true;
	}
	function hookHeader($params)
	{
		global $smarty;
		$smarty->assign(array(
			'HOOK_FOOTER_TOP' => Hook::Exec('footertop')
		));
		$this->context->controller->addCss($this->_path.'css/csmanufacturer.css', 'all');
	}
	function hookFooterTop($params)
	{
		global $smarty,$cookie;
		
		if (version_compare(_PS_VERSION_,'1.5.4','<'))
		{
			$smarty_cache_id = $this->name.'|'.(int)Tools::usingSecureMode().'|'.(int)$this->context->shop->id.'|'.(int)Group::getCurrent()->id.'|'.(int)$this->context->language->id;
			$this->context->smarty->cache_lifetime = 31536000;
			Tools::enableCache();
		}
		else 
		{
			$smarty_cache_id = $this->getCacheId();
		}
		
		if (!$this->isCached('csmanufacturer.tpl',$smarty_cache_id))
		{
			$manufacturers = Manufacturer::getManufacturers(false,0,true);
			$smarty->assign(array(
				'manufacs' => $manufacturers,
				'ps_manu_img_dir' => _PS_MANU_IMG_DIR_
			));
		}
		if (version_compare(_PS_VERSION_,'1.5.4','<'))
			Tools::restoreCacheSettings();
		return $this->display(__FILE__, 'csmanufacturer.tpl',$smarty_cache_id);
	}
	
	public function hookActionObjectManufacturerUpdateAfter($params)
	{
		$this->_clearCache('csmanufacturer.tpl');
	}
	
	public function hookActionObjectManufacturerDeleteAfter($params)
	{
		$this->_clearCache('csmanufacturer.tpl');
	}
	
}


