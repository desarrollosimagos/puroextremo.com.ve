<?php
defined('_PS_VERSION_') OR die('No Direct Script Access Allowed');
/**
 * Description of ajax
 *
 * @author Mohammad Arifur Rahman 
 * @todo Description
 * @link http://smartdatasoft.net description
 * @version v1.1
 * @copyright (c) 2013, Smart Datasoft
 * @license http://URL name 
 */

 
include_once(_PS_MODULE_DIR_.'revsliderprestashop/revprestashoploader.php');
include_once(_PS_MODULE_DIR_.'revsliderprestashop/revslider_admin.php');

class Revolutionslider_ajaxController extends ModuleAdminController 
{
    /**
     *
     * @var array
     */
    protected $_ajax_results;
    /**
     *
     * @var type 
     */
    protected $_ajax_stripslash;
    /**
     *
     * @var type 
     */
    protected $_filter_whitespace;
    /**
     *
     * @var Lushslider_model
     */
    protected $lushslider_model;
    /**
     * {@inheritdoc}
     */
    public function __construct() 
    {        
        $this->display_header = false;
        $this->display_footer = false;
        $this->content_only   = true;
        //$this->bindToAjaxRequest();        
        parent::__construct();
        $this->_ajax_results['error_on'] = 1; 
        // Let's include Lushslider Model
        
    }
    public function init()
    {        

        // Process POST | GET
        $this->initProcess();
    }
    /**
     * 
     * @throws Exception
     */
    public function initProcess()
    {

        $loadTemplate = true;
        
        $revAction = Tools::getValue('revControllerAction');
        
        if(!empty($revAction)){
            $loadTemplate = false;
        }
        
        $productAdmin = new RevSliderAdmin(_PS_MODULE_DIR_.'revsliderprestashop',$loadTemplate);
        
        switch($revAction){
            
            case 'uploadimage':
                $this->rev_uploader();                
                break;
            
            case 'captions':
                
                $db = new UniteDBRev();

                $styles = $db->fetch(GlobalsRevSlider::$table_css);

                header("Content-Type: text/css; charset=utf-8");

                echo UniteCssParserRev::parseDbArrayToCss($styles, "\n");
                
                
                break;
            
            default:
                
                break;
            
        }
        
        die();
        
    }
    private function rev_uploader(){
        
        $key = Tools::getValue('security_key');

        if(empty($key) || 
                Tools::encrypt(GlobalsRevSlider::MODULE_NAME) != $key){    
            echo json_encode(array('error_on' => 1,
                'error_details' => 'Security Error'));
            die();
        }
        
        $targetFolder = ABSPATH.'/uploads/';
        $randnum = rand(0000000,9999999);

        //$verifyToken = md5('unique_salt' . $_POST['timestamp']);

        if (!empty($_FILES)) {        
                $tempFile = $_FILES['Filedata']['tmp_name'];
                //$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                $targetPath = $targetFolder;
                //$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

                // Validate the file type
                $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);

                if (in_array($fileParts['extension'],$fileTypes)) {
                    $worked = UniteFunctionsWPRev::import_media_img($tempFile, $targetPath, $randnum.$_FILES['Filedata']['name']);
                    if(!empty($worked))
                            echo '1';

                } else {
                    echo '0';
                }
                
        }
        //die();
        
    }
    
    
    /**
     * 
     * @param boolean $post_method
     * @return boolean
     */
    protected function bindToAjaxRequest($post_method = false)
    {
        if(!$this->isXmlHttpRequest())
            die ('We Only Accept Ajax Request');
        // Also Restricted to POST method
        if($post_method)
        {
            if(!isset ($_SERVER['REQUEST_METHOD']) OR 'POST' != $_SERVER['REQUEST_METHOD'])
                die ('Only POST Request Method is allowed');
        }                        
        return TRUE;                 
    } /* Ends bindToAjaxRequest() */
    
}

