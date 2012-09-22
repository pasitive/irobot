<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'video-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

    <p class="note">Поля отмеченные звездочкой <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'file_name'); ?>
        <?php echo CHtml::activeFileField($model, 'file_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'preview_image'); ?>
        <?php echo CHtml::activeFileField($model, 'preview_image'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->checkbox($model, 'status'); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->