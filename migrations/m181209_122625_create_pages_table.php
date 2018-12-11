<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pages`.
 */
class m181209_122625_create_pages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pages', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'body' => $this->text(500)->notNull(),
        ]);

        $this->insert('pages', [
            'title' => 'Page title 1',
            'body' => 'Page body with hash: $2y$13$Q1YUucYC8fTZ85n9uYV.7u6cUIIlftQft9LYCkNX1iTPyqeM8GBDW',
        ]);

        $this->insert('pages', [
            'title' => 'Page title 2',
            'body' => 'Page body without hash: null',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pages');
    }
}
