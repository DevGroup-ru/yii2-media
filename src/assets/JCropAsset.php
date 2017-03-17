<?php

namespace DevGroup\Media\assets;

use Yii;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class JCropAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/jcrop';
    /**
     * @var array
     */
    public $css = [
        'css/Jcrop.min.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'js/Jcrop.min.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];

    public function init()
    {
//        $this->sourcePath = __DIR__ . '/jcrop/';
        parent::init();
    }
}
