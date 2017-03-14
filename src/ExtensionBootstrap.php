<?php

namespace DevGroup\Media;

use DevGroup\AdminModals\components\AdminModals;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;


class ExtensionBootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            if (isset($app->controllerMap['migrate'])) {
                $app->controllerMap['migrate']['migrationLookup'][] = '@vendor/devgroup/yii2-media/src/migrations';
            }

        }
    }
}
