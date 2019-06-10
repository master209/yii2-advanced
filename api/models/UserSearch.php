<?php

namespace api\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

class UserSearch extends User
{
    public $phone_mob;

    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->joinWith(['userProfile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function formName()
    {
        return 's';
    }
}
