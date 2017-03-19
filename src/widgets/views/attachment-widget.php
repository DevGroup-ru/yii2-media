<?php
/** @var \yii\web\View $this */
/** @var \yii\base\Model $model */
/** @var string $attribute */
/** @var array $options */

use DevGroup\Media\helpers\AttachmentHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$inputId = 'media-attachment__' . Html::getInputId($model, $attribute);
$encodedId = Json::encode($inputId);
$js = <<<js
MediaAttachment.bindToInput($encodedId);
js;

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

$this->registerJs($js);

?>
<div class="media-attachment">
    <?=
    Html::activeHiddenInput(
        $model,
        $attribute,
        [
            'id' => $inputId,

            // model binding
            'data-model' => AttachmentHelper::modelClassName($model),
            'data-model-id' => $model->id,
            'data-relation-name' => $attribute,

            // CSRF
            'data-csrf-param' => $csrfParam,
            'data-csrf-token' => $csrfToken,

            // standard urls
            'data-fs-upload-target' => Url::to(['/media/filesystem/upload']),
            'data-fs-trees' => Url::to(['/media/filesystem/trees']),
            'data-fs-list-folders' => Url::to(['/media/filesystem/list-folders']),
            'data-fs-list-files' => Url::to(['/media/filesystem/list-files']),
            'data-fs-get-files' => Url::to(['/media/filesystem/get-files']),
        ]
    );?>


    <div class="input-group media-attachment__input-area">
        <div class="media-attachment__controls">
            <div class="btn-group pull-right">
                <a href="#" class="btn btn-primary media-attachment__upload">
                    <?= Yii::t('yii2-media', 'Upload') ?>
                </a>
                <a href="#" class="btn btn-success media-attachment__browse">
                    <?= Yii::t('yii2-media', 'Browse gallery') ?>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="media-attachment__selected-files">
            <?= Yii::t('yii2-media', 'Drag&drop files here to upload') ?>
        </div>
        <div class="media-attachment__gallery-container">

        </div>
    </div>

</div>
