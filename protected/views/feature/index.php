<?php
$this->breadcrumbs = array(
    'Характеристики'
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
	$.fn.yiiGridView.update('feature-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список характеристик</h1>

<?php $this->widget('zii.widgets.grid.CGridView',
                    array(
                         'id' => 'feature-grid',
                         'dataProvider' => $model->search(),
                         'filter' => null,
                         'columns' => array(
                             'name',
                             array(
                                 'header' => 'Тип',
                                 'type' => 'text',
                                 'value' => '$data->getAttributeLabel("type_$data->type")',
                             ),
                             'created_at',
                             'updated_at',
                             array(
                                 'class' => 'CButtonColumn',
                             ),
                         ),
                    )); ?>
