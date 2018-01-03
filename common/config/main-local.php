<?php

// shop name

$user = '';
$pw = '';
$db = str_replace("o", "", $user);

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=3316;dbname='.$db.';unix_socket=/tmp/mysql5.sock',
            //'dsn' => 'mysql:host=127.0.0.1;port=3316;dbname='.$db;
            'username' => $user,
            'password' => $pw,
            'charset' => 'utf8',

            'tablePrefix' => 'yii2_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
