<?php

use yii\db\Schema;
use yii\db\Migration;

class m150916_082450_article_tag extends Migration
{
    /*public function up()
    {

    }

    public function down()
    {
        echo "m150916_082450_article_tag cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%article_tag}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(10)->notNull(),
            'tag_id' => $this->integer(10)->notNull(),

            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%article_tag}}');
    }

}
