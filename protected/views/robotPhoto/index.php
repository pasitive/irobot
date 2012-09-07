<?php
$this->breadcrumbs = array(
    'Фото',
);

$this->menu = array(
    array('label' => 'Создать', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('robot-photo-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список фото роботов</h1>

<div class="search-form">
    <?php $this->renderPartial('_search', array(
                                               'model' => $model,
                                          )); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView',
                    array(
                         'id' => 'robot-photo-grid',
                         'dataProvider' => $model->search(),
                         'filter' => null,
                         'columns' => array(
                             array(
                                 'labelExpression' => '$data->robot->name',
                                 'class' => 'LinkColumn',
                                 'urlExpression' => '$data->getImage()',
                                 'htmlOptions' => array('width' => '50'),
                                 'linkHtmlOptions' => array("class" => "fancy"),
                                 'imageUrlExpression' => '$data->getImage(50)',
                                 'imageHtmlOptions' => array('width' => 50),
                             ),
                             'id',
                             array(
                                 'class' => 'CLinkColumn',
                                 'header' => 'Робот',
                                 'labelExpression' => '$data->robot->name',
                                 'urlExpression' => 'Yii::app()->createUrl("/robot/view/", array("id" => $data->robot->id))'
                             ),
                             'created_at',
                             'updated_at',
                             array(
                                 'class' => 'CButtonColumn',
                                 'template' => '{delete}',
                             ),
                         ),
                    )); ?>
