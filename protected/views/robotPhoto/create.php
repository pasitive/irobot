<?php
$this->breadcrumbs=array(
	'Фото' => array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать фото</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>