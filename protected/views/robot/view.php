<?php
$this->breadcrumbs = array(
    'Модели роботов' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'Создать', 'url' => array('create')),
    array('label' => 'Обновить', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Список', 'url' => array('index')),
    array('label' => 'Удалить', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Вы действительно хотите удалить эту запись?')),
);
?>

<h2>Общая информация</h2>

<?php $this->widget('zii.widgets.CDetailView',
                    array(
                         'data' => $model,
                         'attributes' => array(
                             array(
                                 'type' => 'image',
                                 'value' => $model->getImage(200),
                             ),
                             'id',
                             'name',
                             'screen_name',
                             'description:html',
                             'link_url:url',
                             'price',
                             'file_path',
                             'created_at',
                             'updated_at',
                         ),
                    )); ?>
<br/>

<!--Выводим характеристики-->
<?php if (!empty($equipment) && !empty($robotEquipment)) { ?>
<?php $this->renderPartial('/robotEquipment/_form', array('equipment' => $equipment, 'robotEquipment' => $robotEquipment, 'readonly' => true)) ?>
<?php } ?>

<!--Выводим комплектации-->
<?php if (!empty($feature) && !empty($robotFeature)) { ?>
<?php $this->renderPartial('/robotFeature/_form', array('feature' => $feature, 'robotFeature' => $robotFeature, 'readonly' => true)) ?>
<?php } ?>