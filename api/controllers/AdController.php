<?php

namespace api\controllers;

use api\models\AdSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class AdController extends ActiveController
{
    public $modelClass = 'common\models\Ad';

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

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass;
        $req = Yii::$app->getRequest()->getBodyParams();
/*Array     $req
(
    [owner_id] => 5                              //владельца могут прислать прямо в запросе (В Т.Ч. ПОДДЕЛАННОМ!)
    [title] => рекл-4
    [description] => dcfs
    [promo] => 0
)*/
        $model->load($req, '');
        $model->owner_id = Yii::$app->user->id;  //!НО МЫ берем владельца не из запроса, а через токен (Yii::$app->user->id)
        if ($this->checkAccess('create', $model) && $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function prepareDataProvider()
    {
        $searchModel = new AdSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
//        echo $model->owner_id."<pre>"; print_r(\Yii::$app->user->id); echo"</pre>"; die();      //DEBUG!

        if ($action === 'create') { //если хитрый юзер подделывает запрос и передает не свой owner_id (взятый через токен)
            if ($model->owner_id !== \Yii::$app->user->id) {
/*                if (!Yii::$app->user->can(Rbac::ADMIN_POST, ['post' => $model])) {
                    throw new ForbiddenHttpException(sprintf('%s forbidden.', $action));
                }*/
                throw new ForbiddenHttpException(sprintf('%s forbidden.', $action));
            }
        }

        if ($action === 'update' || $action === 'delete') {
            if ($model->owner_id !== \Yii::$app->user->id)
                throw new ForbiddenHttpException(sprintf('You can only %s AD which you yourself created.', $action));
        }

        return true;
    }
}