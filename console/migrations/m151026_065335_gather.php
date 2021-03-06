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
            'name'=>$this->string(50),
            'category' => $this->string(50),
            'url_org' => $this->string(100)->notNull()->defaultValue(''),
            'url' => $this->string(32)->notNull(),
            'date' => $this->string(10),
            'res' => $this->integer(5)->notNull(),
            'result' => $this->text()->notNull(),
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
