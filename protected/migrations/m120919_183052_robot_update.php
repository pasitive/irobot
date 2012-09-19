<?php

class m120919_183052_robot_update extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->addColumn('robot', 'sort', 'integer DEFAULT 0');
	}

	public function safeDown()
	{
        $this->dropColumn('robot', 'sort');
	}
}