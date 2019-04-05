<?php

namespace api\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

class OrderSearch extends Order
{
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
        $query = Order::find()->joinWith('ad', false);

        if(isset($params['user_id'])) {
            $query->where(['owner_id' => $params['user_id']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }

    public function formName()
    {
        return 's';
    }
}
