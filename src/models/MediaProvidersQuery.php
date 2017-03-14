<?php

namespace DevGroup\Media\models;

/**
 * This is the ActiveQuery class for [[MediaProviders]].
 *
 * @see MediaProviders
 */
class MediaProvidersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaProviders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaProviders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
