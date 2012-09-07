<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
                                                         'id' => 'robot-photo-form',
                                                         'enableAjaxValidation' => false,
                                                         'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                    )); ?>

    <p class="note">Поля отмеченные звездочкой <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'robot_id'); ?>
        <?php echo $form->dropDownList($model, 'robot_id', CHtml::listData(Robot::model()->findAll(), 'id', 'name')); ?>
        <?php echo $form->error($model, 'robot_id'); ?>
    </div>

    <div class="row">

        <?php
                $this->widget('CMultiFileUpload', array(
                                               'model' => $model,
                                               'attribute' => 'photos',
                                               'accept' => 'jpg|jpeg|gif|png',
                                          ));
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->