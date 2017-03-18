<?php

namespace DevGroup\Media;

use creocoder\flysystem\Filesystem;
use DevGroup\Media\models\MediaProviders;
use DevGroup\Media\UrlProvider\AbstractUrlProvider;
use Yii;
use yii\base\Module;

class MediaModule extends Module
{
    public static $moduleId = 'media';

    public $permission = 'media-module';

    /** @var Filesystem[] */
    public $fileSystems = [];

    /** @var AbstractUrlProvider[] */
    public $urlProviders = [];

    public $tree2providerId = [];
    /**
     * @var int
     */
    public $defaultTree;

    public function init()
    {
        parent::init();
        static::$moduleId = $this->id;

        /** @var MediaProviders[] $providers */
        $providers = MediaProviders::find()->all();
        foreach ($providers as $provider) {
            if ($this->defaultTree === null) {
                $this->defaultTree = (int) $provider->tree_id;
            }

            $tree2providerId[(int) $provider->tree_id] = $provider->id;

            $conf = $provider->options;
            $conf['class'] = $provider->class_name;

            $this->fileSystems[$provider->id] = Yii::createObject($conf);

            $conf = $provider->url_provider_options;
            $conf['class'] = $provider->url_provider_class_name;
            $this->urlProviders[$provider->id] = Yii::createObject($conf);
            $this->urlProviders[$provider->id]->setFilesystem(
                $this->fileSystems[$provider->id]
            );
            $this->urlProviders[$provider->id]->initFilesystem();
        }
    }

    /**
     * @return \creocoder\flysystem\Filesystem
     */
    public function defaultFileSystem()
    {
        return reset($this->fileSystems);
    }

    /**
     * @return \DevGroup\Media\UrlProvider\AbstractUrlProvider
     */
    public function defaultUrlProvider()
    {
        return reset($this->urlProviders);
    }


    /**
     * @param int $tree
     *
     * @return \DevGroup\Media\UrlProvider\AbstractUrlProvider
     */
    public function urlProviderByTree($tree)
    {
        $tree = (int) $tree;
        if (isset($this->tree2providerId[$tree])) {
            return $this->urlProviders[$this->tree2providerId[$tree]];
        }
        Yii::warning("Tree with id $tree not found in MediaProviders", 'yii2-media');
        return $this->defaultUrlProvider();
    }

    /**
     * @return null|\yii\base\Module|MediaModule
     */
    public static function module()
    {
        return Yii::$app->getModule(static::$moduleId);
    }
}
