<?php
ini_set('date.timezone', 'Europe/London');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../common/config/bootstrap.php');
require(__DIR__ . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../common/config/main-local.php'),
    require(__DIR__ . '/config/main.php'),
    require(__DIR__ . '/config/main-local.php')
);

require(__DIR__ . '/../thridpart/functions/functions.php');

$application = new yii\web\Application($config);
$application->language = isset(\Yii::$app->session['language']) ? \Yii::$app->session['language'] : 'en-US' ;
\Yii::$app->session['language'] = $application->language;
$application->run();
