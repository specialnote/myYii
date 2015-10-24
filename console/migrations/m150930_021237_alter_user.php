<?php

use yii\db\Schema;
use yii\db\Migration;

class m150930_021237_alter_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}','is_mobile',Schema::TYPE_SMALLINT.'(3) NOT NULL DEFAULT 10 COMMENT "手机是否验证,10表示没有，20表示验证"');
        $this->addColumn('{{%user}}','is_email',Schema::TYPE_SMALLINT.'(3) NOT NULL DEFAULT 10 COMMENT "手机是否验证,10表示没有，20表示验证"');
    }

    public function down()
    {
        echo "m150930_021237_alter_user cannot be reverted.\n";

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
