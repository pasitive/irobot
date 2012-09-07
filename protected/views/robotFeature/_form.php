<?php if(isset($robotFeature)): ?>
        
<?php $readonly = (isset($readonly) && $readonly === true) ? true : false; ?>

<div class="form">
    
    <h2>Технические характеристики робота</h2>

    <?php if(!$readonly) : ?>
    <p class="note">Все ТЕКСТОВЫЕ поля обязательны для заполнения</p>
    <?php endif; ?>

    <div class="grid-view">
        <table class="items">
            <?php foreach ($robotFeature as $i => $item) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'even' : 'odd' ?>">
                <td><?php echo CHtml::encode($feature[$i]->name) ?></td>
                <td>
                    <?php
                        switch ($feature[$i]->type) {
                            case 'boolean':
                                echo CHtml::activeCheckBox($item, "[$i]value",
                                                           array(
                                                                'disabled' => ($readonly ? 'disabled' : '')
                                                           ));
                                break;
                            case 'string':
                                echo CHtml::activeTextField($item, "[$i]value",
                                                            array(
                                                                 'readonly' => ($readonly ? 1 : ''),
                                                                 'value' => (($item->isNewRecord && empty($item->value))
                                                                         ? '-'
                                                                         : $item->value),
                                                            ));
                                break;
                        }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php endif; ?>