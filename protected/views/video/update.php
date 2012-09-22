<?php
$this->breadcrumbs=array(
	'Видео'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Обновление',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Обновить Video <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>