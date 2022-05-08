<?php

declare(strict_types=1);

namespace shop\entities;

namespace app\modules\api\entities;

interface AggregateRoot
{
    /**
     * @return object[]
     */
    public function releaseEvents(): array;
}
