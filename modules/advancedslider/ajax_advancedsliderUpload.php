<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('advancedslider.php');
include_once('imageUpload.php');

if (Tools::getValue('action') == 'deleteImage' && Tools::getValue('id'))
     	{
			
		
				$row = Db::getInstance()->getRow('
                SELECT image FROM `'._DB_PREFIX_.'advancedslider_slides_images`
                WHERE `id_image` = '.intval(Tools::getValue('id')).'');
				
				
				 if (file_exists('./uploads/' . $row['image'])) 
       			 unlink('./uploads/' . $row['image']);

	 		$query = Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'advancedslider_slides_images WHERE `id_image`='.(int)(Tools::getValue('id')));
			if($query)
			echo Tools::getValue('id');
     	}
		



if (Tools::getValue('action') == 'submitUploadImage' && Tools::getValue('id_slide'))
     	{
$allowedExtensions = array("jpg", "jpeg", "png", "gif");
$sizeLimit = 204857;
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('uploads/');
$filename = $uploader->getName();
	if (isset($result['success']))
		{
			
				$query = Db::getInstance()->Execute('
            INSERT INTO `'._DB_PREFIX_.'advancedslider_slides_images`
		(`id_advancedslider_slides`, `image`) VALUES(
		'.intval(Tools::getValue('id_slide')).', 
		\''.$result['filename'].'\')');
		
		
		$last_id = Db::getInstance()->Insert_ID(); 
		$result['id_image'] = $last_id;
			
		
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);		
		}
		else
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);


     	}


