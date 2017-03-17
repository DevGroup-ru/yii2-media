<?php

namespace DevGroup\Media\helpers;

use DevGroup\Media\models\File;
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

        $result = MediaFileRelation::find()
            ->select(['file_id', 'model_id', 'sort_order'])
            ->where([
                'model_class_name_hash' => md5($firstModel::className()),
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
                $model->$relationName = [];
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
}
