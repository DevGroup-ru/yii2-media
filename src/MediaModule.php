<?php

namespace DevGroup\Media;

use Yii;
use yii\base\Module;

class MediaModule extends Module
{
    public static $moduleId;

    public $permission = 'media-module';

    public function init()
    {
        parent::init();
        static::$moduleId = $this->id;
    }

    /**
     * @return null|\yii\base\Module|MediaModule
     */
    public static function module()
    {
        return Yii::$app->getModule(static::$moduleId);
    }
}
