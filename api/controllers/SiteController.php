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
            //копирую массив ошибок
            $errors = [];
            foreach ($model->errors as $i=>$err) {
                $errors[$i] = $model->errors[$i];
            }
//для проверки нескольких мессаг в одном поле
//$errors = ["password" => ["Incorrect username or password.","Еще мессага!"]];
//см. - front-vuetify\src\components\Auth\Login.vue - onSubmit ()
            //перевожу каждое сообщение об ошибке
            foreach ($errors as $key=>$err) {
                foreach ($err as $k=>$mes) {
                    $errors[$key][$k] = Yii::t('forms', $errors[$key][$k]);
                }
            }
            return $errors;
        }
    }

/*    protected function verbs()
    {
        return [
            'login' => ['options','post'],
        ];
    }*/
}
