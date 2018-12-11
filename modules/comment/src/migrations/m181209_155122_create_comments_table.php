<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m181209_155122_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer(11)->unsigned()->notNull(),
            'parent_id' => $this->integer(11)->unsigned(),
            'content' => $this->text(500)->notNull(),
            'created_by' => $this->integer(11)->unsigned()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
        ]);

        $this->createIndex('comments_parent_id_idx', '{{%comments}}', ['parent_id']);
        $this->createIndex('comments_idx', '{{%comments}}', ['model', 'model_id', 'parent_id']);

        $this->addForeignKey('fk-comments-parent_id', '{{%comments}}', 'parent_id', '{{%comments}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey( 'fk-comments-parent_id', '{{%comments}}');

        $this->dropIndex('comments_idx', '{{%comments}}');
        $this->dropIndex('comments_parent_id_idx', '{{%comments}}');

        $this->dropTable('{{%comments}}');
    }
}
