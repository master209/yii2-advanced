<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class FileForm extends ActiveRecord
{
    public $file;
/*
yii\web\UploadedFile Object
(
    [name] => 1!.jpg
    [tempName] => /var/www/p324657/data/mod-tmp/phpjC3cEV
    [type] => image/jpeg
    [size] => 7370
    [error] => 0
)*/

	public $timeFile;
	public $fullName;
	public $pathFile = '/file/'; //Yii::getAlias('@frontend/file');

	public static function tableName()
	{
		return 'files';
	}

    public function rules()
    {
        return [
			[['file_name'], 'string', 'max' => 255],
			[['file'], 'file'],
        ];
    }

	public function uploadFile()
	{
		if ($this->validate()) {
			if ($this->file->baseName.$this->file->extension != '') {
                $this->timeFile = date("-Y-m-d-H-i-s" );
                $this->fullName = $this->file->baseName .$this->timeFile. '.' . $this->file->extension;		// 1.jpg
                $path = Yii::getAlias('@frontend/web'.$this->pathFile);		// /var/www/p324657/data/www/yii2-advanced.cyberdevel.ru/frontend/web/file
                $this->file_name = $this->fullName;

/*
 !!! \vendor\yiisoft\yii2\web\UploadedFile.php -

    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            if ($deleteTempFile) {

//////////////////////  !!!!!!!!!!!!!!!!!!
                return move_uploaded_file($this->tempName, $file);      !!!

http://www.php.su/move_uploaded_file

move_uploaded_file -- Перемещает загруженный файл в новое место

Эта функция проверяет, является ли файл filename загруженным на сервер (переданным по протоколу HTTP POST). Если файл действительно загружен на сервер, он будет перемещён в место, указанное в аргументе destination.

Если filename не является загруженным файлов, никаких действий не предпринимается и move_uploaded_file() возвращает FALSE.


//////////////////////  !!!!!!!!!!!!!!!!!!
            } elseif (is_uploaded_file($this->tempName)) {              !!!

http://www.php.su/is_uploaded_file

is_uploaded_file -- Определяет, был ли файл загружен при помощи HTTP POST

Возвращает TRUE, если файл filename был загружен при помощи HTTP POST. Это полезно, чтобы убедиться в том, что злонамеренный пользователь не пытается обмануть скрипт так, чтобы он работал с файлами, с которыми работать не должен -- к примеру, /etc/passwd.

Такие проверки особенно полезны, если существует вероятность того, что операции над файлом могут показать его содержимое пользователю или даже другим пользователям той же системы.

Для правильной работы, функции is_uploaded_file() нужен аргумент вида $_FILES['userfile']['tmp_name'], - имя закачиваемого файла на клиентской машине $_FILES['userfile']['name'] не подходит.

                return copy($this->tempName, $file);
            }
        }

        return false;
    }

 */
                if($this->file->saveAs($path.$this->fullName, false)) {
                }
                return true;
			}
		}
		return false;
	}

}
