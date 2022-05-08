<?php

declare(strict_types=1);

namespace app\modules\api\repositories;

use app\modules\api\entities\Framework;
use app\modules\api\exceptions\NotFoundException;

final class FrameworkRepository
{
    public function get(int $id): Framework
    {
        return $this->getBy(['id' => $id]);
    }

    private function getBy(array $condition): Framework
    {
        if (! $framework = Framework::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Framework not found.');
        }
        return $framework;
    }
}
