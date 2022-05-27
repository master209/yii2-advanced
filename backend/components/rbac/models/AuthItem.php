<?php

namespace backend\components\rbac\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class AuthItem extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'auth_item';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'type'], 'required'],
			[['type', 'created_at', 'updated_at'], 'integer'],
			[['description', 'rule_name', 'data'], 'string'],
			[['name'], 'string', 'max' => 64],
			[['name'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => 'Name',
			'type' => 'Type',
			'description' => 'Description',
			'rule_name' => 'Rule Name',
			'data' => 'Data',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}
}
