<?php

use yii\db\Schema;
use yii\db\Migration;

class m160108_035002_url_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "URL采集记录"';
        }
        $this->createTable('{{%fund_url_log}}', [
            'id' => $this->primaryKey(),
            'md5_url'=>$this->string(32),
            'url'=>$this->string(100),//url
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%fund_url_log}}');
        return false;
    }
}
