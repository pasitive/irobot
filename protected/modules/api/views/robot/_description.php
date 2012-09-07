<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 10/31/11
 * Time: 8:43 PM
 * To change this template use File | Settings | File Templates.
 */
 
echo $description;

echo '<br />';

if(isset($dataProvider)) {
    $this->renderPartial('//feature/_list', array('dataProvider' => $dataProvider));
}