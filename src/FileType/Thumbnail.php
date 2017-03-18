<?php

namespace DevGroup\Media\FileType;

use Yii;

class Thumbnail extends AbstractFileType
{

    /**
     * @param string $filename
     *
     * @return bool Whether this file type relates to filename.
     */
    public function checkFileType($filename)
    {
        return false;
    }
}
