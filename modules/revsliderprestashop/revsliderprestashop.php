<?php

/*
  Plugin Name: Revolution Slider
  Plugin URI: http://www.themepunch.com/codecanyon/revolution_ps/
  Description: Revolution Slider - Premium responsive slider
  Author: SmatDataSoft
  Version: 4.1.4.2
  Author URI: http://smartdatasoft.com
 */
if(!defined('__DIR__'))
    define('__DIR__',  dirname(__FILE__));
$currentFolder = __DIR__;
require_once $currentFolder . '/revprestashoploader.php'; // added by sds on 2nd Jan, 2013

class RevsliderPrestashop extends Module {

    public static $wpdb;
    public static $_url;
    public function __construct() {
        global $revSliderVersion;
        $this->name = 'revsliderprestashop';
        $this->tab = 'front_office_features';
        $this->version = GlobalsRevSlider::SLIDER_REVISION;
        $this->author = 'smartdatasoft';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        parent::__construct();
        self::$wpdb = rev_db_class::rev_db_instance();
        self::$_url = $this->_path;
        $this->displayName = $this->l('Revolution Slider.');
        $this->description = $this->l('Revolution Slider - Premium responsive Prestashop slider');
        
      
    }
    public static function rev_slider_shortcode($args) {        

        $sliderAlias = UniteFunctionsRev::getVal($args, 0);

        ob_start();

        $slider = RevSliderOutput::putSlider($sliderAlias);

        $content = ob_get_contents();

        ob_clean();

        ob_end_clean();



        //handle slider output types

        if (!empty($slider)) {

            $outputType = $slider->getParam("output_type", "");

            switch ($outputType) {

                case "compress":

                    $content = str_replace("\n", "", $content);

                    $content = str_replace("\r", "", $content);

                    return($content);

                    break;

                case "echo":

                    echo $content;  //bypass the filters

                    break;

                default:

                    return($content);

                    break;

            }

        }

        else

            return($content);  //normal output

    }
    public function install() {
        /* Adds Module */
        
        if (parent::install() && $this->registerHook('displayTop') 
                && $this->registerHook('displayHome') 
                && $this->registerHook('displayLeftColumn') 
                && $this->registerHook('displayRightColumn') 
				&& $this->registerHook('displayLeftColumn') 
				&& $this->registerHook('displayTopColumn')
		        && $this->registerHook('displayBanner')
		        && $this->registerHook('displayHeader') 
				&& $this->registerHook('displayFooter') 
				
				&& $this->registerHook('displayLeftColumnProduct') 
				&& $this->registerHook('displayRightColumnProduct') 
				&& $this->registerHook('displayProductButtons') 
				&& $this->registerHook('displayFooterProduct') 
				&& $this->registerHook('displayCarrierList') 
				&& $this->registerHook('displayBeforeCarrier') 
				&& $this->registerHook('displayPaymentTop') 
				&& $this->registerHook('displayPaymentReturn') 
				&& $this->registerHook('displayOrderConfirmation') 
				&& $this->registerHook('displayShoppingCart') 
				&& $this->registerHook('displayShoppingCartFooter') 
				&& $this->registerHook('dislayMyAccountBlock') 
				&& $this->registerHook('displayCustomerAccountFormTop') 
				&& $this->registerHook('displayCustomerAccountForm') 
                                && $this->moduleControllerRegistration()
				
                && $this->registerHook('actionShopDataDuplication')) {
            
            require_once ABSPATH . "/revslider_admin.php";
                        
            $res = RevSliderAdmin::onActivate();            
            $admin = new RevSliderAdmin(ABSPATH,false);            
            RevSliderAdmin::sds_caption_css_init($res);
            //$admin->addEvent_onActivate();            
            //$res = RevSliderAdmin::onActivate();
            $this->installQuickAccess();
            return (bool)$res;
        }
        return false;
    }

