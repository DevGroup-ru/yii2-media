<?php

namespace DevGroup\Media\models;

use creocoder\flysystem\Filesystem;
use DevGroup\Media\helpers\FsHelper;
use DevGroup\Media\MediaModule;
use DevGroup\Media\UrlProvider\AbstractUrlProvider;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadModel extends Model
{
    public $model;
    public $model_id;
    public $relation_name;
    /** @var  UploadedFile[] */
    public $files;

    public $filesIds;

    public function rules()
    {
        return [
            ['files', 'file', 'maxFiles' => 0],
            [['model', 'relation_name', 'model_id'], 'string'],
        ];
    }

    public function upload()
    {
        $result = true;
        $this->filesIds = [];

        if ($this->validate()) {
            $fileSystem = MediaModule::module()->defaultFileSystem();

            $pathPrefix = $this->model . '/'
                . $this->model_id;

            $pathPrefix = $this->model . '/' . FsHelper::makeFolders($pathPrefix, 2) . $this->model_id;

            foreach ($this->files as $file) {
                $fileType = static::beforeFileUpload($file);

                $uploadResult = static::uploadFile(
                    $file,
                    $pathPrefix,
                    $fileSystem
                );

                $result = $result && ($uploadResult !== false);

                if ($uploadResult !== false) {
                    $folder = Folder::ensureFolder($pathPrefix, MediaModule::module()->defaultTree);
                    $fileModel = File::ensureFile($folder, $file->baseName . '.' . $file->extension);
                    $fileModel->size = $file->size;
                    $fileModel->file_type_id = $fileType->id;

                    $fileType->handler->modelCreated($fileModel, $file->tempName);

                    $fileModel->save();

                    $this->filesIds[] = $fileModel->id;
                } else {
                    $this->addError('files', Yii::t('yii2-media', 'Unable to upload file {file}', [
                        'file' => $file->baseName . '.' . $file->extension
                    ]));
                }
            }
            return true;
        }

        return false;
    }

    public static function beforeFileUpload(UploadedFile $file)
    {
        $fileName = $file->baseName . '.' . $file->extension;
        $fileType = MediaFileType::defineFileType($fileName);
        $fileType->handler->beforeUpload($fileName, $file->tempName);
        return $fileType;
    }

    public static function uploadFile(UploadedFile $file, $pathPrefix, Filesystem $fileSystem)
    {
        $resource = fopen($file->tempName, 'rb+');

        $pathPrefix = rtrim($pathPrefix, '/');
        $target = $pathPrefix . '/'
            . $file->baseName . '.' . $file->extension;

        if ($fileSystem->has($target)) {
            $fileSystem->delete($target);
        }
        $uploadResult = $fileSystem->writeStream($target, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }

        if ($uploadResult === false) {
            return false;
        }
        return $target;
    }
}
