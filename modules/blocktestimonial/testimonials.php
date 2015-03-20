<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/blocktestimonial.php');
if (isset(Context::getContext()->controller)) {
	$oController = Context::getContext()->controller;
}
else {
	$oController = new FrontController();
	$oController->init();
}
$oController->setMedia();
@$oController->displayHeader();
$blockTestimonial = new blockTestimonial();
echo $blockTestimonial->displayTestimonials();
@$oController->displayFooter();
?>