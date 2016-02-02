<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_061227_fund_filter extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金筛选"';
        }
        $this->createTable('{{%fund_filter}}', [
            'id' => $this->primaryKey()->notNull(),
            'fund_num'=>$this->string(8),
            'date'=>$this->string(12)->notNull(),//日期
            'type'=>$this->integer(3)->notNull(),//筛选类型
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_filter}}');
        return false;
    }
}
