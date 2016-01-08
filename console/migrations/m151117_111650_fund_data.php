<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_111650_fund_data extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金数据表"';
        }
        $this->createTable('{{%fund_data}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(50),
            'fund_type'=>$this->string(50),//类型
            'date' => $this->string(10),
            'day_sort' => $this->string(10),//当天排名
            'week' => $this->string(10),//一年第几周
            'month' => $this->string(10),//一年第几月
            'quarter' => $this->string(10),//一年第几季度
            'year' => $this->string(10),//第几年
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
