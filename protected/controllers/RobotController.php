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

        $feature = Feature::model()->findAll(array('index' => 'id'));
        $robotFeature = array();
        foreach ($feature as $id => $item) {
            $robotFeature[$id] = new RobotFeature();
        }

        $equipment = Equipment::model()->findAll(array('index' => 'id'));
        $robotEquipment = array();
        foreach ($equipment as $id => $item) {
            $robotEquipment[$id] = new RobotEquipment();
        }

        $video = Video::model()->active()->findAll(array('index' => 'id'));
        $robotVideo = array();
        foreach($video as $id => $item) {
            $robotVideo[$id] = new RobotVideo();
        }

        $this->processPostRequest($model, $robotFeature, $robotEquipment, $robotVideo);

        $this->render('create', array(
            'model' => $model,
            'feature' => $feature,
            'robotFeature' => $robotFeature,
            'equipment' => $equipment,
            'robotEquipment' => $robotEquipment,
            'video' => $video,
            'robotVideo' => $robotVideo,
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
            ->with('robotVideos')
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

        $buf = $this->loadModelVideoForUpdate($model);
        if ($buf !== false) {
            $video = $buf['video'];
            $robotVideo = $buf['robotVideo'];
            $_viewData = CMap::mergeArray($_viewData,
                array(
                    'video' => $video,
                    'robotVideo' => $robotVideo,
                ));
        } else {
            $robotVideo = null;
        }

        $_viewData['model'] = $model;

        $this->processPostRequest($model, $robotFeature, $robotEquipment, $robotVideo);

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
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Robot('search');

        if (isset($_POST['Robot']) && count($_POST['Robot'] > 0)) {

            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', array_keys($_POST['Robot']));
            $criteria->index = 'id';

            $items = Robot::model()->findAll($criteria);

            $valid = true;
            foreach ($items as $id => $item) {
                if (isset($_POST['Robot'][$id])) {
                    $item->attributes = $_POST['Robot'][$id];
                }
                $valid = $item->validate() && $valid;
            }

            if($valid) {
                foreach($items as $item) {
                    $item->save();
                }
                Yii::app()->user->setFlash('success', 'Сортировка обновлена');
                $this->refresh();
            }
        }

        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Robot'])) {
            $model->attributes = $_GET['Robot'];
        }


        $this->render('index', array(
            'model' => $model,
            'items' => isset($items) ? $items : array(),
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

    protected function loadModelVideoForUpdate(Robot $model)
    {
        $video = Video::model()->active()->findAll(array('index' => 'id'));
        if (empty($video)) {
            return false;
        }

        $robotVideoBuf = $model->robotVideos;
        $robotVideo = array();
        foreach ($robotVideoBuf as $item) {
            $robotVideo[$item->video_id] = $item;
        }

        unset($robotVideoBuf);

        foreach ($video as $item) {
            if (!isset($robotVideo[$item->id])) {
                $robotVideo[$item->id] = new RobotVideo();
            }
            unset($item);
        }

        return array(
            'robotVideo' => $robotVideo,
            'video' => $video,
        );
    }

    /**
     * @throws CHttpException
     * @param Robot $model
     * @param null $robotFeature
     * @param null $robotEquipment
     * @return void
     */
    protected function processPostRequest(Robot $model, $robotFeature = null, $robotEquipment = null, $robotVideo = null)
    {
        if (isset($_POST['Robot']) ||
            isset($_POST['RobotFeature']) ||
            isset($_POST['RobotEquipment']) ||
            isset($_POST['RobotVideo'])
        ) {
            $db = Yii::app()->db;
            $transaction = $db->beginTransaction();
            try {

                // Обработка модели
                $model->attributes = $_POST['Robot'];

                if ($robotFeature !== null) {
                    foreach ($robotFeature as $id => $item) {
                        if (isset($_POST['RobotFeature'][$id])) {
                            $item->attributes = $_POST['RobotFeature'][$id];
                        }
                    }
                }

                if ($robotEquipment !== null) {
                    foreach ($robotEquipment as $id => $item) {
                        if (isset($_POST['RobotEquipment'][$id])) {
                            $item->attributes = $_POST['RobotEquipment'][$id];
                        }
                    }
                }

                if($robotVideo !== null) {
                    foreach ($robotVideo as $id => $item) {
                        if (isset($_POST['RobotVideo'][$id]) && intval($_POST['RobotVideo'][$id]['status']) == 1) {
                            $item->attributes = $_POST['RobotVideo'][$id];
                        } else {
                            unset($robotVideo[$id]);
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

                if ($robotVideo !== null) {
                    // Обработка комплектации
                    $robotVideoValid = true;
                    foreach ($robotVideo as $id => $item) {
                        if (isset($_POST['RobotVideo'][$id]) && intval($_POST['RobotVideo'][$id]['status']) == 1) {
                            $item->video_id = $id;
                            $item->robot_id = intval($model->id);
                            $robotVideoValid = $item->validate() && $robotVideoValid;
                        } else {
                            RobotVideo::model()->deleteAllByAttributes(array(
                                'robot_id' => $model->id,
                                'video_id' => $id,
                            ));
                        }
                    }

                    if ($robotVideoValid) {
                        foreach ($robotVideo as $item) {
                            if (!$item->save()) {
                                throw new CHttpException(400, 'Не удалось сохранить элемент видео ' . $item->id);
                            }
                        }
                    }
                }

                $transaction->commit();
                Yii::app()->user->setFlash('success', 'Новая модель упешно добавлена.');

            } catch (Exception $e) {
                $transaction->rollback();
                Yii::app()->user->setFlash('error', 'При добавлении модели произошла ошибка.');
            }

            $this->redirect(array('/robot/index'));
        }
    }

    public function actionAjaxGroupDelete()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {

        }
    }
}
