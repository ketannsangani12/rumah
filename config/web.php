<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
    ],
    'components' => ['i18n' => [
        'translations' => [
            'yii2mod.rbac' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@yii2mod/rbac/messages',
            ],
            // ...
        ],
    ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'metronic'=>[
            'class'=>'dlds\metronic\Metronic',
            'resources'=>'Macintosh HD/Users/ketansangani/work/yii2metronic/web/metronic/theme/assets',
            'version'=>\dlds\metronic\Metronic::VERSION_4,
            'style'=>\dlds\metronic\Metronic::STYLE_SQUARE,
            'theme'=>\dlds\metronic\Metronic::THEME_DARK,
            'layoutOption'=>\dlds\metronic\Metronic::LAYOUT_FLUID,
            //'headerOption'=>\dlds\metronic\Metronic::HEADER_FIXED,
            'sidebarPosition'=>\dlds\metronic\Metronic::SIDEBAR_POSITION_LEFT,
            'sidebarOption'=>\dlds\metronic\Metronic::SIDEBAR_MENU_ACCORDION,
            //'footerOption'=>\dlds\metronic\Metronic::FOOTER_FIXED,

        ],
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//code.jquery.com/jquery-1.11.2.min.js',  // use custom jquery
                    ]
                ],

                'dlds\metronic\bundles\ThemeAsset' => [
                    'addons'=>[
                        'default/login'=>[
                            'css'=>[
                                'pages/css/login-4.min.css',
                            ],
                            'js'=>[
                                'global/plugins/backstretch/jquery.backstretch.min.js',

                            ]
                        ],
                    ]
                ],
            ],
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Xh3Opf8DjgFq3xMGcushTBaRaVNV77Kg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'tlssocietyapps@gmail.com',
                'password' => 'jzvhfjgnteedaqbd',
                'port' => '587',
                'encryption' => 'tls',
            ],
            'useFileTransport'=>false

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
