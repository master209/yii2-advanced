<?php

namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use api\models\UserSearch;
use yii\web\ForbiddenHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['index',];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create, update, delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new UserSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        return $searchModel->search();
    }
    
    public function verbs()
    {
        return [
            'index' => ['get', 'options'],
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
//echo "checkAccess user ID:<pre>"; print_r(\Yii::$app->user->id); echo"</pre>"; die();      //DEBUG!

        if (in_array($action, ['index'])) {
            if (!Yii::$app->user->can('administrator')) {
                throw  new ForbiddenHttpException('Forbidden.');
            }
        }
        return true;
    }

}
