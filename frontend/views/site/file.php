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

  var control = document.getElementById("fileform-file");
  var handler = $('form').attr('action');
  console.log('handler = ' + handler);

  var form = new FormData();
  console.log(control.files);
  form.append("file", control.files);

  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    console.log("Отправка завершена");
  };
  xhr.open("post", "/index.php?r=site/about", true);
  xhr.send(form);
/*
    $('form').submit(function(){
      // ... здесь обработка
        return false;
    });
*/
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
            <?php $form = ActiveForm::begin(['id' => 'file-form']); ?>

<?= $form->field($model, 'file')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary submit', 'name' => 'file-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
