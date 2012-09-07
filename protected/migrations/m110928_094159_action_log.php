<?php

class m110928_094159_action_log extends DbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->createTable('action_log',
                           array(
                                'type' => "ENUM('a', 'm', 'd')",
                                'message' => 'string NOT NULL',
                                'model_class' => 'string NOT NULL',
                                'model_id' => 'integer NOT NULL',
                                'data' => 'text',
                           ));

        $this->createTable('update_package', array(
                                               'file_path' => 'string',
                                               'check_sum' => 'char(32)',
                                             ));
    }

    public function safeDown()
    {
        $this->dropTable('action_log');
        $this->dropTable('update_package');
    }
}