<?php

class m110923_122407_init extends DbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->createTable('user', array(
                                        'first_name' => 'string',
                                        'middle_name' => 'string',
                                        'last_name' => 'string',
                                        'username' => 'string NOT NULL',
                                        'password' => 'char(32) NOT NULL',
                                        'role' => 'ENUM(\'member\', \'admin\') NOT NULL',
                                   ));
        $this->createIndex('user_username', 'user', 'username', true);

        $this->createTable('robot', array(
                                         'name' => 'string NOT NULL',
                                         'description' => 'text NOT NULL',
                                         'price' => 'decimal(10,2) NOT NULL',
                                         'file_path' => 'string NOT NULL',
                                    ));

        // Технические характеристики робота
        $this->createTable('feature', array(
                                           'name' => 'string NOT NULL',
                                           'type' => "ENUM('boolean', 'string')",
                                      ));

        $this->createTable('robot_feature', array(
                                                 'robot_id' => 'integer NOT NULL',
                                                 'feature_id' => 'integer NOT NULL',
                                                 'value' => 'string NOT NULL',
                                            ));
        $this->addForeignKey('robot_feature_feature', 'robot_feature', 'feature_id', 'feature', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('robot_feature_robot', 'robot_feature', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');

        // Комплектации
        $this->createTable('equipment', array(
                                          'name' => 'string NOT NULL',
                                          'description' => 'text NOT NULL',
                                          'image' => 'string NOT NULL',
                                        ));

        $this->createTable('robot_equipment', array(
                                                'robot_id' => 'integer NOT NULL',
                                                'equipment_id' => 'integer NOT NULL',
                                              ));
        $this->addForeignKey('robot_equipment_robot', 'robot_equipment', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('robot_equipment_equipment', 'robot_equipment', 'equipment_id', 'equipment', 'id', 'CASCADE', 'RESTRICT');
        
    }

    public function safeDown()
    {
        return false;
    }
}