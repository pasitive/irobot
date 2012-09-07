<h2>Характеристики</h2>
<?php $this->widget('application.components.GridView',
                    array(
                         'id' => 'feature-list-grid',
                         'dataProvider' => $dataProvider,
                         'filter' => null,
                         'hideHeader' => true,
                         'template' => '{items}',
                         'columns' => array(
                             'feature.name',
                             'value',
                         ),
                    )); ?>