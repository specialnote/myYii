<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_111650_fund_data extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金净值表"';
        }
        $this->createTable('{{%fund_data}}', [
            'id' => $this->primaryKey(),
            'fund_id'=>$this->string(50),
            'date' => $this->string(10),
            'iopv' => $this->string(10),//基金单位净值
            'accnav' => $this->string(10),//基金累计净值
            'growth' => $this->string(20),//增长值
            'rate' => $this->string(10),//增长率
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%fund_data}}');
        return false;
    }

}
