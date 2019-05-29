<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\models\User;
use common\models\UserProfile;
use yii\widgets\MaskedInput;
use trntv\yii\datetime\DateTimeWidget;

/* @var $this yii\web\View */
/* @var $profile common\models\UserProfile */
/* @var $user backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */


$this->title = 'Редактирование пользователя: ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $profile->fullname;
?>
<div class="user-update">

	<?php $form = ActiveForm::begin() ?>

	<?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

	<?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>

	<?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

	<?= $form->field($user, 'status')->label(null)->radioList(User::statuses()) ?>

	<?= $form->field($user, 'roles')->checkboxList($roles) ?>

	<?= $form->field($profile, 'lastname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($profile, 'firstname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($profile, 'byfather')->textInput(['maxlength' => true]) ?>

	<?= $form->field($profile, 'phone_mob')->widget(MaskedInput::className(), [
		  'mask' => '+7 (999) 999-99-99',
	])->textInput(['placeholder'=>'+7 (999) 999-99-99','class'=>'']) ?>

	<?= $form->field($profile, 'birthday')->widget(
		DateTimeWidget::className(),
		[
			'phpDatetimeFormat' => 'yyyy-MM-dd',
		]
	) ?>

	<?= $form->field($profile, 'gender')->dropDownlist(
		[
			UserProfile::GENDER_MALE => 'Мужской',
			UserProfile::GENDER_FEMALE => 'Женский',
		],
		['prompt' => '']
	) ?>

	<?= $form->field($profile, 'position')->textInput(['maxlength' => true]) ?>

	<?= $form->field($profile, 'other')->textarea(['rows' => 6, 'maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end() ?>

</div>
