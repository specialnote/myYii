<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_022353_fund_chose extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "自选基金"';
        }
        $this->createTable('{{%fund_choose}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(8),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_choose}}');
        return false;
    }
}
