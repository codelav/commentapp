<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_ratings`.
 */
class m181209_155625_create_comment_ratings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment_ratings}}', [
            'id' => $this->primaryKey(),
            'comments_id' => $this->integer(11)->unsigned()->notNull(),
            'rate' => $this->integer(1)->notNull(),
            'created_by' => $this->integer(11)->unsigned()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
        ]);

        $this->createIndex('comment_ratings_comments_id_idx', '{{%comment_ratings}}', ['comments_id']);
        $this->createIndex('comment_ratings_created_by_idx', '{{%comment_ratings}}', ['created_by']);

        $this->addForeignKey('fk-comment_ratings-comments_id', '{{%comment_ratings}}', 'comments_id', '{{%comments}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-comment_ratings-comments_id', '{{%comment_ratings}}');

        $this->dropIndex('comment_ratings_created_by_idx', '{{%comment_ratings}}');
        $this->dropIndex('comment_ratings_comments_id_idx', '{{%comment_ratings}}');

        $this->dropTable('{{%comment_ratings}}');
    }
}
