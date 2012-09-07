<?php
$this->breadcrumbs = array(
    'Элементы комплектации',
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
	$.fn.yiiGridView.update('equipment-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список элементов комплектации</h1>

<?php $this->widget('zii.widgets.grid.CGridView',
                    array(
                         'id' => 'equipment-grid',
                         'dataProvider' => $model->search(),
                         'filter' => null,
                         'pager' => array('pageSize' => 999),
                         'columns' => array(
                             array(
                                 'labelExpression' => '$data->name',
                                 'class' => 'LinkColumn',
                                 'urlExpression' => '$data->getImage(0)',
                                 'htmlOptions' => array('width' => '50'),
                                 'linkHtmlOptions' => array("class" => "fancy"),
                                 'imageUrlExpression' => '$data->getImage(50)',
                                 'imageHtmlOptions' => array('width' => 50),
                             ),
                             'screen_name',
                             array(
                                 'header' => 'Название',
                                 'class' => 'CLinkColumn',
                                 'urlExpression' => 'Yii::app()->createUrl("/equipment/view", array("id" => $data->id))',
                                 'labelExpression' => '$data->name',
                             ),
                             'created_at',
                             'updated_at',
                             array(
                                 'class' => 'CButtonColumn',
                             ),
                         ),
                    )); ?>
