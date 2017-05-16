<?php

namespace DevGroup\Media\controllers;

use DevGroup\Media\helpers\AttachmentHelper;
use DevGroup\Media\models\TestModel;
use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $model = new TestModel();
        $model->id = 123;
        //$model->files = '20,35';

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }
        return $this->render('index', ['model' => $model]);
    }
}
