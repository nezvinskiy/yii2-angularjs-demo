<?php

declare(strict_types=1);

namespace app\modules\api\readModels;

use app\modules\api\entities\Candidate;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

final class CandidateReadRepository
{
    public function find(int $id): ?Candidate
    {
        return Candidate::find()->andWhere(['id' => $id])->one();
    }

    public function getAll(): DataProviderInterface
    {
        $query = Candidate::find()->alias('p')->with('candidateFrameworks');

        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_ASC],
                'attributes' => [
                    'created_at' => [
                        'asc' => ['p.created_at' => SORT_ASC],
                        'desc' => ['p.created_at' => SORT_DESC],
                    ],
                ],
            ],
        ]);
    }
}
