<?php

namespace DevGroup\Media\models;

/**
 * This is the ActiveQuery class for [[MediaFileRelation]].
 *
 * @see MediaFileRelation
 */
class MediaFileRelationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaFileRelation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaFileRelation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
