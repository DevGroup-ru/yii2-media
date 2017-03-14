<?php

namespace DevGroup\Media\UrlProvider;

use creocoder\flysystem\Filesystem;
use Yii;
use yii\base\Component;

abstract class AbstractUrlProvider extends Component
{
    /** @var  Filesystem */
    protected $filesystem;

    public function setFilesystem(Filesystem &$filesystem)
    {
        $this->filesystem = &$filesystem;
    }

    abstract public function initFilesystem();

    /**
     * @param string $filename
     *
     * @return string
     */
    abstract public function getFileUrl(string $filename);
}
