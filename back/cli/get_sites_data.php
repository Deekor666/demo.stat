<?php

require_once '../config.php';
require_once '../General.php';
require_once '../../public/libs/Smarty.class.php';


$SMARTY = new Smarty();
$SMARTY->template_dir = 'tpl/';
$SMARTY->compile_dir = 'tpl_c/';
$SMARTY->cache_dir = 'cache/';
General::init($DB, $SMARTY);
require_once(SITE_ROOT . '../back/controller/SiteController.php');
require_once(SITE_ROOT . '../back/controller/ParserController.php');

ParserController::loadSitesData();
