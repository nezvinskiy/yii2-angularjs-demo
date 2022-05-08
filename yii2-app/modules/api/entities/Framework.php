<?php

declare(strict_types=1);

namespace app\modules\api\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 */
final class Framework extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    public static function tableName(): string
    {
        return '{{%frameworks}}';
    }
}
