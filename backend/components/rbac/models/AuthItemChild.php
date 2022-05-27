<?php

namespace backend\components\rbac\models;

use Yii;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 */
class AuthItemChild extends \yii\db\ActiveRecord
{

	public static function tableName()
	{
		return 'auth_item_child';
	}


	public function rules()
	{
		return [
			[['parent', 'child'], 'required'],
			[['parent', 'child'], 'string'],
			[['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child'], 'message' => 'The combination of Parent and Child has already been taken.'],
		];
	}


	public function attributeLabels()
	{
		return [
			'parent' => 'Parent',
			'child' => 'Child',
		];
	}

/*
	public function getItem()
	{
		return $this->hasOne(AuthItem::className(), ['name'=>'parent']);
	}
*/

}
