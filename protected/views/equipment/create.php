<?php
$this->breadcrumbs=array(
	'Элементы комплектации'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать элемент комплектации</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>