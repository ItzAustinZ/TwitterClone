<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_160030_create_table_tweet extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tweet}}', [
            'id' => $this->primaryKey(),
            'owner' => $this->string()->notNull(),
            'key' => $this->string()->notNull()->defaultValue(""),
            'text' => $this->text()->defaultValue(""),
            'timestamp' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%tweet}}');
    }
}
