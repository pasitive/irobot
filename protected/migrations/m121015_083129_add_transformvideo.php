<?php

class m121015_083129_add_transformvideo extends CDbMigration
{
	public function up()
	{
        $this->addColumn('robot', 'transformvideo', 'string');
	}

	public function down()
	{
		$this->dropColumn('robot', 'transformvideo');
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