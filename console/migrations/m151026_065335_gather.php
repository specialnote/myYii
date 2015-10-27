<?php

use yii\db\Schema;
use yii\db\Migration;

class m151026_065335_gather extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "文章采集记录"';
        }
        $this->createTable('{{%gather}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(32)->notNull(),
            'url_org' => $this->string(100)->notNull()->defaultValue(''),
            'category' => $this->string(50)->notNull(),
            'res' => $this->integer(5)->notNull()->defaultValue(0),
            'result' => $this->text()->notNull()->defaultValue(''),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%gather}}');
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
