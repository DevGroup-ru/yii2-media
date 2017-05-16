<?php

namespace DevGroup\Media\helpers;

use Yii;

class FsHelper
{
    public static function makeFolders($key, $directoryLevel = 3)
    {
        $key = md5($key);
        $pathPrefix = '';
        for ($i = 0; $i < $directoryLevel; ++$i) {
            if (($prefix = substr($key, $i + $i, 6)) !== false) {
                $pathPrefix .= "$prefix/";
            }
        }
        return $pathPrefix;
    }
}
