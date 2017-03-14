<?php

namespace DevGroup\Media\UrlProvider;

use creocoder\flysystem\LocalFilesystem;
use Yii;

class LocalUrlProvider extends AbstractUrlProvider
{
    private $siteUrl;

    public function initFilesystem()
    {
        /* @var LocalFilesystem $fs */
        $fs = $this->filesystem;
        $fsPath = Yii::getAlias($fs->path);
        $this->siteUrl = Yii::$app->request->hostInfo . '/' . Yii::getAlias('@web');
        $this->siteUrl = str_replace(Yii::getAlias('@webroot'), $this->siteUrl, $fsPath);
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
