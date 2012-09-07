<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 11/23/11
 * Time: 11:24 PM
 * To change this template use File | Settings | File Templates.
 */

Yii::import('zii.widgets.grid.CGridView');

class GridView extends CGridView
{

    public function init()
    {
        $this->initColumns();
    }

    public function registerClientScript()
    {

    }
}
