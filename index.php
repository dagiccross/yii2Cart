<?php
// SET UK TIME
date_default_timezone_set('Europe/London');


 // ini_set('mysql.default_socket','/tmp/mysql5.sock');
 // ini_set('pdo_mysql.default_socket','/tmp/mysql5.sock');
 // ini_set('mysqli.default_socket','/tmp/mysql5.sock');
 // phpinfo();exit();
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/common/config/main-local.php'),
    require(__DIR__ . '/frontend/config/main.php'),
    require(__DIR__ . '/frontend/config/main-local.php')
);

require(__DIR__ . '/thridpart/functions/functions.php');


define("CSS_URL",SITE_URL.'/frontend/web/css');
define("IMG_URL",SITE_URL.'/frontend/web/images');
define("JS_URL",SITE_URL.'/frontend/web/js');

$application = new yii\web\Application($config);

if(!isset(Yii::$app->session['lang'])){

	$i = 0 ;
	foreach (getLanguage() as $k => $v) {
		\Yii::$app->session['lang'] = $k;
		break;
	}
	$application->language = Yii::$app->session['lang'];
}


$application->run();
