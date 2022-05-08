<?php

declare(strict_types=1);

namespace app\modules\api\dispatchers;

interface EventDispatcher
{
    public function dispatchAll(array $events): void;
    public function dispatch(object $event): void;
}
