<?php

namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use common\models\User;
use api\models\LoginForm;
use api\models\SignupForm;
use yii\web\ForbiddenHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['login', 'signup', 'check-identity'],
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
            foreach ($model->errors as $i => $err) {
                $errors[$i] = $model->errors[$i];
            }
//для проверки нескольких мессаг в одном поле
//$errors = ["password" => ["Incorrect username or password.","Еще мессага!"]];
//см. - front-vuetify\src\components\Auth\Login.vue - onSubmit ()
            //перевожу каждое сообщение об ошибке
            foreach ($errors as $key => $err) {
                foreach ($err as $k => $mes) {
                    $errors[$key][$k] = Yii::t('forms', $errors[$key][$k]);
                }
            }
            return $errors;
        }
    }

    public function actionSignup()
    {
echo"actionSignup<pre>"; print_r(Yii::$app->request->bodyParams); echo"</pre>"; die();
        $model = new SignupForm();
//        if ($model->load(Yii::$app->request->post())) {
        if ($model->load(Yii::$app->request->bodyParams, '')) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

    }

    public function actionCheckIdentity()   //check-identity
    {
        if ($user = User::findIdentityByAccessToken(Yii::$app->request->bodyParams['token']))
            return ['user_id' => $user->id, 'token' => $user->getValidToken()->token];
        else
            throw new ForbiddenHttpException(sprintf('check-identity forbidden: token is expiered'));
    }

        protected function verbs()
        {
            return [
                'login' => ['options','post'],
                'check-identity' => ['options','post'],
            ];
        }
}
