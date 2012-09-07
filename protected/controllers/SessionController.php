<?php

class SessionController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters() {
        return array();
    }
    
    public function actionCreate()
    {
        $model = new LoginForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        
        $this->render('create', array('model' => $model));
    }

    public function actionDestroy()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);

    }
    
}