<?php

namespace DevGroup\Media\models;

use Yii;
use yii\db\ActiveQuery;

class Folder extends MediaFs
{
    /**
     * @return MediaFsQuery
     */
    public static function find()
    {
        return parent::find()->where(['is_file' => 0]);
    }

    /**
     * @return Folder[]
     */
    public static function trees()
    {
        $cacheKey = 'Media:FileSystem:AllTrees';
        $trees = Yii::$app->cache->get($cacheKey);
        if ($trees === false) {
            $trees = Folder::find()
                ->roots()
                ->all();
            Yii::$app->cache->set($cacheKey, $trees, 86400);
        }
        return $trees;
    }

    /**
     * @param string $path
     * @param int $tree_id
     *
     * @return Folder
     */
    public static function ensureFolder($path, $tree_id)
    {
        $splitted = explode('/', $path);
        $model = null;

        $trees = static::trees();
        foreach ($trees as $tree) {
            if ($tree_id === (int) $tree->id) {
                $model = $tree;
            }
        }

        foreach ($splitted as $part) {
            /** @var ActiveQuery $query */
            $query = $model
                ->children(1)
                ->where(['name' => $part]);
            $next = $query->one();
            if ($next === null) {
                $next = new Folder([
                    'name' => $part,
                    'fs_path' => $part,
                ]);
                $next->appendTo($model);
            }
            $model = $next;

        }

        return $model;
    }
}
