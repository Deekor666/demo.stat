<?php
require_once '../back/config.php';
require_once '../back/General.php';
require_once 'libs/Smarty.class.php';


$SMARTY = new Smarty();
$SMARTY->template_dir = 'tpl/';
$SMARTY->compile_dir = 'tpl_c/';
$SMARTY->cache_dir = 'cache/';
General::init($DB, $SMARTY);

require_once(SITE_ROOT . '../back/controller/SiteController.php');
require_once(SITE_ROOT . '../back/controller/ParserController.php');

$act = 'site';
if (!empty($_GET['act'])) {
    $act = $_GET['act'];
}
/**
 * при входе на сайт, запуск Сайт Контроллера
 */
if ($act === 'site') {
    new SiteController();
}
