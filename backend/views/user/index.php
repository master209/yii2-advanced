<?php

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\User;
use common\models\UserProfile;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Html::a('Добавить', ['create'], ['title' => 'Добавить пользователя', 'class' => 'btn btn-success']) ?></p>

	<div class="right filter-reset"><?= Html::a('', ['index'], ['class'=>'filter-reset', 'title'=>'сбросить фильтры']) ?></div>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			[
				'attribute'=>'id', 
				'contentOptions' =>['class' => 'id'],
			],
			'username',
			//'email',
			[
				'attribute'=>'Имя',
				'value' => function ($model) {
					return UserProfile::findOne($model->id)->shortname;
				},
			],
			[
				'attribute' => 'phone_mob',
				'label' => 'Мобильный тел.',
				'value' => 'userProfile.phone_mob',
			],
            'email',
			[
				'attribute' => 'status',
				'value' => function ($model) {
					return User::statuses($model->status);
				},
				'filter' => User::statuses(),
			],
			//'ip',
			// 'created_at',
			// 'updated_at',
			// 'action_at',

			[
				'class' => 'yii\grid\ActionColumn',
//				'template' => '{update}{delete}',
			],
		],
	]) ?>

</div>
