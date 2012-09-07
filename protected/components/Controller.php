<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views.old/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function init()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery')
                ->registerScriptFile('/js/fancybox/jquery.fancybox-1.3.4.js')
                ->registerScriptFile('/js/fancybox/jquery.easing-1.3.pack.js')
                ->registerScriptFile('/js/scripts.js')
                ->registerCssFile('/js/fancybox/jquery.fancybox-1.3.4.css');
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                  'users' => array('@'),
            ),
            array('deny', // deny all users
                  'users' => array('*'),
            ),
        );
    }
}