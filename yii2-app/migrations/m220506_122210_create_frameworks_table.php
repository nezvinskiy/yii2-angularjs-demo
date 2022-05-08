<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%frameworks}}`.
 */
class m220506_122210_create_frameworks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%frameworks}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%frameworks}}');
    }
}
