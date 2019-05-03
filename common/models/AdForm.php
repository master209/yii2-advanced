<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AdForm extends ActiveRecord
{
    public $file;
    public $timeFile;
    public $fullName;
    public $pathFile = '/file/';

	public static function tableName()
	{
        return '{{ad}}';

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
            if ($this->file->baseName.$this->file->extension != '') {
                $this->timeFile = date("-d-m-Y_H-i-s" );
                $this->fullName = $this->file->baseName . $this->timeFile. '.' . $this->file->extension;
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
