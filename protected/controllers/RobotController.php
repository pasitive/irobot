<?php

class RobotController extends Controller
{

    public function init()
    {
        // Украли из CGridView
        $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')) . '/gridview';
        $cssFile = $baseScriptUrl . '/styles.css';
        Yii::app()->getClientScript()->registerCssFile($cssFile);

        parent::init();
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $_viewData = array();
        $model = Robot::model()
                ->with('robotFeatures')
                ->with('robotEquipments')
                ->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $buf = $this->loadModelFeaturesForUpdate($model);
        if ($buf !== false) {
            $feature = $buf['feature'];
            $robotFeature = $buf['robotFeature'];
            $_viewData = CMap::mergeArray($_viewData,
                                          array(
                                               'feature' => $feature,
                                               'robotFeature' => $robotFeature,
                                          ));
        }

        $buf = $this->loadModelEquipmentForUpdate($model);
        if ($buf !== false) {
            $equipment = $buf['equipment'];
            $robotEquipment = $buf['robotEquipment'];
            $_viewData = CMap::mergeArray($_viewData,
                                          array(
                                               'equipment' => $equipment,
                                               'robotEquipment' => $robotEquipment,
                                          ));
        }

        $_viewData['model'] = $model;

        $this->render('view', $_viewData);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Robot();

        $feature = Feature::model()->findAll();
        $robotFeature = array();
        foreach ($feature as $item) {
            $feature[$item->id] = $item;
            $robotFeature[$item->id] = new RobotFeature();
        }

        $equipment = Equipment::model()->findAll();
        $robotEquipment = array();
        foreach ($equipment as $item) {
            $equipment[$item->id] = $item;
            $robotEquipment[$item->id] = new RobotEquipment();
        }

        $this->processPostRequest($model, $robotFeature, $robotEquipment);

        $this->render('create', array(
                                     'model' => $model,
                                     'feature' => $feature,
                                     'robotFeature' => $robotFeature,
                                     'equipment' => $equipment,
                                     'robotEquipment' => $robotEquipment,
                                ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $_viewData = array();
        $model = Robot::model()
                ->with('robotFeatures')
                ->with('robotEquipments')
                ->cache(3600)->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $buf = $this->loadModelFeaturesForUpdate($model);
        if ($buf !== false) {
            $feature = $buf['feature'];
            $robotFeature = $buf['robotFeature'];
            $_viewData = CMap::mergeArray($_viewData,
                                          array(
                                               'feature' => $feature,
                                               'robotFeature' => $robotFeature,
                                          ));
        } else {
            $robotFeature = null;
        }

        $buf = $this->loadModelEquipmentForUpdate($model);
        if ($buf !== false) {
            $equipment = $buf['equipment'];
            $robotEquipment = $buf['robotEquipment'];
            $_viewData = CMap::mergeArray($_viewData,
                                          array(
                                               'equipment' => $equipment,
                                               'robotEquipment' => $robotEquipment,
                                          ));
        } else {
            $robotEquipment = null;
        }

        $_viewData['model'] = $model;

        $this->processPostRequest($model, $robotFeature, $robotEquipment);

        $this->render('update', $_viewData);
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
            $model = Robot::model()->findByPk($id);
            if ($model) {
                $model->delete();
            }

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
        $model = new Robot('search');

        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Robot']))
            $model->attributes = $_GET['Robot'];

        $this->render('index', array(
                                    'model' => $model,
                               ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'robot-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function loadModelFeaturesForUpdate(Robot $model)
    {
        $featureBuf = Feature::model()->cache(3600)->findAll();
        if (empty($featureBuf)) {
            return false;
        }

        $feature = array();
        foreach ($featureBuf as $item) {
            $feature[$item->id] = $item;
        }

        $robotFeatureBuf = $model->robotFeatures;
        foreach ($robotFeatureBuf as $item) {
            $robotFeature[$item->feature_id] = $item;
        }

        unset($robotFeatureBuf, $featureBuf);

        foreach ($feature as $item) {
            if (!isset($robotFeature[$item->id])) {
                $robotFeature[$item->id] = new RobotFeature();
            }
            unset($item);
        }

        return array(
            'robotFeature' => $robotFeature,
            'feature' => $feature,
        );
    }

    protected function loadModelEquipmentForUpdate(Robot $model)
    {
        $equipmentBuf = Equipment::model()->cache(3600)->findAll();
        if (empty($equipmentBuf)) {
            return false;
        }

        $equipment = array();
        foreach ($equipmentBuf as $item) {
            $equipment[$item->id] = $item;
        }

        $robotEquipmentBuf = $model->robotEquipments;
        $robotEquipment = array();
        foreach ($robotEquipmentBuf as $item) {
            $robotEquipment[$item->equipment_id] = $item;
        }

        unset($equipmentBuf, $robotEquipmentBuf);

        foreach ($equipment as $item) {
            if (!isset($robotEquipment[$item->id])) {
                $robotEquipment[$item->id] = new RobotEquipment();
            }
            unset($item);
        }

        return array(
            'robotEquipment' => $robotEquipment,
            'equipment' => $equipment,
        );
    }

    /**
     * @throws CHttpException
     * @param Robot $model
     * @param null $robotFeature
     * @param null $robotEquipment
     * @return void
     */
    protected function processPostRequest(Robot $model, $robotFeature = null, $robotEquipment = null)
    {
        if (isset($_POST['Robot']) ||
            isset($_POST['RobotFeature']) ||
            isset($_POST['RobotEquipment'])
        ) {
            $db = Yii::app()->db;
            $transaction = $db->beginTransaction();
            try {

                // Обработка модели
                $model->attributes = $_POST['Robot'];

                if($robotFeature !== null) {
                    foreach($robotFeature as $id => $item) {
                        if(isset($_POST['RobotFeature'][$id])) {
                            $item->attributes = $_POST['RobotFeature'][$id];
                        }
                    }
                }

                if($robotEquipment !== null) {
                    foreach($robotEquipment as $id => $item) {
                        if(isset($_POST['RobotEquipment'][$id])) {
                            $item->attributes = $_POST['RobotEquipment'][$id];
                        }
                    }
                }

                if (!$model->save()) {
                    throw new CHttpException(400, 'Не удалось сохранить данные робота');
                }


                if ($robotFeature !== null) {
                    // Обработка технических характеристик
                    $robotFeatureValid = true;
                    foreach ($robotFeature as $id => $item) {
                        if (isset($_POST['RobotFeature'][$id])) {
                            $item->feature_id = $id;
                            $item->robot_id = intval($model->id);
                            $robotFeatureValid = $item->validate() && $robotFeatureValid;
                        }
                    }

                    if ($robotFeatureValid) {
                        foreach ($robotFeature as $item) {
                            if (!$item->save()) {
                                throw new CHttpException(400, 'Не удалось сохранить техническую характеристику ' . $item->id);
                            }
                        }
                    }
                }

                if ($robotEquipment !== null) {
                    // Обработка комплектации
                    $robotEquipmentValid = true;
                    foreach ($robotEquipment as $id => $item) {
                        if (isset($_POST['RobotEquipment'][$id])) {
                            $item->equipment_id = $id;
                            $item->robot_id = intval($model->id);
                            $robotEquipmentValid = $item->validate() && $robotEquipmentValid;
                        }
                    }

                    if ($robotEquipmentValid) {
                        foreach ($robotEquipment as $item) {
                            if (!$item->save()) {
                                throw new CHttpException(400, 'Не удалось сохранить элемент комплектации ' . $item->id);
                            }
                        }
                    }
                }

                $transaction->commit();

                $this->redirect(array('/robot/index'));

            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
    }

    public function actionAjaxGroupDelete()
    {
        if(Yii::app()->request->getIsAjaxRequest()) {

        }
    }
}
