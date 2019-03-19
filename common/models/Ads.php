<?php

namespace common\models;

use common\models\query\AdsQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Linkable;

/**
 * This is the model class for table "{{%ads}}".
 *
 * @property integer $id
 * @property string $name
 * @property number $year
 *
 * @property User $user
 */
class Ads extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%ads}}';
    }

    public function rules()
    {
        return [
            [['owner_id', 'title', 'description'], 'required'],
            [['owner_id'], 'number'],
            [['title'], 'string', 'max' => 100],
            [['description'], 'string'],
            [['image_src'], 'string', 'max' => 500],
            [['promo'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Автор',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'image_src' => 'Картинка',
            'promo' => 'Promo',
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
     * @return AdsQuery
     */
    public static function find()
    {
        return new AdsQuery(get_called_class());
    }

}
