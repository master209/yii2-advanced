<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Create user form.
 */
class UserForm extends Model
{
	public $username;
	public $email;
	public $password;
	public $status;
	public $roles;

	private $model;


	public function rules()
	{
		return [
			['username', 'trim'],
			['username', 'required'],
			['username', 'match', 'pattern' => '#^[\w\._-]+$#i'],
			['username', 'unique',
				'targetClass' => User::className(),
				'filter' => function ($query) {
					if (!$this->getModel()->isNewRecord) {
						$query->andWhere(['not', ['id' => $this->getModel()->id]]);
					}
				},
                'message' => 'Данный логин уже используется'
			],
			['username', 'string', 'min' => 2, 'max' => 32],

			['email', 'trim'],
			//['email', 'required'],
			['email', 'email'],
			['email', 'unique',
				'targetClass' => User::className(),
				'filter' => function ($query) {
					if (!$this->getModel()->isNewRecord) {
						$query->andWhere(['not', ['id' => $this->getModel()->id]]);
					}
				}
			],
			['email', 'string', 'max' => 255],

			['password', 'required', 'on' => 'create'],
			['password', 'string', 'min' => 6, 'max' => 32],

			['status', 'integer'],
			['status', 'in', 'range' => array_keys(User::statuses())],
			['roles', 'each',
				'rule' => ['in', 'range' => ArrayHelper::getColumn(
					Yii::$app->authManager->getRoles(),
					'name'
				)],
			],
		];
	}


	public function attributeLabels()
	{
		return [
			'username' => 'Логин',
			'password' => 'Пароль',
			'email' => 'E-mail',
			'status' => 'Статус',
			'roles' => 'Роли',
		];
	}


	public function setModel($model)
	{
		$this->username = $model->username;
		$this->email = $model->email;
		$this->status = $model->status;
		$this->model = $model;
		$this->roles = ArrayHelper::getColumn(
			Yii::$app->authManager->getRolesByUser($model->getId()),	//SELECT `b`.* FROM `auth_assignment` `a`, `auth_item` `b` WHERE ((`a`.`item_name`=`b`.`name`) AND (`a`.`user_id`='5')) AND (`b`.`type`=1)
			'name'
		);

		return $this->model;
	}


	public function getModel()
	{
		if (!$this->model) {
			$this->model = new User();
		}

		return $this->model;
	}


	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function save()
	{
		if ($this->validate()) {
			$model = $this->getModel();
			$isNewRecord = $model->getIsNewRecord();
			$model->username = $this->username;
			$model->email = $this->email;
			$model->status = $this->status;
			if ($this->password) {
				$model->setPassword($this->password);
			}
			$model->generateAuthKey();
			if ($model->save() && $isNewRecord) {
				$model->afterSignup();
			}
			$auth = Yii::$app->authManager;
			$auth->revokeAll($model->getId());

			if ($this->roles && is_array($this->roles)) {
				foreach ($this->roles as $role) {
					$auth->assign($auth->getRole($role), $model->getId());
				}
			}

			return !$model->hasErrors();
		}

		return;
	}
}
