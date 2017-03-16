<?php

namespace DevGroup\Media\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the ActiveQuery class for [[MediaFs]].
 *
 * @see MediaFs
 * @mixin NestedSetsQueryBehavior
 */
class MediaFsQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    public function inFolder(Folder $folder, $depth = null)
    {
        $condition = [
            'and',
            ['>', 'lft', $folder->lft],
            ['<', 'rgt', $folder->rgt],
            ['=', 'tree', $folder->tree],
        ];
        if ($depth !== null) {
            $condition[] = ['<=', 'depth', $folder->depth + $depth];
        }

        return $this->andWhere($condition)->addOrderBy(['lft' => SORT_ASC]);
    }

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
