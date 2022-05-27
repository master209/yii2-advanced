<?php

namespace common\models;

use common\models\query\CarQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Linkable;

/**
 * This is the model class for table "{{%car}}".
 *
 * @property integer $id
 * @property string $name
 * @property number $year
 *
 * @property User $user
 */
class Car extends ActiveRecord      //implements Linkable
{
    public static function tableName()
    {
        return '{{%car}}';
    }

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['year'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'year' => 'Year',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return CarQuery
     */
    public static function find()
    {
        return new CarQuery(get_called_class());
    }

/*    public function extraFields()
    {
        return [
            'author' => 'user',
        ];
    }

    public function getLinks()
    {
        return [
            'self' => Url::to(['car/view', 'id' => $this->id], true),
        ];
    }
*/
}
