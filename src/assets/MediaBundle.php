<?php

namespace DevGroup\Media\assets;

use yii\jui\JuiAsset;
use yii\web\AssetBundle;

class MediaBundle extends AssetBundle
{
    public $depends = [
        JCropAsset::class,
        IScrollAsset::class,
        JuiAsset::class,
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/dist/';

        $this->js = [
            YII_ENV_DEV ? 'app.bundle.js' : 'app.bundle.min.js'
        ];

        $this->css = [
            YII_ENV_DEV ? 'app.bundle.css' : 'app.bundle.min.css'
        ];

        parent::init();
    }
}
