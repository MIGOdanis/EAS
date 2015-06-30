<?php
header('Content-type: text/html; charset=utf-8'); 


$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	defined('YII_DEBUG') or define('YII_DEBUG',true);
// }

require_once($yii);
Yii::createWebApplication($config)->run();
