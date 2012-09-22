<?php
$this->breadcrumbs=array(
	'Видео'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать Video</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>