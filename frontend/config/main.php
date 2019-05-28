<?php

$modules = array_merge(
    require __DIR__ . '/../../common/config/modules.php'
);
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'as globalAccess' => [
        'class' => 'common\behaviors\GlobalAccessBehavior',
        'rules' => [


            /* site */
            [
                'controllers' => ['site'],
                'allow' => true,
                'actions' => ['error', 'index', 'about', 'contact'],
                'roles' => ['?', '@'],
            ],
            [
                'controllers' => ['site'],
                'allow' => true,
                'actions' => ['login', 'signup'],
                'roles' => ['?'],
            ],
            [
                'controllers' => ['site'],
                'allow' => true,
                'actions' => ['logout'],
                'roles' => ['@'],
            ],
            [
                'controllers' => ['site'],
                'allow' => false,
            ],

            /* user */
/*            [
                'controllers' => ['user/user'],
                'allow' => true,
                'actions' => ['index','view'],
                'roles' => ['viewUser'],
            ],
            [
                'controllers' => ['user/user'],
                'allow' => true,
                'actions' => ['index','view','create','update'],
                'roles' => ['manageUser'],
            ],
            [
                'allow' => true,
                'actions' => ['index','view','create','update','delete'],
                'roles' => ['adminUser'],
            ],
            [
                'controllers' => ['user/user'],
                'allow' => false,
            ],*/


            /* rbac */
            [
                'controllers' => [
                    'rbac/user',
                    'rbac/role',
                    'rbac/permission',
                    'rbac/rule',
                    'rbac/assignment',
                    'rbac/roletree',
                    'rbac/route'
                ],
                'allow' => true,
                'roles' => ['adminRbac'],
            ],
            [
                'controllers' => [
                    'rbac/user',
                    'rbac/role',
                    'rbac/permission',
                    'rbac/rule',
                    'rbac/assignment',
                    'rbac/roletree',
                    'rbac/route'
                ],
                'allow' => false,
            ],

            /* all */
            [
                'roles' => ['user'],
                'allow' => true,
            ],

        ],
    ],

    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
        ]
    ],
    'modules' => $modules,
    'params' => $params,
];
