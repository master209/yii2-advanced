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
  var _csrf = $('form').find('input').filter('[name="_csrf-frontend"]').val();

/*
  var data = {
        "_csrf-frontend": _csrf,
        "fileform-file": control.files[0]
      }

  console.log('data = ', data);
  $.post(handler, {"_csrf": '123'})
  .done(function(res) {
    console.log('res: ', res);
  });
*/

/*
  var form = $('form'); 
  form.append("fileform-file", control.files[0]);
  var data = form.serialize();
  console.log('data = ', data);
  $.post(handler, $('form').serialize());
*/

  var http = new XMLHttpRequest();
  
      // Процесс загрузки
    if (http.upload && http.upload.addEventListener) {
    
        http.upload.addEventListener( // Создаем обработчик события в процессе загрузки.
        'progress',
        function(e) {
            if (e.lengthComputable) {
                console.log('addEventListener сколько байтов загружено: ', e.loaded)

                // e.loaded — сколько байтов загружено.
                // e.total — общее количество байтов загружаемых файлов.
                // Кто не понял — можно сделать прогресс-бар :-)
            }
         },
        false
        );
    
        http.onreadystatechange = function () {
            // Действия после загрузки файлов
            if (this.readyState == 4) { // Считываем только 4 результат, так как их 4 штуки и полная инфа о загрузке находится
                if(this.status == 200) { // Если все прошло гладко
    
                    // Действия после успешной загрузки.
                    // Например, так
                    console.log('onreadystatechange response: ', this)
                    // var result = $.parseJSON(this.response);
                    // можно получить ответ с сервера после загрузки.
    
                } else {
                    // Сообщаем об ошибке загрузки либо предпринимаем меры.
                }
            }
        };
    
        http.upload.addEventListener(
        'load',
        function(e) {
            // Событие после которого также можно сообщить о загрузке файлов.
            // Но ответа с сервера уже не будет.
            // Можно удалить.
        });
    
        http.upload.addEventListener(
        'error',
        function(e) {
            // Паникуем, если возникла ошибка!
        });
    }
/*
  http.onload = function() {
    console.log("Отправка завершена");
  };
*/
  var form = new FormData();
  console.log(control.files[0]);
  form.append("_csrf-frontend", _csrf);
  form.append("fileform-file", control.files[0]);

  http.open("POST", handler, true);
  http.send(form);


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
