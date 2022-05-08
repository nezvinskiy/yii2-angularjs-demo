<?php

declare(strict_types=1);

namespace app\modules\api\services;

use Yii;
use app\modules\api\repositories\CandidateRepository;
use app\modules\api\repositories\FrameworkRepository;
use app\modules\api\entities\Candidate;
use app\modules\api\dto\CandidateDto;
use DateTimeImmutable;
use Exception;

final class CandidateService
{
    private CandidateRepository $repository;
    private FrameworkRepository $frameworkRepository;

    public function __construct(CandidateRepository $repository, FrameworkRepository $frameworkRepository)
    {
        $this->repository = $repository;
        $this->frameworkRepository = $frameworkRepository;
    }

    /**
     * @throws Exception
     */
    public function create(CandidateDto $candidateDto): void
    {
        $candidate = Candidate::create(
            $candidateDto->name,
            new DateTimeImmutable($candidateDto->birthday),
            $candidateDto->experience,
            $candidateDto->resume,
            $candidateDto->comment,
            new DateTimeImmutable($candidateDto->createdAt),
        );

        $this->repository->add($candidate);

        foreach ($candidateDto->frameworks as $frameworkId) {
            $framework = $this->frameworkRepository->get((int) $frameworkId);
            $this->repository->attachFramework($candidate, $framework);
        }
    }

    /**
     * @throws Exception
     */
    public function edit(int $id, CandidateDto $candidateDto): void
    {
        $candidate = $this->repository->get($id);

        $this->removeFile($candidate->resume);

        $candidate->edit(
            $candidateDto->name,
            new DateTimeImmutable($candidateDto->birthday),
            $candidateDto->experience,
            $candidateDto->resume,
            $candidateDto->comment,
        );

        $this->repository->update($candidate);

        $frameworks = [];
        foreach ($candidateDto->frameworks as $frameworkId) {
            $frameworks[] = $this->frameworkRepository->get((int) $frameworkId);
        }

        $this->repository->syncFrameworks($candidate, ...$frameworks);
    }

    public function remove(int $id): void
    {
        $candidate = $this->repository->get($id);
        $this->removeFile($candidate->resume);
        $this->repository->remove($candidate);
    }

    private function removeFile(string $filename): void
    {
        unlink(Yii::getAlias('@webroot') . $filename);
    }
}
