<?php

declare(strict_types=1);

namespace app\modules\api;

use Yii;
use yii\di\Container;
use app\modules\api\dispatchers\EventDispatcher;
use app\modules\api\dispatchers\SimpleEventDispatcher;

/**
 * api module definition class
 */
final class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $container = Yii::$container;

        $container->setSingleton(EventDispatcher::class, SimpleEventDispatcher::class);

        $container->setSingleton(SimpleEventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher($container, [
                //
            ]);
        });
    }
}
