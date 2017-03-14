<?php

namespace DevGroup\Media\models;

class Folder extends MediaFs
{
    public static function find()
    {
        return parent::find()->where(['is_file' => 0]);
    }
}
