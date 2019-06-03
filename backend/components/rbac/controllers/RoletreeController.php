<?php

namespace backend\components\rbac\controllers;

use Yii;
use backend\components\rbac\models\AuthItem;


class RoletreeController extends \yii\web\Controller
{
	public function actionIndex()
	{
		$roles = AuthItem::find()
								->where(['type' => 1])
								->orderBy('name')
								->all();

		return $this->render('@backend/components/rbac/views/roletree/index', ['roles' => $roles]);
	}

}
