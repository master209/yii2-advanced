<?php

namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use api\models\LoginForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['create', 'update', 'delete', 'logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        return 'api';
    }

    public function actionLogin()
    {
//echo"actionLogin<pre>"; print_r(Yii::$app->request->bodyParams); echo"</pre>"; die();
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
    }

/*    protected function verbs()
    {
        return [
            'login' => ['options','post'],
        ];
    }*/
}
