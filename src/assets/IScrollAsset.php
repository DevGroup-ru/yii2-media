<?php

namespace DevGroup\Media\assets;

use Yii;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class IScrollAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/iscroll/build';

    /**
     * @var array
     */
    public $js = [
        'iscroll-infinite.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];
}
