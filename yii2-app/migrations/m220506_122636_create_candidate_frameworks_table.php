<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%candidate_frameworks}}`.
 */
class m220506_122636_create_candidate_frameworks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%candidate_framework}}', [
            'candidate_id' => $this->integer(),
            'framework_id' => $this->integer(),
            'PRIMARY KEY(candidate_id, framework_id)',
        ]);

        // creates index for column `candidate_id`
        $this->createIndex(
            'idx-candidate_framework-candidate_id',
            '{{%candidate_framework}}',
            'candidate_id'
        );

        // add foreign key for table `candidate`
        $this->addForeignKey(
            'fk-candidate_framework-candidate_id',
            '{{%candidate_framework}}',
            'candidate_id',
            '{{%candidates}}',
            'id',
            'CASCADE'
        );

        // creates index for column `framework_id`
        $this->createIndex(
            'idx-candidate_framework-framework_id',
            '{{%candidate_framework}}',
            'framework_id'
        );

        // add foreign key for table `framework`
        $this->addForeignKey(
            'fk-candidate_framework-framework_id',
            '{{%candidate_framework}}',
            'framework_id',
            '{{%frameworks}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        // drops foreign key for table `candidate`
        $this->dropForeignKey(
            'fk-candidate_framework-candidate_id',
            '{{%candidate_framework}}'
        );

        // drops index for column `candidate_id`
        $this->dropIndex(
            'idx-candidate_framework-candidate_id',
            '{{%candidate_framework}}'
        );

        // drops foreign key for table `framework`
        $this->dropForeignKey(
            'fk-candidate_framework-framework_id',
            '{{%candidate_framework}}'
        );

        // drops index for column `framework_id`
        $this->dropIndex(
            'idx-candidate_framework-framework_id',
            '{{%candidate_framework}}'
        );

        $this->dropTable('{{%candidate_framework}}');
    }
}
