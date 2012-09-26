<?php
$this->breadcrumbs = array(
    'Модели роботов',
);

$this->menu = array(
    array('label' => 'Создать', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('robot-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список моделей роботов</h1>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
    'model' => $model,
)); ?>
</div><!-- search-form -->

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'robot-batch-update',
    'enableAjaxValidation' => false,
)); ?>

    <?php echo $form->errorSummary($items); ?>

    <?php $this->widget('zii.widgets.grid.CGridView',
    array(
        'id' => 'robot-grid',
        'dataProvider' => $model->search(),
        'filter' => null,
        'columns' => array(
            'id',
            array(
                'class' => 'CLinkColumn',
                'header' => 'Робот',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->createUrl("/robot/view/", array("id" => $data->id))'
            ),
            array(
                'name' => 'price',
                'type' => 'raw',
                'value' => 'CHtml::activeTextField($data, "[$data->id]price", array("size" => 8))',
                'htmlOptions' => array('width' => 1),
            ),
            'created_at',
            'updated_at',
            array(
                'name' => 'sort',
                'type' => 'raw',
                'value' => 'CHtml::activeTextField($data, "[$data->id]sort", array("size" => 1))',
                'htmlOptions' => array('width' => 1),
            ),
            array(
                'name' => 'scale',
                'type' => 'raw',
                'value' => 'CHtml::activeTextField($data, "[$data->id]scale", array("size" => 1))',
                'htmlOptions' => array('width' => 1),
            ),

            array(
                'name' => 'status',
                'type' => 'raw',
                'value' => 'CHtml::activeCheckbox($data, "[$data->id]status", array("size" => 1))',
                'htmlOptions' => array('width' => 1),
            ),
            array(
                'class' => 'CButtonColumn',
            ),
        ),
    )); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>
</div>

<?php $this->endWidget(); ?>