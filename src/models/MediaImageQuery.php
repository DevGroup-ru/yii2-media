<?php

namespace DevGroup\Media\models;

/**
 * This is the ActiveQuery class for [[MediaImage]].
 *
 * @see MediaImage
 */
class MediaImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
