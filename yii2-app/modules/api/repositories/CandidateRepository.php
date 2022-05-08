<?php

declare(strict_types=1);

namespace app\modules\api\repositories;

use app\modules\api\entities\Candidate;
use app\modules\api\entities\Framework;
use app\modules\api\dispatchers\EventDispatcher;
use app\modules\api\exceptions\NotFoundException;
use app\modules\api\exceptions\AddedException;
use app\modules\api\exceptions\UpdatedException;
use app\modules\api\exceptions\DeletedException;

final class CandidateRepository
{
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get(int $id): Candidate
    {
        return $this->getBy(['id' => $id]);
    }

    public function add(Candidate $candidate): void
    {
        if (! $candidate->getIsNewRecord()) {
            throw new AddedException('Adding existing model.');
        }
        if (! $candidate->insert(false)) {
            throw new AddedException('Saving error.');
        }
        $this->dispatcher->dispatchAll($candidate->releaseEvents());
    }

    public function update(Candidate $candidate): void
    {
        if ($candidate->getIsNewRecord()) {
            throw new AddedException('Saving new model.');
        }
        if ($candidate->update(false) === false) {
            throw new UpdatedException('Updating error.');
        }
        $this->dispatcher->dispatchAll($candidate->releaseEvents());
    }

    public function remove(Candidate $candidate): void
    {
        if (! $candidate->delete()) {
            throw new DeletedException('Removing error.');
        }
        $this->dispatcher->dispatchAll($candidate->releaseEvents());
    }

    public function attachFramework(Candidate $candidate, Framework $framework): void
    {
        $candidate->link('frameworks', $framework);
        $this->dispatcher->dispatchAll($candidate->releaseEvents());
    }

    public function syncFrameworks(Candidate $candidate, Framework ...$frameworks): void
    {
        $candidate->unlinkAll('frameworks', true);
        foreach ($frameworks as $framework) {
            $candidate->link('frameworks', $framework);
        }
        $this->dispatcher->dispatchAll($candidate->releaseEvents());
    }

    private function getBy(array $condition): Candidate
    {
        if (! $candidate = Candidate::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Candidate not found.');
        }
        return $candidate;
    }
}
