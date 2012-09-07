<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
                                                         'id' => 'feature-form',
                                                         'enableAjaxValidation' => false,
                                                    )); ?>

    <p class="note">Поля звездочкой <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', array('boolean' => 'Есть/Нет', 'string' => 'Строка'), array('maxlength' => 7)); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->