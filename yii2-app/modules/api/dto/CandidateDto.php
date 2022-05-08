<?php

declare(strict_types=1);

namespace app\modules\api\dto;

final class CandidateDto
{
    public string $name;
    public string $birthday;
    public int $experience;
    public string $resume;
    public string $comment;
    public array $frameworks;
    public string $createdAt;

    public static function fromArray(array $data): self
    {
        $instance = new self();
        $instance->name = $data['name'];
        $instance->birthday = $data['birthday'];
        $instance->experience = $data['experience'];
        $instance->resume = $data['resume'];
        $instance->comment = $data['comment'];
        $instance->frameworks = $data['frameworks'];
        $instance->createdAt = $data['createdAt'];
        return $instance;
    }
}
