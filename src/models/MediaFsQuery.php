<?php

namespace DevGroup\Media\models;

/**
 * This is the ActiveQuery class for [[MediaFs]].
 *
 * @see MediaFs
 */
class MediaFsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaFs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaFs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
