<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    //    'defaultRoute' => 'site/about',
    //'catchAll' => ['site/offline'],
    'id'                => 'blonder413',
    'language'          => 'es-CO',
    'name'              => 'Blonder413 basic',
    'sourceLanguage'    => 'en-US',
    //    'layout'    => 'navidad/main',
    'timeZone'          => 'America/Bogota',
    'version'           => '0.1',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@public'       => Yii::$app->homeUrl . 'web',
        '@bitbucket'    => 'https://bitbucket.org/blonder413/',
        '@delicious'    => 'https://delicious.com/blonder413',
        '@dribbble'     => 'https://dribbble.com/blonder413',
        '@facebook'     => 'https://www.facebook.com/blonder413',
        '@github'       => 'https://github.com/blonder413/',
        '@gitlab'       => 'https://gitlab.com/u/blonder413',
        '@google+'      => 'https://plus.google.com/u/0/+JonathanMoralesSalazar',
        '@lastfm'       => 'http://www.last.fm/es/user/blonder413',
        '@linkedin'     => 'https://www.linkedin.com/in/blonder413',
        '@twitter'      => 'https://twitter.com/blonder413',
        '@vimeo'        => 'https://vimeo.com/blonder413',
        '@youtube'      => 'https://www.youtube.com/channel/UCOBMvNSxe08V5E9qExfFt4Q',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'KI_NyXUohWHU_iOX2oBmNUfighdlAncd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],// add authManager to /config/console.php too
        'authManager'       => [
            'class'         => 'yii\rbac\DbManager',
            'defaultRoles'  => ['guest'],
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'articulo/<slug>'           => 'site/article',
                'articulo/descarga/<slug>'  => 'site/download',
                'categoria/<slug>'          => 'site/category',
                'curso/index'               => 'site/all-courses',
                'curso/<slug>'              => 'site/course',
                'etiqueta/<tag>'            => 'site/tag',
                'acerca'                    => 'site/about',
                'home'                      => 'site/index',
                'contacto'                  => 'site/contact',
                'login'                     => 'site/login',
                "registro"                  => "site/signup",
                'pdf/<slug>'                => 'site/pdf',
                'portafolio'                => 'site/portfolio',
                'en-vivo'                   => 'site/streaming',
                'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',

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
