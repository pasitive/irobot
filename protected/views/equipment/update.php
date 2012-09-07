<?php
$this->breadcrumbs=array(
	'Элементы комплектации'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Обновление',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Обновить элемент комплектации: <br><strong><?php echo $model->name; ?></strong></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>