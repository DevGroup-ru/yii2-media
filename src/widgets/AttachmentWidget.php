<?php

namespace DevGroup\Media\widgets;

use DevGroup\Media\assets\MediaBundle;
use Yii;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class AttachmentWidget extends InputWidget
{
    public function run()
    {
        MediaBundle::register($this->view);

        echo $this->render(
            'attachment-widget',
            [
                'model' => $this->model,
                'attribute' => $this->attribute,
                'options' => $this->options,
            ]
        );
    }
}
