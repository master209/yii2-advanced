<?php

namespace api\controllers;

use common\models\Ad;
use api\models\AdSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class UserAdController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex($user_id = null)
    {
//            echo "actionIndex user_id<pre>"; print_r($user_id); echo"</pre>"; die();
        $searchModel = new AdSearch();
        return $searchModel->search(['user_id' => $user_id]);
    }

    public function actionCreate()
    {
        $model = new Ad();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->owner_id = Yii::$app->user->id;  //!берем владельца не из запроса, а через токен (Yii::$app->user->id)
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function actionView($id)
    {
        echo "actionView id<pre>"; print_r($id); echo"</pre>"; die();
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'create' => ['post'],
            'update' => ['put', 'patch'],
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
//        echo "checkAccess action<pre>"; print_r($action); echo"</pre>"; die();
    }
}
