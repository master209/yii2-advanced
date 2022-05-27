<?php

namespace common\models\query;

use yii\db\ActiveQuery;

class AdQuery extends ActiveQuery
{
    public function forUser($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    public function latest($limit)
    {
        return $this->limit($limit)->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @inheritdoc
     * @return \common\models\Ad[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Ad|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
