<?php

namespace common\models;

use common\models\query\OrderQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Linkable;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $name
 * @property number $year
 *
 * @property User $user
 */
class Order extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order}}';
    }

    public function rules()
    {
        return [
            [['ad_id', 'name', 'phone'], 'required'],
            [['ad_id'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 16],
            [['done'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'ad_id',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'done' => 'Обработан',
        ];
    }

/*    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return OrderQuery
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

}
