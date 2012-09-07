<?php

class m110927_203954_equipment_value extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->addColumn('robot_equipment', 'value', 'integer DEFAULT 0');
	}

	public function safeDown()
	{
        $this->dropColumn('robot_equipment', 'value');
	}
}