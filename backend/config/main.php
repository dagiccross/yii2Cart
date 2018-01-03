<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',

    'modules' => [
        'cms' => [
            'class' => 'backend\modules\cms\Module',
        ],
        'auth' => [
            'class' => 'backend\modules\auth\Module',
        ],
        'goods' => [
            'class' => 'backend\modules\goods\Module',
        ],
        'extensions' => [

            'class' => 'backend\modules\extensions\ExtensionModule',

        ],
        'order' => [

            'class' => 'backend\modules\order\OrderModule',

        ],
        'member' => [

            'class' => 'backend\modules\member\memberModule',

        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\Logininfo',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '__admin_identity', 'httpOnly' => true],
            'idParam' => '__admin'
        ],
        // 'db' => [
        //     'class' => 'yii\db\Connection',
        //     'dsn' => 'mysql:host=localhost;dbname=yii2_yii2test', // MySQL, MariaDB
        //     //'dsn' => 'sqlite:/path/to/database/file', // SQLite
        //     //'dsn' => 'pgsql:host=localhost;port=5432;dbname=mydatabase', // PostgreSQL
        //     //'dsn' => 'cubrid:dbname=demodb;host=localhost;port=33000', // CUBRID
        //     //'dsn' => 'sqlsrv:Server=localhost;Database=mydatabase', // MS SQL Server, sqlsrv driver
        //     //'dsn' => 'dblib:host=localhost;dbname=mydatabase', // MS SQL Server, dblib driver
        //     //'dsn' => 'mssql:host=localhost;dbname=mydatabase', // MS SQL Server, mssql driver
        //     //'dsn' => 'oci:dbname=//localhost:1521/mydatabase', // Oracle
        //     'username' => 'root', //数据库用户名
        //     'password' => '', //数据库密码
        //     'charset' => 'utf8',
        //     'tablePrefix' => 'yii2_',
        //     ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages'   //应该需要定义这个吧
                ],
            ],
        ],
    ],
    'params' => $params,
];
