<?php

namespace DevGroup\Media\FileType;

use DevGroup\Media\helpers\AttachmentHelper;
use DevGroup\Media\MediaModule;
use DevGroup\Media\models\File;
use DevGroup\Media\models\MediaFileType;
use yii\base\Object;

abstract class AbstractFileType extends Object
{
    /**
     * @var MediaFileType
     */
    protected $fileTypeModel;

    public function setFileTypeModel(MediaFileType &$model)
    {
        $this->fileTypeModel = &$model;
    }

    /**
     * @param string $filename
     *
     * @return bool Whether this file type relates to filename.
     */
    abstract public function checkFileType($filename);

    public function beforeUpload($filename, $tempName)
    {

    }

    public function modelCreated(File $model, $tempName)
    {
        AttachmentHelper::fillPublicUrl($model);
    }

    public function modelUpdated(File $model)
    {

    }
}
