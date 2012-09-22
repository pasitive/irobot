<?php

class m120922_101255_shared_video extends DbMigration
{
		// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->createTable('robot_video', array(
            'robot_id' => 'integer',
            'video_id' => 'integer',
        ));

        $this->addForeignKey('robot_video_robot_id', 'robot_video', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('robot_video_video_id', 'robot_video', 'video_id', 'video', 'id', 'CASCADE', 'RESTRICT');
	}

	public function safeDown()
	{
        $this->dropForeignKey('robot_video_robot_id', 'robot_video');
        $this->dropForeignKey('robot_video_video_id', 'robot_video');
        $this->dropTable('robot_video');
	}

}