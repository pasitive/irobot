<?php

class m111006_102644_photo_video extends DbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('robot', 'image', 'string');

        $this->createTable('robot_photo', array(
                                               'robot_id' => 'integer NOT NULL',
                                               'file_name' => 'string NOT NULL',
                                               'thumb_name' => 'string NOT NULL',
                                          ));
        $this->addForeignKey('robot_photo_robot', 'robot_photo', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('robot_video', array(
                                               'robot_id' => 'integer NOT NULL',
                                               'file_name' => 'string NOT NULL',
                                          ));
        $this->addForeignKey('robot_video_robot', 'robot_video', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropForeignKey('robot_photo_robot', 'robot_photo');
        $this->dropForeignKey('robot_video_robot', 'robot_video');
        $this->dropColumn('robot', 'image');
        $this->dropTable('robot_photo');
        $this->dropTable('robot_video');
    }
}