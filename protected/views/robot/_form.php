<h2>Общее</h2>

<div class="form">

  <p class="note">Поля отмеченные звездочкой <span class="required">*</span> обязательны для заполнения.</p>

  <div class="row">
      <?php echo $form->labelEx($model, 'name'); ?>
      <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
      <?php echo $form->error($model, 'name'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'screen_name'); ?>
      <?php echo $form->textField($model, 'screen_name', array('size' => 60, 'maxlength' => 255)); ?>
      <?php echo $form->error($model, 'screen_name'); ?>
    <p class="hint">Название робота, которое будет выводиться в клиентских приложениях в нижней карусели</p>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'link_url'); ?>
      <?php echo $form->textField($model, 'link_url', array('size' => 60, 'maxlength' => 255)); ?>
      <?php echo $form->error($model, 'link_url'); ?>
    <p class="hint">Адрес страницы при нажатии на кнопку "Купить"</p>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'description'); ?>
      <?php $this->widget('application.extensions.tinymce.TinyMce',
      array(
          'htmlOptions' => array('cols' => 50, 'rows' => 6),
          'model' => $model,
          'attribute' => 'description',
      ));
      ?>
      <?php echo $form->error($model, 'description'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'cleaning_text'); ?>
      <?php $this->widget('application.extensions.tinymce.TinyMce',
      array(
          'htmlOptions' => array('cols' => 50, 'rows' => 6),
          'model' => $model,
          'attribute' => 'cleaning_text',
      ));
      ?>
      <?php echo $form->error($model, 'cleaning_text'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'price'); ?>
      <?php echo $form->textField($model, 'price', array('size' => 10, 'maxlength' => 10)); ?>
      <?php echo $form->error($model, 'price'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'scale'); ?>
      <?php echo $form->textField($model, 'scale', array('size' => 1, 'maxlength' => 3)); ?>
      <?php echo $form->error($model, 'scale'); ?>
    <p class="hint">Масштаб при выводе модели робота</p>
  </div>

    <?php if ($model->isNewRecord) : ?>

  <div class="row">
      <?php echo $form->labelEx($model, 'transformvideo'); ?>
      <?php echo CHtml::activeFileField($model, 'transformvideo'); ?>
      <?php echo $form->error($model, 'transformvideo'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'file_path'); ?>
      <?php echo CHtml::activeFileField($model, 'file_path'); ?>
      <?php echo $form->error($model, 'file_path'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'file_path_pod'); ?>
      <?php echo CHtml::activeFileField($model, 'file_path_pod'); ?>
      <?php echo $form->error($model, 'file_path_pod'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'texture_file'); ?>
      <?php $this->widget('CMultiFileUpload', array(
      'model' => $model,
      'attribute' => 'texture_file',
      'accept' => 'jpg|gif|png',
  )); ?>
      <?php echo $form->error($model, 'texture_file'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'image'); ?>
      <?php echo CHtml::activeFileField($model, 'image'); ?>
      <?php echo $form->error($model, 'image'); ?>
  </div>

    <?php else: ?>

  <div class="row">
      <?php echo $form->labelEx($model, 'newTransformvideo'); ?>
      <?php echo CHtml::activeFileField($model, 'newTransformvideo'); ?>
      <?php echo $form->error($model, 'newTransformvideo'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'file_path'); ?>

      <?php if (!empty($model->file_path)): ?>
      <?php echo CHtml::link($model->getFilePath(), $model->getFilePath()); ?>
      <?php endif; ?>
  </div>


  <div class="row">
      <?php echo $form->labelEx($model, 'newFilePath'); ?>
      <?php echo CHtml::activeFileField($model, 'newFilePath'); ?>
      <?php echo $form->error($model, 'newFilePath'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'file_path_pod'); ?>

      <?php if (!empty($model->file_path_pod)): ?>
      <?php echo CHtml::link($model->getFilePathPod(), $model->getFilePathPod()); ?>
      <?php endif; ?>

  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'newFilePathPod'); ?>
      <?php echo CHtml::activeFileField($model, 'newFilePathPod'); ?>
      <?php echo $form->error($model, 'newFilePathPod'); ?>
  </div>

  <hr/>

  <h2>Текстура</h2>

    <?php if (!empty($model->texture_file)): ?>
        <?php
        if (!is_array(CJSON::decode($model->texture_file))) {
            echo CHtml::image($model->getTextureFileByName($model->texture_file, false, true), '', array('width' => 150));
        } else {
            $textures = CJSON::decode($model->texture_file);
            echo '<table><tr>';
            foreach ($textures as $texture) {
                $image = CHtml::image($model->getTextureFileByName($texture, false, true), '', array('width' => 150));
                $link = '<a href="' . $model->getTextureFileByName($texture, false, true) . '">' . $image . '</a>';
                //echo '<td>' . CHtml::image($model->getTextureFileByName($texture, false, true), '', array('width' => 150)) . '</td>';
                echo '<td>' . $link . '</td>';
            }
            echo '</tr></table>';
        }
        ?>

        <?php endif; ?>

  <div class="row">
      <?php echo $form->labelEx($model, 'newTextureFile'); ?>
      <?php $this->widget('CMultiFileUpload', array(
      'model' => $model,
      'attribute' => 'newTextureFile',
      'accept' => 'jpg|gif|png',
  )); ?>
      <?php echo $form->error($model, 'newTextureFile'); ?>
  </div>

  <hr/>

  <h2>Изображение</h2>

    <?php if (!empty($model->image)): ?>
        <?php echo CHtml::image($model->getImage(200)); ?>
        <?php endif; ?>
  <div class="row">
      <?php echo $form->labelEx($model, 'newImage'); ?>
      <?php echo CHtml::activeFileField($model, 'newImage'); ?>
      <?php echo $form->error($model, 'newImage'); ?>
  </div>
    <?php endif; ?>

</div><!-- form -->