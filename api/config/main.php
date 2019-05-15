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
            'showScriptName' => false,
            'rules' => [

                '' => 'site/index',

                'auth' => 'site/login',
                'signup' => 'site/signup',

                'POST,OPTIONS check-identity' => 'site/check-identity',

                'GET profile' => 'profile/index',
                'PUT,PATCH profile' => 'profile/update',

//                'GET posts' => 'post/index',
//                'PUT,PATCH posts' => 'post/update',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'post'],

//                'GET cars' => 'car/index',
//                'PUT,PATCH cars' => 'car/update',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'car'],

                ['class' => 'yii\rest\UrlRule', 'controller' => 'ad'],
                'POST ads/load-file/<id:\d+>' => 'ad/load-file',
                'OPTIONS ads/load-file/<id:\d+>' => 'ad/options',

                ['class' => 'yii\rest\UrlRule', 'controller' => 'order'],
                'PUT,PATCH orders/<order_id:\d+>/mark-done' => 'order/mark-done',
                'OPTIONS orders/<order_id:\d+>/mark-done' => 'order/options',

//                '<_c:[\w-]+>/<id:\d+>/<_a:[\w-]+>' => '<_c>/<_a>',
//                '<controller:\w+>/<id:\d+>/<action:\w+>' => '<controller>/<action>',

                ['class' => 'yii\rest\UrlRule', 'controller' => 'user-ad'],
                'GET users/<user_id:\d+>/ads' => 'user-ad/index',
                'POST users/<user_id:\d+>/ads' => 'user-ad/create',
                'GET users/<user_id:\d+>/ads/<id:\d+>' => 'user-ad/view',
                'PUT,PATCH users/<user_id:\d+>/ads/<id:\d+>' => 'user-ad/update',
                'DELETE users/<user_id:\d+>/ads/<id:\d+>' => 'user-ad/delete',
                'OPTIONS users/<user_id:\d+>/ads/<id:\d+>' => 'user-ad/options',
                'OPTIONS users/<user_id:\d+>/ads' => 'user-ad/options',

                ['class' => 'yii\rest\UrlRule', 'controller' => 'user-order'],
                'GET users/<user_id:\d+>/orders' => 'user-order/index',
//                'POST users/<user_id:\d+>/orders' => 'user-order/create',
                'GET users/<user_id:\d+>/orders/<order_id:\d+>' => 'user-order/view',
                'PUT,PATCH users/<user_id:\d+>/orders/<order_id:\d+>/' => 'user-order/update',
//                'DELETE users/<user_id:\d+>/orders/<order_id:\d+>' => 'user-order/delete',
                'OPTIONS users/<user_id:\d+>/orders' => 'user-order/options',


//                'users/<user_id:\d+>/ads/<id:\d+>/<action:\w+>' => 'user-ad/<action>',

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
    ],
    'params' => $params,
];