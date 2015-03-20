<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/blocklayered_mod.php');

if (substr(Tools::encrypt('blocklayered_mod/index'),0,10) != Tools::getValue('token') || !Module::isInstalled('blocklayered_mod'))
	die('Bad token');

$blockLayered = new BlockLayered_mod();
echo $blockLayered->indexAttribute();