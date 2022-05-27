<?php

namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use common\models\User;
use common\models\UserForm;
use common\models\UserProfile;
use api\models\UserSearch;
use yii\web\ForbiddenHttpException;

class UserController extends Controller
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

        $behaviors['authenticator']['only'] = ['index', 'view', 'create', 'update', 'delete', 'statuses'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'update', 'delete', 'statuses'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $this->checkAccess('index');
        $searchModel = new UserSearch();
        return $searchModel->search();
    }

    public function actionUpdate($id)
    {
        $user = new UserForm();
        $user->setModel($this->findModel($id));

/*
 Array
(
    [username] => u2
    [email] => u2@mail.ru
    [password] =>
    [status] => 30
    [roles] => Array
        (
        )
)
 */

        $profile = UserProfile::findOne($id);
/*
 Array
(
    [user_id] => 12
    [firstname] => Н
    [lastname] => Ивлева
    [byfather] => А
    [birthday] =>
    [avatar_path] =>
    [gender] => 2
    [position] => Диспетчер
    [website] =>
    [other] =>
    [phone_mob] =>
)
 */

        if ($user->load(Yii::$app->getRequest()->getBodyParams(), '') && $profile->load(Yii::$app->getRequest()->getBodyParams(), '')) {
//echo "user:<pre>"; print_r($user); echo"</pre>"; die();      //DEBUG!
//echo "profile:<pre>"; print_r($profile->attributes); echo"</pre>"; die();      //DEBUG!
            $isValid = $user->validate();
            $isValid = $profile->validate() && $isValid;
            if ($isValid) {
                $user->save(false);
                $profile->phone_mob = preg_replace('/[+\s-\(\)]/', '',$profile->phone_mob);
                $profile->save(false);

                return [
                    'user' => 'isValid',
                    'profile' => 'isValid'
                ];
            } else {
                $errors = [];
                if(count($user->errors) > 0) {
                    foreach ($user->errors as $i => $err) {
                        $errors[$i] = $user->errors[$i];
                    }
                }
                if(count($profile->errors) > 0) {
                    foreach ($profile->errors as $i => $err) {
                        $errors[$i] = $profile->errors[$i];
                    }
                }
                return $errors;
            }
        }

/*        $roles = ArrayHelper::map(
            Yii::$app->authManager->getRoles(),
            'name', 'description'
        );
        asort($roles);*/

    }

    public function actionStatuses()    //statuses
    {
        $_statuses =  User::statuses();
        $statuses = [];
        foreach ($_statuses as $k=>$val) {
            $_arr['id'] = $k;
            $_arr['name'] = $val;
            array_push($statuses, $_arr);
        }
        return $statuses;
    }

    public function actionOptions()
    {
        //кажется, здесь может быть и пусто чтобы OPTIONS работал
    }

    public function verbs()
    {
        return [
            'index' => ['get', 'options'],
            'statuses' => ['get', 'options'],
            'create' => ['post'],
            'update' => ['put', 'patch', 'options'],
            'delete' => ['delete'],
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

    public function findModel($id)
    {
        return User::findOne($id);
    }
}
