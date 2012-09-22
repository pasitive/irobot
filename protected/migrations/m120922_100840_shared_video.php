<?php

class m120922_100840_shared_video extends DbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->dropForeignKey('robot_video_robot', 'robot_video');
        $this->dropTable('robot_video');

        $this->createTable('video', array(
            'file_name' => 'varchar(255) NOT NULL',
            'preview_image' => 'varchar(255) NOT NULL',
            'status' => 'tinyint(1)',
        ));
	}

	public function safeDown()
	{
        return true;
	}
}