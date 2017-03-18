<?php

namespace DevGroup\Media\UrlProvider;

use creocoder\flysystem\LocalFilesystem;
use Yii;

class LocalUrlProvider extends AbstractUrlProvider
{
    public $siteUrl;

    public function initFilesystem()
    {
        /* @var LocalFilesystem $fs */
        $fs = $this->filesystem;
        $fsPath = Yii::getAlias($fs->path);
        if (empty($this->siteUrl)) {
            $this->siteUrl = Yii::$app->request->hostInfo . Yii::getAlias('@web');
        }

        $this->siteUrl = str_replace(
            Yii::getAlias('@webroot'),
            rtrim($this->siteUrl, '/'),
            $fsPath
        );
        $this->siteUrl = rtrim($this->siteUrl, '/');
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFileUrl(string $filename)
    {
        return $this->siteUrl . "/$filename";
    }
}
