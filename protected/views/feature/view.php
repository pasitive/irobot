<?php
$this->breadcrumbs=array(
	'Характеристики'=>array('index'),
	$model->name,
);

$this->menu=array(
    array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Обновить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить эту запись?')),
);
?>

<h1>Просмотр характеристики</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
            'label' => 'Тип',
            'type' => 'raw',
            'value' => CHtml::encode($model->getAttributeLabel("type_$model->type")),
        ),
		'created_at',
		'updated_at',
	),
)); ?>
