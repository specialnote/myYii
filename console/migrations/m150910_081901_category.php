<?php

use yii\db\Schema;
use yii\db\Migration;

class m150910_081901_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'slug' => $this->string(20)->notNull()->unique(),
            'description' => $this->string(500),
            'article_counts'=>$this->integer()->notNull()->defaultValue(0),
            'parent' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ], $tableOptions);

        $this->insert('{{%category}}',[
            'name'=>'采集',
            'slug'=>'gather',
            'description'=>'所有采集文章分类',
            'article_counts'=>0,
            'parent'=>0,
            'status'=>10,
            'created_at'=>time(),
            'updated_at'=>time()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
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
