<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_000517_create_table_key_connections extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%key_connections}}', [
            'id' => $this->primaryKey(),
            'owner' => $this->integer(),
            'text' => $this->string()->notNull()->defaultValue(""),
            'timestamp' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%key_connections}}');
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
