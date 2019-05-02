<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AdForm extends ActiveRecord
{
    public $fileInfo = [];     //для распарсенного файла
    public $file;
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
            if ($this->fileInfo['filename'].$this->fileInfo['extension'] != '') {
                $this->timeFile = date("-d-m-Y_H-i-s" );
                $this->fullName = $this->fileInfo['filename'] .$this->timeFile. '.' . $this->fileInfo['extension'];
                $path = Yii::getAlias('@frontend/web'.$this->pathFile);
                $this->image_src = $this->fullName;

                if($this->file->saveAs($path.$this->fullName, false)) {
                }
                return true;
            }
        }
		return false;
	}

}
