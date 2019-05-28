<?php
return [
    'language'=>'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'common' => 'common.php',
                        'backend' => 'backend.php',
                        'frontend' => 'frontend.php',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
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
                    'allow' => true,
                    'roles' => ['user'],
                ],

            ],
        ],

    'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'site/*',
            ]
        ],
];
