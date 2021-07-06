<?php
//phpinfo();
//exit;
ini_set('display_erros','On');
ini_set('allow_url_fopen','On');
error_reporting(22519);
date_default_timezone_set('America/Sao_Paulo');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Referer, Language, Provider, UserEmail, User-Agent, App, Client, X-Apikey, X-Authorization, Authorization, Versao, Method,HTTP_X_UID, HTTP_X_REST_TOKEN,X_REST_TOKEN,app-versao");

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

if (isset($_SERVER['DEBUGSHOW'])){
    define('YII_DEBUG', $_SERVER['DEBUGSHOW'] == 1);
}
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
