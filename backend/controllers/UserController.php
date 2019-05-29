<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;
use common\models\UserProfile;
use backend\models\UserSearch;
use backend\models\UserForm;

/**
 * Class UserController.
 */
class UserController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

/*		$dataProvider->sort = [
			'defaultOrder' => ['created_at' => SORT_DESC],
		];*/

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserForm();
		$model->setScenario('create');

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		$roles = ArrayHelper::map(
				Yii::$app->authManager->getRoles(), 
				'name', 'description'
			);
		asort($roles);

		return $this->render('create', [
			'model' => $model,
			'roles' => $roles,
		]);
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$user = new UserForm();
		$user->setModel($this->findModel($id));
		$profile = UserProfile::findOne($id);
		$profile->phone_mob = mb_substr($profile->phone_mob, 1);

		if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
			$isValid = $user->validate();
			$isValid = $profile->validate() && $isValid;
			if ($isValid) {
				$user->save(false);
				$profile->phone_mob = preg_replace('/[+\s-\(\)]/', '',$profile->phone_mob);
				$profile->save(false);

				return $this->redirect(['index']);
			}
		}

		$roles = ArrayHelper::map(
				Yii::$app->authManager->getRoles(), 
				'name', 'description'
			);
		asort($roles);

		return $this->render('update', [
			'user' => $user,
			'profile' => $profile,
			'roles' => $roles,
		]);
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		if ($id == Yii::$app->user->id) {
			Yii::$app->session->setFlash('error', 'Вы не можете удалить собственную учетную запись.');
		} else {
/*		физическое удаление юзера
			$avatar = UserProfile::findOne($id)->avatar_path;
			if ($avatar) {
				unlink(Yii::getAlias('@storage/avatars/' . $avatar));
			}
			Yii::$app->authManager->revokeAll($id);
			$this->findModel($id)->delete();
			UserProfile::findOne($id)->delete();
*/
			$user = $this->findModel($id);
			$user->status = User::STATUS_DELETED;		//не удаляю юзера физически, а лишь помечаю удаленным сменой статуса
			$user->save(false);

			Yii::$app->session->setFlash('success', 'Пользователь удален');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
