<?
error_reporting(22519);
$yii=  dirname(__FILE__).'/../yii/framework/yii.php';
$config= dirname(__FILE__).'/protected/config/main.php';

define('YII_DEBUG',true);
define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_ENABLE_EXCEPTION_HANDLER', true);

require_once($yii);
$app = new Yii();
$app->createWebApplication($config);
?>