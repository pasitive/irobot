<div class="form">

    <?php $form = $this->beginWidget('CActiveForm',
                                     array(
                                          'id' => 'robot-video-form',
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

    <?php if($model->isNewRecord) : ?>
        
    <div class="row">
        <?php echo $form->labelEx($model, 'file_name'); ?>
        <?php echo CHtml::activeFileField($model, 'file_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'preview_image'); ?>
        <?php echo CHtml::activeFileField($model, 'preview_image'); ?>
    </div>

    <?php else : ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'newFileName'); ?>
        <?php echo CHtml::activeFileField($model, 'newFileName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'newPreviewImage'); ?>
        <?php echo CHtml::activeFileField($model, 'newPreviewImage'); ?>
    </div>
    
    <?php endif; ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->