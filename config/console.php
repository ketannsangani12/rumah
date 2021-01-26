<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'fcm1' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AAAAaq66eTU:APA91bGEeGuDCqx1rYo4-RyG0vh5WiuUnj7IQNRdEOuy1SnlFJHgOX6cFN4WmYU11MD6u7WuMmzjRb25sF8ht1KVKLxmYAJziAaEk7J89veltjBvjV_eAZ4092GHRbixG0h1EZxsORo0', // Server API Key (you can get it here: https://firebase.google.com/docs/server/setup#prerequisites)
        ],
        'fcm2' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AAAAJ8-FITc:APA91bHkTxjGr3w-cSyxIK-web9If5qUoMqONP6awDIkqdfebyZkcnM7R1DZeZKbTfwWiPeC3KGDqNc4Pp1J1wSQHeYgE1ei2F---KwYiYVvm6KI0-X1BjGv2F_XzXGecn65DU411V4O', // Server API Key (you can get it here: https://firebase.google.com/docs/server/setup#prerequisites)
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
