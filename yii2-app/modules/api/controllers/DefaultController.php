<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\rest\OptionsAction;

/**
 * Default controller for the `api` module
 */
final class DefaultController extends Controller
{
    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'options' => ['OPTIONS'],
        ];
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }

    public function actionIndex(): array
    {
        return [
            'version' => '1.0.0',
        ];
    }
}