    public function uninstall() {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $res =  $this->moduleControllerUnRegistration();
            require_once ABSPATH . "/revslider_admin.php";
            $res &= RevSliderAdmin::deleteDBTables();                        
            return (bool)$res;
        }
        return false;
    }
 
    protected function moduleControllerRegistration()
    {
        $tab = new Tab(null, Configuration::get('PS_LANG_DEAFULT'), Configuration::get('PS_SHOP_DEAFULT'));
        $tab->class_name = 'Revolutionslider_ajax';
        $tab->id_parent  = 0;
        $tab->module     = $this->name;
        $tab->name       = "Revolutionslider Ajax Controller";
        $tab->position   = 10;
        $tab->active     = 0;
        $tab->add();
        
        if(!$tab->id)
            return FALSE;
        
//        $tab2 = new Tab(null, Configuration::get('PS_LANG_DEAFULT'), Configuration::get('PS_SHOP_DEAFULT'));
//        $tab2->class_name = 'Revolutionslider_imgmanager';
//        $tab2->id_parent  = 0;
//        $tab2->module     = $this->name;
//        $tab2->name       = "Revolutionslider Media";
//        $tab2->position   = 10;
//        $tab2->active     = 1;
//        $tab2->add();
//        if(!$tab2->id)
//            return FALSE;
        Configuration::updateValue('REVOLUTION_CONTROLLER_TABS', json_encode(array($tab->id)));
        return true;
    }
    
    protected function moduleControllerUnRegistration()
    {
        $ids = json_decode(Configuration::get('REVOLUTION_CONTROLLER_TABS'), true);
        foreach ($ids AS $id)
        {
            $tab = new Tab($id);
            $tab->delete();
        }

            $quickids = json_decode(Configuration::get('REV_QUICK_ACCS'), true);
            
        foreach ($quickids AS $id)
        {
            $quc = new QuickAccess($id);
            $quc->delete();
        }


        return true;        
    }
    
    
    protected function _prehook() {
        require_once ABSPATH . "/revslider_front.php";
        $revfront = new RevSliderFront(ABSPATH);
        return $revfront;
    }

    protected function hookCommonCb() {
        $revfront = $this->_prehook();
        $this->context->controller->addCSS($this->_path . 'rs-plugin/css/settings.css');
        $this->context->controller->addCSS($this->_path . 'rs-plugin/css/captions.css');
        $this->context->controller->addCSS($this->_path . 'rs-plugin/css/static-captions.css');
        $this->context->controller->addCSS($this->_path . 'rs-plugin/css/dynamic-captions.css');        
        $this->context->controller->addCSS($this->_path . 'css/front.css');        
        //$this->context->controller->addJS($this->_path . 'js/jquery-1.9.1.min.js');        
        $this->context->controller->addJS($this->_path . 'rs-plugin/js/jquery.themepunch.plugins.min.js');
        $this->context->controller->addJS($this->_path . 'rs-plugin/js/jquery.themepunch.revolution.min.js');

        $sliders = self::$wpdb->get_results("SELECT * FROM " . self::$wpdb->prefix . GlobalsRevSlider::TABLE_SLIDERS_NAME);
        return $sliders;
    }

    public function generateSlider($hookPosition='displayHome'){
        
        $sliders = $this->hookCommonCb();        
        $content = '';
        
        if (!empty($sliders)) {            
            ob_start();
            foreach ($sliders as $slider):
                $slider = (object)$slider;
                
                $params = json_decode($slider->params); 
                
                if(isset($params->id_shop) && $params->id_shop != Shop::getContextShopID()){
                    continue;
                }
                
                if ($params->displayhook === $hookPosition) {                    
                    RevSliderOutput::putSlider($slider->id, '');
                }

            endforeach;
            $content = ob_get_contents();
            ob_end_clean();
        }        
        $this->smarty->assign('revhome', $content);
        return $this->display(__FILE__, 'views/templates/front/revolution_slider.tpl');
        
    }
   
