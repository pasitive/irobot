<?php if (isset($robotVideo)): ?>

<?php $readonly = (isset($readonly) && $readonly === true) ? true : false; ?>

<div class="form">
    <h2>Видео</h2>
    <div class="grid-view">
        <table class="items">
            <?php foreach ($robotVideo as $i => $item) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'even' : 'odd' ?>">
                <td width="50"><?php
                    echo CHtml::link(
                            CHtml::image(
                                $video[$i]->getPreviewImage(200),
                                "",
                                array("width" => 200)
                            ),
                            $video[$i]->getPreviewImage(0),
                            array("class" => "fancy")
                    )
                    ?></td>
                <td><?php echo CHtml::activeCheckBox($item, "[$i]status", array('disabled' => ($readonly ? 'disabled' : ''))) ?></td>
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