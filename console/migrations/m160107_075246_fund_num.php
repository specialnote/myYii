<?php

use yii\db\Schema;
use yii\db\Migration;

class m160107_075246_fund_num extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金编码"';
        }
        $this->createTable('{{%fund_history}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(8),
            'date'=>$this->string(20),//采集日期
            'rate'=>$this->string(10),//增长率
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_history}}');
        return false;
    }
}
