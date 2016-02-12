<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_160012_add_name_to_key_connections extends Migration
{
    public function up()
    {
        $this->addColumn('key_connections', 'name', $this->text()->notNull());
    }

    public function down()
    {
        $this->dropColumn('key_connections', 'name');
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
