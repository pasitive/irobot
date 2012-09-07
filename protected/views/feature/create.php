<?php
$this->breadcrumbs=array(
	'Характеристики'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать характеристику</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>