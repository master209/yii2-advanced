<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'rbac' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'roletree' => [
                    'class' => 'backend\components\rbac\controllers\RoletreeController',		//назначается контроллер для роута /rbac/roletree
                ],
            ],

        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
    ],

    'as globalAccess' => [
        'class' => 'common\behaviors\GlobalAccessBehavior',
        'rules' => [


            //site
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

            //user
            [
                'controllers' => ['user'],
                'allow' => true,
                'actions' => ['index','view'],
                'roles' => ['viewUser'],
            ],
            [
                'controllers' => ['user'],
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
                'controllers' => ['user'],
                'allow' => false,
            ],

            //rbac
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

            /*                //ads
                            [
                                'controllers' => ['ads'],
                                'allow' => true,
                                'actions' => ['index','load-file'],
                                'roles' => ['*'],
                            ],*/

            //all
            [
                'allow' => true,
                'roles' => ['user'],
            ],

        ],
    ],

    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'user/*',
            'rbac/*',
//                'ads/*',
        ]
    ],

    'params' => $params,
];
