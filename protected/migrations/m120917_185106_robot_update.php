<?php

class m120917_185106_robot_update extends CDbMigration
{
	public function up()
	{
        $this->addColumn('robot', 'file_path_pod', 'varchar(255)');
	}

	public function down()
	{
        $this->dropColumn('robot', 'file_path_pod');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}