<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m181209_132415_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
        ]);

        $this->insert('users', [
            'username' => 'admin',
            'password_hash' => '$2y$13$KaP2Fvdj1MQMk0KzKhkN..zz58HLKNiZpBtm6HBq5U/JJT5k26JZu',
            'email' => 'admin@dev.com',
            'auth_key' => 'W8PpL8xlSiLU8VtvbWPNGb0HUNzGS_3X'
        ]);

        $this->insert('users', [
            'username' => 'user',
            'password_hash' => '$2y$13$Q1YUucYC8fTZ85n9uYV.7u6cUIIlftQft9LYCkNX1iTPyqeM8GBDW',
            'email' => 'user@dev.com',
            'auth_key' => '-2pNFeE7sgYlEV3CoVkFlg2goig9dd5n'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
