<?php

namespace DevGroup\Media\assets;

use Yii;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class SortableAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $js = [
        'sortable.min.js',
        'jquery.binding.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/sortable/';
        parent::init();
    }
}
