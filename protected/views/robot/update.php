<?php
$this->breadcrumbs = array(
    'Модели роботов' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Обновление',
);

$this->menu = array(
    array('label' => 'Создать', 'url' => array('create')),
    array('label' => 'Просмотр', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Список', 'url' => array('index')),
);
?>

<h1>Обновить модель робота <?php echo $model->name; ?></h1>

<?php $form = $this->beginWidget('CActiveForm', array(
                                                     'id' => 'robot-form',
                                                     'enableAjaxValidation' => false,
                                                     'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                )); ?>
<?php echo $form->errorSummary($model); ?>

<?php

$tabs = array(
   'general' => array(
       'title' => 'Общее',
       'view' => '_form',
       'data' => array('model' => $model, 'form' => $form),
   ),
);

if(!empty($feature) && !empty($robotFeature)) {
    $tabs['features'] = array(
       'title' => 'Характеристики',
       'view' => '/robotFeature/_form',
       'data' => array('robotFeature' => $robotFeature, 'feature' => $feature, 'form' => $form),
   );
}

if(!empty($equipment) && !empty($robotEquipment)) {
    $tabs['equipment'] = array(
       'title' => 'Комплектация',
       'view' => '/robotEquipment/_form',
       'data' => array('robotEquipment' => $robotEquipment, 'equipment' => $equipment, 'form' => $form),
   );
}

$this->widget('CTabView', array(
                               'tabs' => $tabs
                          ));
?>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>