public function generateSliderById($id=1){

 $content = '';

  if (empty($id) )
      return 'no id found';     


        ob_start();
        RevSliderOutput::putSlider($id, '');
         $content = ob_get_contents();
         ob_end_clean();

 $this->smarty->assign('revhome', $content);
        return $this->display(__FILE__, 'views/templates/front/revolution_slider.tpl');


  }
    public function hookDisplayLeftColumn($params) {
          return $this->generateSlider('displayLeftColumn');  
    }
     
    public function hookDisplayRightColumn($params) {
          return $this->generateSlider('displayRightColumn');  
    }
    public function hookDisplayHome($params) {
     return $this->generateSlider('displayHome');
    }

    public function hookDisplayBanner($params) {
     return $this->generateSlider('displayBanner');
    }


  public function hookDisplayTop($params) {

    if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
      return;


     return $this->generateSlider('displayTop');
    }
 

  public function hookdisplayTopColumn($params)
  {
    if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
      return;

    return $this->generateSlider('displayTopColumn');
  }

public function hookdisplayHeader ($params) {
//A hook which allow you to do things in the header of each pages
  return $this->generateSlider('displayHeader');
}
public function hookdisplayFooter ($params) {
// Add block in footer
  return $this->generateSlider('displayFooter');
}
public function hookdisplayLeftColumnProduct($params) {
  return $this->generateSlider('displayLeftColumnProduct');

}public function hookdisplayRightColumnProduct($params) {
  return $this->generateSlider('displayRightColumnProduct');
  }
public function hookdisplayProductButtons($params) {
  return $this->generateSlider('displayProductButtons');
}
 
public function hookdisplayFooterProduct($params){
  return $this->generateSlider('displayFooterProduct');
}

public function hookdisplayCarrierList($params) {
  return $this->generateSlider('displayCarrierList');
}

public function hookdisplayBeforeCarrier($params){
  return $this->generateSlider('displayBeforeCarrier');
}

public function hookdisplayPaymentTop($params){
  return $this->generateSlider('displayPaymentTop');
}

public function hookdisplayPaymentReturn($params){
  return $this->generateSlider('displayPaymentReturn');
}

public function hookdisplayOrderConfirmation($params){
  return $this->generateSlider('displayOrderConfirmation');
}

 

public function hookdisplayShoppingCart($params) {
  return $this->generateSlider('displayShoppingCart');
}

public function hookdisplayShoppingCartFooter($params) {
  return $this->generateSlider('displayShoppingCartFooter');
}

public function hookpdislayMyAccountBlock($params){
  return $this->generateSlider('dislayMyAccountBlock');
}


public function hookdisplayCustomerAccountFormTop($params){
  return $this->generateSlider('displayCustomerAccountFormTop');
}

public function hookdisplayCustomerAccountForm($params){
  return $this->generateSlider('displayCustomerAccountForm');
}

public function getContent() {
        global $currentFile;
    

        $content = '<script type="text/javascript">';

        $content .= ' var ajaxurl = "'.$this->context->link->getAdminLink('Revolutionslider_ajax') .'"  ; ' ;

        $content .= '     </script>';
        require_once ABSPATH . "/revslider_admin.php";
        ob_start();
        
        $productAdmin = new RevSliderAdmin(ABSPATH);
        $content .= ob_get_contents();


      
        ob_end_clean();
        return $content;        
    }
    public function installQuickAccess(){

      $qick_access = new QuickAccess();

      $qick_access->link = 'index.php?controller=AdminModules&configure=revsliderprestashop&tab_module=front_office_features&module_name=revsliderprestashop';
      $qick_access->new_window = false;

      $languages = Language::getLanguages(false);
      foreach ($languages as $language){
          $qick_access->name[$language['id_lang']]= 'Revolution Slider';
      }
      $qick_access->add();  

      if(!$qick_access->id)
            return FALSE;
        Configuration::updateValue('REV_QUICK_ACCS', json_encode(array($qick_access->id)));
    return true;
    }

}