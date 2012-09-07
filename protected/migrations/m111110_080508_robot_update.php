<?php

class m111110_080508_robot_update extends DbMigration
{
	public function safeUp()
	{
        $this->addColumn('robot', 'link_url', 'string');
        $this->addColumn('robot', 'screen_name', 'string');

        $this->createTable('robot_texture', array(
                                               'robot_id' => 'integer NOT NULL',
                                               'file_name' => 'integer NOT NULL',
                                             ));
        $this->addForeignKey('robot_texture_robot_id', 'robot_texture', 'robot_id', 'robot', 'id', 'CASCADE', 'RESTRICT');

        $this->addColumn('equipment', 'screen_name', 'string');

        $this->addColumn('robot_video', 'preview_image', 'string');

        $this->addColumn('robot', 'texture_file', 'string');
        $this->addColumn('robot', 'texture_name', 'string');
	}

	public function safeDown()
	{
        $this->dropColumn('robot', 'link_url');
        $this->dropColumn('robot', 'screen_name');

        $this->dropTable('robot_texture');

        $this->dropColumn('equipment', 'screen_name');

        $this->dropColumn('robot_video', 'preview_image');

        $this->dropColumn('robot', 'texture_file');
        $this->dropColumn('robot', 'texture_name');
	}
}