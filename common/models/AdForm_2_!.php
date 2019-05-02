<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AdForm extends ActiveRecord
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
        return '{{ad}}';

    }

    public function formName()	//не использовать префиксы в именах полей на форме
    {
        return 'ad-form';
    }


    public function rules()
    {
        return [
            [['image_src'], 'string', 'max' => 500],
            [['file'], 'file'],
        ];
    }

	public function uploadFile()
	{
        if ($this->validate()) {
            if ($this->file['filename'].$this->file['extension'] != '') {
                $this->timeFile = date("-Y-m-d-H-i-s" );
                $this->fullName = $this->file['filename'] .$this->timeFile. '.' . $this->file['extension'];		// 1.jpg
                $path = Yii::getAlias('@frontend/web'.$this->pathFile);
                $this->image_src = $this->fullName;
//echo "uploadFile<pre>"; print_r($path.$this->fullName); echo"</pre>";   die();
//echo ($path.$this->fullName);  die();

                if($this->file->saveAs($path.$this->fullName, false)) {
                }
                return true;
            }
        }
		return false;
	}

}
