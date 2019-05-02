<?php
namespace api\controllers;

use common\models\Ad;
use api\models\AdSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use frontend\models\FileForm;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class UserAdController extends Controller
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

        $behaviors['authenticator']['only'] = ['index', 'view', 'create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
/*            'cors' => [                         //https://www.yiiframework.com/doc/api/2.0/yii-filters-cors
                // restrict access to
                'Origin' => ['http://localhost:8080', 'http://localhost:8081'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],

                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['X-Wsse'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ]*/
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'update', 'delete'],
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
        $searchModel = new AdSearch();
        return $searchModel->search(['user_id' => $user_id]);
    }

    public function actionCreate()
    {
echo "actionCreate<pre>"; print_r(Yii::$app->getRequest()->getBodyParams()); echo"</pre>";   die();
        $model = new Ad();

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

    public function actionLoadFile($id = null)
    {
        if(!$id) {
            throw new ServerErrorHttpException('Failed to load file by NULL ad_id');
        }
echo "actionLoadFile<pre>"; print_r($id); echo"</pre>";   die();

        $model = new FileForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//echo "actionAbout<pre>"; print_r($model->attributes); echo"</pre>";   die();
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->uploadFile()) {
                $model->save(false);
            }
        } else
            return $this->render('file', [		// 'about'
                'model' => $model,
            ]);
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

    /*  /users/3/ads/31
     * Это закрытый роут. Доступен только для тех ролей, которым разрешено знать,
     * что владельцем ads/31 является users/3
     */
    public function actionView($id)
    {
        if(!$model = $this->findModel($id)) {
            throw new ServerErrorHttpException('Failed to view by NULL model '.$id);
        }

        if ($this->checkAccess('view', $model)) {
            return $model;
        }
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

    public function actionOptions($id = null)
    {
//echo "actionOptions<pre>"; print_r(Yii::$app->getRequest()->getMethod()); echo"</pre>"; die();      //DEBUG!

        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = $id === null ? $this->collectionOptions : $this->resourceOptions;
        $headers = Yii::$app->getResponse()->getHeaders();
        $headers->set('Allow', implode(', ', $options));
        $headers->set('Access-Control-Allow-Methods', implode(', ', $options));
    }

    public function verbs()
    {
        return [
            'index' => ['get', 'options'],
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
        return Ad::findOne($id);
    }
}
