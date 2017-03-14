<?php

namespace DevGroup\Media\models;

use Yii;
use yii2tech\ar\role\RoleBehavior;

class File extends MediaFs
{
    public function behaviors()
    {
        return [
            'roleBehavior' => [
                'class' => RoleBehavior::className(), // Attach role behavior
                'roleRelation' => 'fileRole', // specify name of the relation to the slave table
                'roleAttributes' => [
                    'is_file' => '1',
                ],
            ],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            $this->getRoleRelationModel()->attributeLabels()
        );
    }

    public function attributeHints()
    {
        return array_merge(
            parent::attributeHints(),
            $this->getRoleRelationModel()->attributeHints()
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            $this->getRoleRelationModel()->rules()
        );
    }

    public function getFileRole()
    {
        return $this->hasOne(MediaFile::class, ['file_id' => 'id']);
    }
}
