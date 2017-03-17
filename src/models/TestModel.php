<?php

namespace DevGroup\Media\models;

use DevGroup\Media\behaviors\MediaFileBehavior;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class TestModel extends ActiveRecord
{
//    public $id = '123';

    public function behaviors()
    {
        return [
            'files' => [
                'class' => MediaFileBehavior::class,
                'relationName' => 'files',
            ],
        ];
    }
}
