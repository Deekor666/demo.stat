<?php
require_once '../back/dbconfig.php';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$DB = new PDO($dsn, $user, $pass, $opt);


define('SITE_ROOT', realpath(dirname(__FILE__)) . '../');
define('BACK_DIR', SITE_ROOT . '../back/');
define('WWW_DIR', SITE_ROOT . '../public/');

