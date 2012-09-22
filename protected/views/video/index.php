<?php
$this->breadcrumbs=array(
	'Видео'=>array('index'),
	'Список',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('video-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'robot-batch-update',
    'enableAjaxValidation' => false,
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'video-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
	'columns'=>array(
        'id',
        array(
            'class' => 'LinkColumn',
            'urlExpression' => '$data->getPreviewImage()',
            'htmlOptions' => array('width' => '150'),
            'linkHtmlOptions' => array("class" => "fancy"),
            'imageUrlExpression' => '$data->getPreviewImage(200)',
            'imageHtmlOptions' => array('width' => 150),
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value' => 'CHtml::activeCheckbox($data, "[$data->id]status", array("size" => 1))',
            'htmlOptions' => array('width' => 1),
        ),
		'created_at',
		'updated_at',
		array(
			'class'=>'CButtonColumn',
            'template' => '{delete}'
		),
	),
)); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>

</div>

<?php $this->endWidget(); ?>