<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
                                                         'id' => 'equipment-form',
                                                         'enableAjaxValidation' => false,
                                                         'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                    )); ?>

    <p class="note">Поля звездочкой <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'screen_name'); ?>
        <?php echo $form->textField($model, 'screen_name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'screen_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    <?php if ($model->isNewRecord) : ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'image'); ?>
        <?php echo CHtml::activeFileField($model, 'image'); ?>
        <?php echo $form->error($model, 'image'); ?>
    </div>
    <?php else: ?>
        
    <?php if(!empty($model->image)): ?>
        <?php echo CHtml::image($model->getImage(150)); ?>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'newImage'); ?>
        <?php echo CHtml::activeFileField($model, 'newImage'); ?>
        <?php echo $form->error($model, 'image'); ?>
    </div>
    <?php endif; ?>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->