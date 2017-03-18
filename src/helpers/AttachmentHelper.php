<?php

namespace DevGroup\Media\helpers;

use DevGroup\Media\MediaModule;
use DevGroup\Media\models\File;
use DevGroup\Media\models\Folder;
use DevGroup\Media\models\MediaFileRelation;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AttachmentHelper
{
    /**
     * @param string  $relationName
     * @param Model[] $models
     */
    public static function retrieveRelated($relationName, array $models)
    {
        $ids = ArrayHelper::getColumn($models, 'id');
        $firstModel = reset($models);
        $reflector = new \ReflectionClass($firstModel);
        $modelName = $reflector->getShortName();
        $result = MediaFileRelation::find()
            ->select(['file_id', 'model_id', 'sort_order'])
            ->where([
                'model' => $modelName,
                'relation_name' => $relationName,
                'model_id' => $ids,
            ])
            ->orderBy(['sort_order' => SORT_ASC])
            ->asArray()
            ->all();
        $result = ArrayHelper::map($result, 'sort_order', 'file_id', 'model_id');
        foreach ($models as $model) {
            if (isset($result[$model->id])) {
                $model->$relationName = implode(',', $result[$model->id]);
            } else {
                $model->$relationName = '';
            }
        }
    }

    public static function fileDefinitions(array $ids)
    {
        //! @todo add cache here
        $files = File::find()
            ->where(['in', 'id', $ids])
            ->select(['id', 'name', 'fs_path', 'created_time', 'updated_time'])
            ->with(['imageData.thumb'])
            ->indexBy('id')
            ->all();
        $result = [];
        foreach ($ids as $id) {
            if (isset($files[$id])) {
                $file = $files[$id];

                $result[$id] = $file->toArray([], $file->extraFields(), true);
            }
        }
        return $result;
    }

    public static function fillPublicUrl(File $model)
    {
        $parents = Folder::find()
            ->select(['fs_path'])
            ->where([
                'and',
                [
                    '<', 'lft', $model->lft,
                ],
                [
                    '>', 'rgt', $model->rgt,
                ],
                [
                    'tree' => $model->tree,
                ],
                [
                    '!=', 'depth', 0
                ]
            ])
            ->orderBy(['lft' => SORT_ASC])
            ->asArray()
            ->column();

        $fullUrl = implode('/', $parents) . '/' . $model->fs_path;
        $urlProvider = MediaModule::module()->urlProviderByTree($model->tree);
        $model->public_url = $urlProvider->getFileUrl($fullUrl);
        $model->save();
    }


    public static function modelClassName($model)
    {
        $reflection = new \ReflectionClass($model);
        return $reflection->getShortName();
    }
}
