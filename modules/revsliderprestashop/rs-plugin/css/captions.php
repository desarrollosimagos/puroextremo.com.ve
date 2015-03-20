<?php

require_once(dirname(__FILE__).'../../../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../../../init.php');
require_once(dirname(__FILE__).'../../../revprestashoploader.php');
require_once ABSPATH . "/revslider_admin.php";
//$currentFolder = dirname(__FILE__);

//include framework files
//require_once $currentFolder . '../../../inc_php/framework/include_framework.php';


$admin = new RevSliderAdmin(ABSPATH,false);
$db = new UniteDBRev();
$styles = $db->fetch(GlobalsRevSlider::$table_css);

header("Content-Type: text/css; charset=utf-8");

echo UniteCssParserRev::parseDbArrayToCss($styles, "\n");

?>