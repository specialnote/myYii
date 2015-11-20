<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_031451_fund_analyze extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "基金净值表"';
        }
        $this->createTable('{{%fund_analyze}}', [
            'id' => $this->primaryKey(),
            'fund_num'=>$this->string(50),
            'week' => $this->string(10),//第几周
            'week_average_iopv' => $this->string(10),//周平均净值
            'week_average_growth' => $this->string(10),//周平均增长值
            'week_average_rate' => $this->string(10),//周平均增长值率
            'month' => $this->string(10),//第几月
            'month_average' => $this->string(10),//月平均净值
            'month_average_iopv' => $this->string(10),//月平均净值
            'month_average_growth' => $this->string(10),//月平均增长值
            'month_average_rate' => $this->string(10),//月平均增长值率
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_analyze}}');
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
