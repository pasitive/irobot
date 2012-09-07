<?php

class ErrorController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array();
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {

            $response = array(
                'code' => $error['code'],
                'errorCode' => $error['errorCode'],
                'message' => $error['message'],
                'data' => array()
            );

            Header('Content-type:application/json');
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }
}