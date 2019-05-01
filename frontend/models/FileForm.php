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

    public function formName()	//не использовать префиксы в именах полей на форме
    {
        return '';
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
                $path = Yii::getAlias('@frontend/web'.$this->pathFile);
                $this->file_name = $this->fullName;

                if($this->file->saveAs($path.$this->fullName, false)) {
                }
                return true;
			}
		}
		return false;
	}

}
