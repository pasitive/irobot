<?php

class RobotPhotoController extends Controller
{

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new RobotPhoto;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RobotPhoto'])) {
            $model->attributes = $_POST['RobotPhoto'];
            if ($model->validate()) {

                $photos = CUploadedFile::getInstances($model, 'photos');
                
                $hash = $model->generatePathHash();

                if (!empty($photos)) {
                    foreach ($photos as $photo) {

                        $image = Common::processImage($model, $photo, 0, $hash);

                        foreach($model->imageSize as $imageSize) {
                            Common::processImage($model, $photo, $imageSize, $hash);
                        }

                        $robotPhoto = new RobotPhoto();
                        $attributes = array(
                            'robot_id' => $model->robot_id,
                            'file_name' => $image['fileName'],
                        );
                        $robotPhoto->attributes = $attributes;
                        $robotPhoto->save();
                    }
                }

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
                                     'model' => $model,
                                ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new RobotPhoto('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['RobotPhoto']))
            $model->attributes = $_GET['RobotPhoto'];

        $this->render('index', array(
                                    'model' => $model,
                               ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = RobotPhoto::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'robot-photo-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
