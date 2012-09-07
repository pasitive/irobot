<?php
/**
 * Created by JetBrains PhpStorm.
 * User: boldinov
 * Date: 6/20/11
 * Time: 1:06 PM
 */

class DbMigration extends CDbMigration
{

    protected $options = 'engine=InnoDB;';

    public function createTable($table, $columns, $options = null)
    {
        if ($options != null) {
            $this->options .= $options;
        }

        $columns = CMap::mergeArray(array(
                                         'id' => 'pk',
                                    ), $columns);

        parent::createTable($table, $columns, $this->options);

        $this->addColumn($table, 'created_at', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addColumn($table, 'updated_at', 'TIMESTAMP');
    }
}