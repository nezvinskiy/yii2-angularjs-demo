<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use yii\rest\Controller;
use app\modules\api\readModels\FrameworkReadRepository;
use app\modules\api\providers\MapDataProvider;
use app\modules\api\entities\Framework;

final class FrameworkController extends Controller
{
    private FrameworkReadRepository $frameworks;

    public function __construct($id, $module, FrameworkReadRepository $frameworks, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->frameworks = $frameworks;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex(): MapDataProvider
    {
        $dataProvider = $this->frameworks->getAll();

        return new MapDataProvider($dataProvider, [$this, 'serializeListItem']);
    }

    public function serializeListItem(Framework $framework): array
    {
        return [
            'id' => $framework->id,
            'name' => $framework->name,
        ];
    }
}
