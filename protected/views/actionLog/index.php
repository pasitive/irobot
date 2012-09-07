<?php
$this->breadcrumbs=array(
	'Лог действий'=>array('index'),
	'Список',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('action-log-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список</h1>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'action-log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
	'columns'=>array(
		'id',
		'typeAsString',
		'message',
		'model_class',
		'model_id',
//		'data',
//		'created_at',
		'updated_at',
	),
)); ?>
