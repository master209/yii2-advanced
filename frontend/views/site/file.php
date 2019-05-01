<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$script = <<< JS
$(function () {
  //..........
});

$('button.submit').on('click', function(e) {
  console.log('submit clicked!');
  e.preventDefault();

  var control = document.getElementById("file");
  var handler = $('form').attr('action');
  var _csrf = $('form').find('input').filter('[name="_csrf-frontend"]').val();

  var http = new XMLHttpRequest();
  http.onload = function() {
    console.log("Отправка завершена");
  };
  var form = new FormData();
  console.log(control.files[0]);
  form.append("_csrf-frontend", _csrf);
  form.append("file", control.files[0]);

  http.open("POST", handler, true);
  http.send(form);
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);


$this->title = 'File';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-file">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'file-form',
                        'accept' => 'image/*',
                        'enctype' => 'multipart/form-data',
                     ]
            ]); ?>

<?= $form->field($model, 'file')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary submit', 'name' => 'file-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
