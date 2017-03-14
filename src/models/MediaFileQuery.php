<?php

namespace DevGroup\Media\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the ActiveQuery class for [[MediaFile]].
 *
 * @see MediaFile
 */
class MediaFileQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     * @return MediaFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
