<?php

use yii\db\Migration;

/**
 * Class m220506_124250_add_data_to_frameworks_table
 */
class m220506_124250_add_data_to_frameworks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->batchInsert('{{%frameworks}}', ['name',], [
            ['Yii1',],
            ['Yii2',],
            ['Laravel',],
            ['Symphony',],
            ['Zend',],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        echo "m220506_124250_add_data_to_frameworks_table cannot be reverted.\n";

        return false;
    }
}
