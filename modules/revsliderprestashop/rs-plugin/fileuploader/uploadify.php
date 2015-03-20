<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
require_once(dirname(__FILE__).'../../../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../../../init.php');
require_once dirname(__FILE__) . '../../../revprestashoploader.php';
require_once ABSPATH . "/revslider_admin.php";
$admin = new RevSliderAdmin(ABSPATH,false);

$key = Tools::getValue('security_key');

if(empty($key) || 
        Tools::encrypt(GlobalsRevSlider::MODULE_NAME) != $key){    
    echo json_encode(array('error_on' => 1,
        'error_details' => 'Security Error'));
    die();
}

// Define a destination
// $targetFolder = _PS_MODULE_DIR_.'revsliderprestashop/uploads'; // Relative to the root

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
            echo 'Invalid file type.';
	}
        die();
}
?>