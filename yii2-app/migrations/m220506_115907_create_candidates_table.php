<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%candidates}}`.
 */
class m220506_115907_create_candidates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%candidates}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'birthday' => $this->date(),
            'experience' => $this->integer(),
            'resume' => $this->string(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%candidates}}');
    }
}
