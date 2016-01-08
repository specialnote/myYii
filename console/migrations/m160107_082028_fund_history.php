<?php

use yii\db\Schema;
use yii\db\Migration;

class m160107_082028_fund_history extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金增长率"';
        }
        $this->createTable('{{%fund_history}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(50),
            'date'=>$this->string(50),//日期
            'rate'=>$this->string(50),//增长率
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
