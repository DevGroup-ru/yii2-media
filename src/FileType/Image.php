<?php

namespace DevGroup\Media\FileType;

use DevGroup\Media\helpers\AttachmentHelper;
use DevGroup\Media\helpers\FsHelper;
use DevGroup\Media\MediaModule;
use DevGroup\Media\models\File;
use DevGroup\Media\models\Folder;
use DevGroup\Media\models\MediaImage;
use DevGroup\Media\models\UploadModel;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\web\UploadedFile;


class Image extends AbstractFileType
{
    public $directoryLevel = 1;
    public $thumbnailWidth = 300;
    public $thumbnailHeight = 300;
    public $thumbnailInset = false;

    /**
     * @param string $filename
     *
     * @return bool Whether this file type relates to filename.
     */
    public function checkFileType($filename)
    {
        return preg_match(
            '/\.(jpg|jpeg|gif|png)$/i',
            $filename
        ) === 1;
    }

    public function modelCreated(File $model, $tempName)
    {
        parent::modelCreated($model, $tempName);
        // create thumbnail for that file
        $mediaImage = MediaImage::findOne($model->id);
        if ($mediaImage === null) {
            $mediaImage = new MediaImage([
                'file_id' => $model->id,
            ]);
        }

        $pathInfo = pathinfo('_' . $model->fs_path, PATHINFO_FILENAME);
        $baseName = mb_substr($pathInfo, 1, mb_strlen($pathInfo, '8bit'), '8bit');

        $mediaImage->extension = strtolower(pathinfo($model->fs_path, PATHINFO_EXTENSION));

        $thumbnailFilename = $baseName . "_{$model->id}.{$mediaImage->extension}";

        $tmpFilename = tempnam(sys_get_temp_dir(), $thumbnailFilename);

        $tmpFilename .= '.' . $mediaImage->extension;
        $image = \yii\imagine\Image::thumbnail(
            $tempName,
            $this->thumbnailWidth,
            $this->thumbnailHeight,
            $this->thumbnailInset ? ManipulatorInterface::THUMBNAIL_INSET : ManipulatorInterface::THUMBNAIL_OUTBOUND
        );
        $image->save($tmpFilename);

        $pathPrefix = 'thumbnails';

        $pathPrefix .= '/' . FsHelper::makeFolders($thumbnailFilename, $this->directoryLevel);

        $fakeUploadedFile = new UploadedFile([
            'tempName' => $tmpFilename,
            'name' => $thumbnailFilename,
        ]);

        UploadModel::uploadFile(
            $fakeUploadedFile,
            $pathPrefix,
            MediaModule::module()->defaultFileSystem()
        );
        $filesize = filesize($tmpFilename);

        unlink($tmpFilename);

        $folder = Folder::ensureFolder($pathPrefix, MediaModule::module()->defaultTree);
        $file = File::ensureFile($folder, $thumbnailFilename);
        $mediaImage->thumb_file_id = $file->id;
        $file->file_type_id = 3;
        $file->size = $filesize;

        $mediaImage->save();

        AttachmentHelper::fillPublicUrl($file);
    }
}
