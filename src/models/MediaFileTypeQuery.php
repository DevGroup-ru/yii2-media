<?php

namespace DevGroup\Media\models;

/**
 * This is the ActiveQuery class for [[MediaFileType]].
 *
 * @see MediaFileType
 */
class MediaFileTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaFileType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaFileType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
