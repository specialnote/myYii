<?php

use yii\db\Schema;
use yii\db\Migration;

class m150916_074927_article extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'category_id' => $this->string(10)->notNull(),

            'author' => $this->string(30)->notNull(),
            'cover_img' => $this->string(),
            'status' => $this->smallInteger(2)->notNull()->defaultValue(10),

            'view_count' => $this->smallInteger()->notNull()->defaultValue(0),
            'share' => $this->smallInteger()->notNull()->defaultValue(0),

            'publish_at' => $this->string(20)->notNull(),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%article}}');
    }

}
