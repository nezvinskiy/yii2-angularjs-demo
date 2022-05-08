<?php

declare(strict_types=1);

namespace app\modules\api\readModels;

use app\modules\api\entities\Framework;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

final class FrameworkReadRepository
{
    public function getAll(): DataProviderInterface
    {
        $query = Framework::find()->alias('p');

        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);
    }
}
