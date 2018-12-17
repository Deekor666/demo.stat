<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '/..';
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/..'),
    get_include_path(),
)));
require_once 'config.php';
require_once 'General.php';
require_once (SITE_ROOT  . '../public/libs/Smarty.class.php');


$SMARTY = new Smarty();
$SMARTY->template_dir = 'tpl/';
$SMARTY->compile_dir = 'tpl_c/';
$SMARTY->cache_dir = 'cache/';
General::init($DB, $SMARTY);
require_once(SITE_ROOT . '../back/controller/SiteController.php');
require_once(SITE_ROOT . '../back/controller/ParserController.php');

ParserController::loadSitesData();
