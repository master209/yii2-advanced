<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'forms' => 'forms.php',
                    ],
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'application/xml' => 'yii\web\XmlParser',
            ],
        ],
/*        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],*/
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'auth' => 'site/login',

                'GET profile' => 'profile/index',
                'PUT,PATCH profile' => 'profile/update',

//                'GET posts' => 'post/index',
//                'PUT,PATCH posts' => 'post/update',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'post'],

//                'GET cars' => 'car/index',
//                'PUT,PATCH cars' => 'car/update',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'car'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'ad'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'order'],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user-ads',
                    'patterns' => [
                        'GET users_/{id}/ads' => 'index',
/*                        'tokens' => [
                            '{id}' => '<id:\w+>'
                        ],*/
                    ],
                ],

/*                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['needController'],
                    'patterns' => [
                        'PUT,PATCH {id}/update' => 'update',
                        'DELETE {id}/delete' => 'delete',
                        'GET,HEAD {id}' => 'view',
                        'POST {id}/create' => 'create',
                        'GET,HEAD' => 'index',
                        '{id}' => 'options',
                        '' => 'options',
                    ],
                ],*/
        ],
    ],
    'params' => $params,
];
