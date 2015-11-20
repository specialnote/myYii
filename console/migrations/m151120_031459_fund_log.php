<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_031459_fund_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金净值表"';
        }
        $this->createTable('{{%fund_log}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(50),
            'date' => $this->string(10),
            'item' => $this->string(10),//记录项目
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_log}}');
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
