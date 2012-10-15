<?php

class m120926_080818_scale_create extends CDbMigration
{
	public function up()
	{
        $this->addColumn('robot', 'scale', 'decimal(2,1) DEFAULT 1.0');
	}

	public function down()
	{
		$this->dropColumn('robot', 'scale');
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