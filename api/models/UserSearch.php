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
//echo "search<pre>"; print_r($params); echo"</pre>"; die();      //DEBUG!

        $query = User::find()->joinWith(['userProfile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 30,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
//            'action_at' => $this->action_at,
        ]);

        return $dataProvider;
    }

    public function formName()
    {
        return 's';
    }
}
