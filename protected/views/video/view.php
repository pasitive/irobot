<?php
$this->breadcrumbs=array(
	'Видео'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить эту запись?')),
);
?>

<h1>Просмотр</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'file_name',
		array(
            'type' => 'image',
            'value' => $model->getPreviewImage(400)
        ),
		'status',
		'created_at',
		'updated_at',
	),
)); ?>
