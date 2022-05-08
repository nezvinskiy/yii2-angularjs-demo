<?php

declare(strict_types=1);

namespace app\modules\api\entities;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use DateTimeImmutable;

/**
 * @property integer $id
 * @property string $name
 * @property string $birthday
 * @property integer $experience
 * @property string $resume
 * @property string $comment
 * @property string $created_at
 *
 * @property CandidateFramework[] $candidateFrameworks
 */
final class Candidate extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    public static function create(
        string $name,
        DateTimeImmutable $birthday,
        int $experience,
        string $resume,
        string $comment,
        DateTimeImmutable $createdAt
    ): self {
        $employee = new self();
        $employee->name = $name;
        $employee->birthday = $birthday->format('Y-m-d');
        $employee->experience = $experience;
        $employee->resume = $resume;
        $employee->comment = $comment;
        $employee->created_at = $createdAt->format('Y-m-d H:i:s');
        return $employee;
    }

    public function edit(
        string $name,
        DateTimeImmutable $birthday,
        int $experience,
        string $resume,
        string $comment
    ): void {
        $this->name = $name;
        $this->birthday = $birthday->format('Y-m-d');
        $this->experience = $experience;
        $this->resume = $resume;
        $this->comment = $comment;
    }

    public static function tableName(): string
    {
        return '{{%candidates}}';
    }

    public function getCandidateFrameworks(): ActiveQuery
    {
        return $this->hasMany(CandidateFramework::class, ['candidate_id' => 'id']);
    }

    public function getFrameworks(): ActiveQuery
    {
        return $this->hasMany(Framework::class, ['id' => 'framework_id'])
            ->viaTable('{{%candidate_framework}}', ['candidate_id' => 'id']);
    }
}
