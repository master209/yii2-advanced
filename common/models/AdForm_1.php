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
        echo "uploadFile<pre>"; print_r(pathinfo($this->file['name'])); echo"</pre>";   die();
/*
 uploadFile<pre>Array
(
    [dirname] => .
    [basename] => 1!.jpg
    [extension] => jpg
    [filename] => 1!
)
</pre>
 */
        if ($this->validate()) {
			if ($this->file['name'] != '') {
                $this->timeFile = date("-Y-m-d-H-i-s" );
                $this->fullName = $this['name'] .$this->timeFile. '.' . $this->file->extension;		// 1.jpg
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
