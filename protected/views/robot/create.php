<?php
$this->breadcrumbs = array(
    'Модели роботов' => array('index'),
    'Создать',
);

$this->menu = array(
    array('label' => 'Список', 'url' => array('index')),
);
?>

<h1>Добавить модель</h1>

<?php $form = $this->beginWidget('CActiveForm', array(
                                                     'id' => 'robot-form',
                                                     'enableAjaxValidation' => false,
                                                     'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                )); ?>
<div class="form">
<?php echo $form->errorSummary($model); ?>
</div>

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

if(!empty($video) && !empty($robotVideo)) {
    $tabs['video'] = array(
        'title' => 'Видео',
        'view' => '/robotVideo/_form',
        'data' => array('robotVideo' => $robotVideo, 'video' => $video, 'form' => $form),
    );
}

$this->widget('CTabView', array(
                               'tabs' => $tabs
                          ));

/*$this->renderPartial('_generalForm', array('model' => $model, 'form' => $form));
$this->renderPartial('_featureForm', array('robotFeature' => $robotFeature, 'feature' => $feature, 'form' => $form));
$this->renderPartial('_equipmentForm', array('robotEquipment' => $robotEquipment, 'equipment' => $equipment, 'form' => $form));*/
?>


<br/>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>