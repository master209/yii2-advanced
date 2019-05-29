<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property integer $birthday
 * @property string $avatar_path
 * @property integer $gender
 * @property string $website
 * @property string $other
 */
class UserProfile extends ActiveRecord
{
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;

	private $fullname;				//Пупкин Иван Иванович
	private $shortname;				//Пупкин Иван
	private $fio;							//Пупкин И.И.

	public static function tableName()
	{
		return '{{user_profile}}';
	}


	public function rules()
	{
		return [
			[['firstname','lastname','gender'], 'required'],
			['birthday', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
			[['phone_mob'], 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 
					'message' => 'Некорректный номер телефона'],		//+7 (999) 999-99-99
			['gender', 'in', 'range' => [null, self::GENDER_MALE, self::GENDER_FEMALE]],
			['website', 'trim'],
			['website', 'url', 'defaultScheme' => 'http', 'validSchemes' => ['http', 'https']],
			['other', 'string', 'max' => 1024],
			[['firstname', 'lastname', 'byfather', 'avatar_path', 'website', 'position'], 'string', 'max' => 255],
			['firstname', 'match', 'pattern' => '/^[a-zа-яё]+$/iu'],
			['lastname', 'match', 'pattern' => '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu'],
			['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
			[['firstname', 'lastname', 'byfather', 'birthday', 'phone_mob', 'gender', 'website', 'position', 'other'], 'default', 'value' => null],
		];
	}

	public function attributeLabels()
	{
		return [
			'lastname' => 'Фамилия',
			'firstname' => 'Имя',
			'byfather' => 'Отчество',
			'phone_mob' => 'Телефон мобильный',
			'birthday' => 'Дата рождения',
			'avatar_path' => 'Фото',
			'gender' => 'Пол',
			'position' => 'Должность',
			'website' => 'Веб-сайт',
			'other' => 'Доп инфо',
		];
	}

// Возвращает ФИО в формате Иванов Иван Иванович
	public function getFullname()
	{
		$this->fullname = mb_convert_case(($this->lastname.' '.$this->firstname.' '.$this->byfather), MB_CASE_TITLE, 'UTF-8');
		return $this->fullname;
	}


// Возвращает ФИО в формате Иванов Иван
	public function getShortname()
	{
		$this->shortname = mb_convert_case(($this->lastname.' '.$this->firstname), MB_CASE_TITLE, 'UTF-8');
		return $this->shortname;
	}


// Возвращает ФИО в формате Иванов И.И.
	public function getFio()
	{
		$this->fio = $this->lastname.' '.mb_strtoupper(mb_substr($this->firstname,0,1,'utf-8'),'utf-8').'.'.mb_strtoupper(mb_substr($this->byfather,0,1,'utf-8'),'utf-8').'.';
		return $this->fio;
	}



// Возвращает ФИО в формате Иванов Иван по переданному логину вида malaya.oa
	public static function userNameByLogin($login = null)
	{
		if($login === null) {	//забыли передать логин
			return $false;
		} else {
			$user = User::findOne(['username' => $login]);
			$userProfile = UserProfile::findOne($user->id);

			return ($userProfile === null) ? false : mb_convert_case(($userProfile->lastname.' '.$userProfile->firstname), MB_CASE_TITLE, 'UTF-8');
		}	
	}



	/**
	 * возвращает массив всех юзеров, которые есть в табл. User
	 * формат возвращаемого массива (для выпадающего списка):
	 *  
	 * Array
	 * (
	 *     [minaev.sv] => Минаев Сергей
	 *     [rodina.ov] => Родина Ольга
	 * )
	 */
	public static function getUsersAll()
	{
		$users = User::find()->all();
		foreach($users as $user) {
			$profile = UserProfile::findOne($user->id);
			$usersAll[$user->username] = $profile->userNameByLogin($user->username);
		}
		asort($usersAll);

		return $usersAll;
	}


	/**
	 * возвращает массив юзеров, которым назначена роль $roleName
	 * формат возвращаемого массива (для выпадающего списка):
	 *  
	 * Array
	 * (
	 *     [minaev.sv] => Минаев Сергей
	 *     [rodina.ov] => Родина Ольга
	 * )
	 */
	public static function getUsersByRole($roleName)
	{
		$userIdsByRole = Yii::$app->authManager->getUserIdsByRole($roleName);

		foreach($userIdsByRole as $userId) {
			$user = User::findOne($userId);
			$profile = UserProfile::findOne($userId);
			if(Yii::$app->getRequest()->get()['appeals']!==null)
				$usersByRole[$user->username] = $profile->lastname;
			else
				$usersByRole[$user->username] = $profile->userNameByLogin($user->username).' /'.$profile->position.'/';
		}
		asort($usersByRole);

		return $usersByRole;
	}


	/**
	 * возвращает массив юзеров, которым назначена роль $roleName
	 * формат возвращаемого массива (для выпадающего списка):
	 *  
	 * Array
	 * (
	 *     [5] => Минаев Сергей
	 *     [20] => Родина Ольга
	 * )
	 *
	 * если передано $short=true, то возвращать юзеров без должностей
	 */
	public static function getUsersIdByRole($roleName, $short=false)
	{
		$userIdsByRole = Yii::$app->authManager->getUserIdsByRole($roleName);

		foreach($userIdsByRole as $userId) {
			$user = User::findOne($userId);
			$profile = UserProfile::findOne($userId);
			if(Yii::$app->getRequest()->get()['appeals']!==null) {
				$usersByRole[$user->id] = $profile->lastname;
			} else {
				$usersByRole[$user->id] = $profile->userNameByLogin($user->username);
				if(!$short) {
					$usersByRole[$user->id] .= ' /'.$profile->position.'/';
				}
			}
		}
		asort($usersByRole);

		return $usersByRole;
	}


}
