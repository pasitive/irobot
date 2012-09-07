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
                             'price:number',
                             'created_at',
                             'updated_at',
                             array(
                                 'class' => 'CButtonColumn',
                             ),
                         ),
                    )); ?>
