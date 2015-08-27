<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_072330_alter_user extends Migration
{
    public function safeUp()
    {
        //添加 分组 字段
        $this->addColumn('{{%user}}','group',Schema::TYPE_SMALLINT.'(3) NOT NULL DEFAULT 100');
        //添加 手机 字段
        $this->addColumn('{{%user}}','mobile',Schema::TYPE_INTEGER.'(11)');
        //插入默认记录
        $this->insert('{{%user}}',[
            'username'=>'admin',
            'email'=>'2022281825@qq.com',
            'mobile'=>'18310722679',
            'password'=>Yii::$app->security->generatePasswordHash('123456'),
            'auth_key'=>Yii::$app->security->generateRandomString(),
            'password_reset_token' =>Yii::$app->security->generateRandomString(),
            'group'=>'300',
            'last_login_time'=>time(),
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
    }

    public function down()
    {
        echo "m150827_072330_alter_user cannot be reverted.\n";

        return false;
    }
}
