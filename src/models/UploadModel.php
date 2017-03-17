<?php

namespace DevGroup\Media\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadModel extends Model
{
    public $model_class_name_hash;
    public $model_id;
    public $relation_name;
    /** @var  UploadedFile[] */
    public $files;

    public function rules()
    {
        return [
            ['files', 'file', 'maxFiles' => 0],
            [['model_class_name_hash', 'relation_name'], 'string'],
            ['model_id', 'integer'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
