<?php

class m120919_190647_robot_published_create extends CDbMigration
{
	public function up()
	{
        $this->addColumn('robot', 'status', 'tinyint(1)');
	}

	public function down()
	{
		$this->dropColumn('robot', 'status');
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