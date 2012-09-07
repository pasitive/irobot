<?php

class m110927_103124_service extends DbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->createTable('service', array(
                                           'feature_count' => 'integer DEFAULT 0',
                                           'equipment_count' => 'integer DEFAULT 0',
                                      ));
        $this->createIndex('service_feature_count', 'service', 'feature_count');
        $this->createIndex('service_equipment_count', 'service', 'equipment_count');

        $this->insert('service', array());
    }

    public function safeDown()
    {
        $this->dropIndex('service_feature_count', 'service');
        $this->dropIndex('service_equipment_count', 'service');
        $this->dropTable('service');
    }
}