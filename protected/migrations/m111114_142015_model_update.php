<?php

class m111114_142015_model_update extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('robot', 'cleaning_text', 'text');
	}

	public function safeDown()
	{
        $this->dropColumn('robot', 'cleaning_text');
	}
}