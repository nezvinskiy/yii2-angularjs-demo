<?php

declare(strict_types=1);

namespace app\modules\api\entities;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

final class CandidateFramework extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%candidate_framework}}';
    }

    public function getFramework(): ActiveQuery
    {
        return $this->hasOne(Framework::class, ['id' => 'framework_id']);
    }
}
