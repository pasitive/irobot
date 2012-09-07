<?php

class m111017_190921_robot_photo extends CDbMigration
{
	public function safeUp()
	{
        $this->dropColumn('robot_photo', 'thumb_name');
	}

	public function safeDown()
	{
        return;
	}
}