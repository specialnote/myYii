<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_110925_fund extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "混户基金（爱基金）"';
        }
        $this->createTable('{{%fund}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50),
            'num' => $this->string(8),
            'company' => $this->string(50),
            'type' => $this->string(10),
            'date' => $this->string(10),
            'week' => $this->string(10),
            'month' => $this->string(10),
            'quarter' => $this->string(10),
            'year' => $this->string(10),
            'three_year' => $this->string(10),
            'all' => $this->string(10),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%fund}}');
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
