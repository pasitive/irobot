<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
          media="screen, projection"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
          media="print"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
          media="screen, projection"/>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

    <div id="header">
        <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
    </div>
    <!-- header -->

    <div id="mainmenu">
        <?php $this->widget('zii.widgets.CMenu', array(
                                                      'items' => array(
                                                          array('label' => 'Комплектации', 'url' => array('/equipment'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Характеристики', 'url' => array('/feature'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Модели', 'url' => array('/robot'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Фото', 'url' => array('/robotPhoto'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Видео', 'url' => array('/robotVideo'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Пользователи', 'url' => array('/user'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Лог действий', 'url' => array('/actionLog'), 'visible' => !Yii::app()->user->isGuest),
                                                          array('label' => 'Вход', 'url' => array('/session/create'), 'visible' => Yii::app()->user->isGuest),
                                                          array('label' => 'Выход (' . Yii::app()->user->name . ')', 'url' => array('/session/destroy'), 'visible' => !Yii::app()->user->isGuest)
                                                      ),
                                                 )); ?>
    </div>
    <!-- mainmenu -->
    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                                             'links' => $this->breadcrumbs,
                                                        )); ?><!-- breadcrumbs -->
    <?php endif?>

    <?php echo $content; ?>

    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by <?php echo CHtml::link('www.irobot.ru', 'www.irobor.ru') ?>.<br/>
        All Rights Reserved.<br/>
        <?php if (YII_DEBUG) {
        list($queryCount, $queryTime) = Yii::app()->db->getStats();
        echo "Query count: $queryCount, Total query time: " . sprintf('%0.5f', $queryTime) . "s";
    }
        ?>
        <?php if (YII_DEBUG) : ?>
        <div>
            Execution Time: <?php echo round(CLogger::getExecutionTime(), 3);?> sec<br/>
            Memory Usage: <?php echo round(CLogger::getMemoryUsage() / 1048576, 2);?> mb
        </div>
        <?php endif; ?>
    </div>
    <!-- footer -->

</div>
<!-- page -->
<script type="text/javascript">
    $(function() {
        $("a.fancy").fancybox({
            transitionIn:'elastic'
        });
    });
</script>
</body>
</html>