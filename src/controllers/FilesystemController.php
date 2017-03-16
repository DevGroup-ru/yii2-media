<?php

namespace DevGroup\Media\controllers;

use DevGroup\Media\models\File;
use DevGroup\Media\models\Folder;
use Yii;
use yii\caching\TagDependency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FilesystemController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionTrees()
    {
        $cacheKey = 'Media:FileSystem:Trees';
        $trees = Yii::$app->cache->get($cacheKey);
        if ($trees === false) {
            $trees = Folder::find()
                ->where([
                    '[depth]' => 0,
                ])
                ->roots()
                ->select([
                    'id',
                    'name',
                    'fs_path',
                ])
                ->asArray()
                ->all();
            Yii::$app->cache->set($cacheKey, $trees, 86400);
        }
        return $trees;
    }

    public function actionListFolders($folder_id)
    {
        $folder = Folder::loadModel($folder_id, false, true, 86400, true);

        $nodes = $folder
            ->children(1)
            ->select([
                'id',
                'name',
                'fs_path',
            ])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return $nodes;
    }

    public function actionListFiles($folder_id, $start = 0, $count = 100)
    {
        $folder = Folder::loadModel($folder_id, false, true, 86400, true);
        $files = File::find()
            ->inFolder($folder, 1)
            ->select(['id', 'name', 'fs_path', 'created_time', 'updated_time'])
            ->offset($start)
            ->limit($count)
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($files as $file) {
            $result[] = $file->toArray([], $file->extraFields(), true);
        }
        return $result;
    }
}
