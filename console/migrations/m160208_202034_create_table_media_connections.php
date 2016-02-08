<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_202034_create_table_media_connections extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%media_connections}}', [
            'id' => $this->primaryKey(),
            'tweet' => $this->integer(),
            'url' => $this->string()->notNull()->defaultValue(""),
            'timestamp' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%media_connections}}');
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
