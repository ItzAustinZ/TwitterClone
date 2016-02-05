<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_152649_add_roles_to_users extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'role', $this->integer()->notNull()->defaultValue(10));
        $this->addColumn('user', 'timestamp', $this->integer()->notNull());
        $this->addColumn('user', 'numTweets', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('user', 'role');
        $this->dropColumn('user', 'timestamp');
        $this->dropColumn('user', 'numTweets');
    }
}
