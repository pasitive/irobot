<?php if (isset($robotEquipment)): ?>

<?php $readonly = (isset($readonly) && $readonly === true) ? true : false; ?>

<div class="form">
    <h2>Комплектация робота</h2>
    <div class="grid-view">
        <table class="items">
            <?php foreach ($robotEquipment as $i => $item) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'even' : 'odd' ?>">
                <td width="50"><?php
                    echo CHtml::link(
                            CHtml::image(
                                $equipment[$i]->getImage(50),
                                "",
                                array("width" => 50)
                            ),
                            $equipment[$i]->getImage(0),
                            array("class" => "fancy")
                    )
                    ?></td>
                <td><?php echo CHtml::link($equipment[$i]->name, array('/equipment/view', 'id' => $equipment[$i]->id)) ?></td>
                <td><?php echo CHtml::activeCheckBox($item, "[$i]value", array('disabled' => ($readonly ? 'disabled' : ''))) ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div><!-- form -->

<script type="text/javascript">
    $(document).ready(function(){
        <?php if(!$readonly) { ?>
        $('.form input[type=text]').placeholder();
        <?php } ?>
    })
</script>

<?php endif; ?>