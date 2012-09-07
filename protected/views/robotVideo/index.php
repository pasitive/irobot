<?php
$this->breadcrumbs = array(
    'Видео',
);

$this->menu = array(
    array('label' => 'Создать', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('robot-video-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список</h1>

<div class="search-form">
    <?php $this->renderPartial('_search', array(
                                               'model' => $model,
                                          )); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView',
                    array(
                         'id' => 'robot-video-grid',
                         'dataProvider' => $model->search(),
                         'pager' => array(
                             'class' => 'CLinkPager',
                             'pageSize' => 5,
                         ),
                         'filter' => null,
                         'columns' => array(
                             array(
                                 'labelExpression' => '$data->robot->name',
                                 'class' => 'LinkColumn',
                                 'urlExpression' => '$data->getPreviewImage()',
                                 'htmlOptions' => array('width' => '50'),
                                 'linkHtmlOptions' => array("class" => "fancy"),
                                 'imageUrlExpression' => '$data->getPreviewImage(100)',
                                 'imageHtmlOptions' => array('width' => 50),
                             ),
                             'id',
                             array(
                                 'class' => 'CLinkColumn',
                                 'header' => 'Робот',
                                 'labelExpression' => '$data->robot->name',
                                 'urlExpression' => 'Yii::app()->createUrl("/robot/view/", array("id" => $data->robot->id))'
                             ),
                             'file_name',
                             'created_at',
                             'updated_at',
                             array(
                                 'class' => 'CButtonColumn',
                                 'template' => '{delete}',
                             ),
                         ),
                    )); ?>