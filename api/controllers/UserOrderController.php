<?php

namespace api\controllers;

use common\models\Order;
use api\models\OrderSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class UserOrderController extends Controller
{
    /**
     * @var array the HTTP verbs that are supported by the collection URL
     */
    public $collectionOptions = ['GET', 'POST', 'HEAD', 'OPTIONS'];
    /**
     * @var array the HTTP verbs that are supported by the resource URL
     */
    public $resourceOptions = ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = [/*'view', */'create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className()
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => [/*'view', */'create', 'update', 'delete'],
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
        $searchModel = new OrderSearch();
        return $searchModel->search(['user_id' => $user_id]);
    }

    public function actionCreate()
    {
        $model = new Order();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->owner_id = Yii::$app->user->id;  //!берем владельца не из запроса, а через токен (Yii::$app->user->id)

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
//            $id = implode(',', array_values($model->getPrimaryKey(true)));
//            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function actionUpdate($id)
    {
        if(!$model = $this->findModel($id)) {
            throw new ServerErrorHttpException('Failed to update by NULL model '.$id);
        }

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($this->checkAccess('update', $model) && $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }

    public function actionView($id)
    {
//echo "actionView<pre>"; print_r($id); echo"</pre>"; die();      //DEBUG!

        if(!$model = $this->findModel($id)) {
            throw new ServerErrorHttpException('Failed to view by NULL model '.$id);
        }

//        if ($this->checkAccess('view', $model)) {
            return $model;
//        }
    }

    public function actionDelete($id)
    {
        if(!$model = $this->findModel($id))
            throw new ServerErrorHttpException('Failed to delete by NULL model '.$id);

        if ($this->checkAccess('delete', $model) && $model->delete())
            Yii::$app->getResponse()->setStatusCode(204);
        else
            throw new ServerErrorHttpException('Failed to delete the object.');
    }

/*    public function actionOptions($id = null)
    {
//echo "actionOptions<pre>"; print_r(Yii::$app->getRequest()->getMethod()); echo"</pre>"; die();      //DEBUG!

        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = $id === null ? $this->collectionOptions : $this->resourceOptions;
        $headers = Yii::$app->getResponse()->getHeaders();
        $headers->set('Allow', implode(', ', $options));
        $headers->set('Access-Control-Allow-Methods', implode(', ', $options));
    }*/

    public function verbs()
    {
        return [
            'index' => ['get'],
            'view' => ['get'],
            'create' => ['post'],
            'update' => ['put', 'patch', 'options'],
            'delete' => ['delete'],
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
//echo $model->owner_id."<pre>"; print_r(\Yii::$app->user->id); echo"</pre>"; die();      //DEBUG!

        if ($action === 'view' || $action === 'update' || $action === 'delete') {
            if ($model->owner_id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException(sprintf('checkAccess %s forbidden for target owner_id %s', $action, $model->owner_id));
            }
        }

        return true;
    }

    public function findModel($id)
    {
        return Order::findOne($id);
    }
}
