<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Добавить пользователя</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>