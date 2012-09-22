<?php

class RobotController extends Controller
{
    public $layout = '//layouts/blank';

    public function actionList($format = 'xml')
    {
        switch ($format) {
            case self::FORMAT_XML:
                $this->isJsonResponse = false;
                Header('Content-type: application/xml');
                echo $this->getXml()->saveXML();
                break;

            case self::FORMAT_JSON:
                $this->getJson();
                break;
        }

    }

    public function actionCheck($robotId)
    {
        $model = $this->loadModel($robotId);

        $response = array(
            'updated_at' => date_format(date_create($model->updated_at), DATE_RFC822),
        );

        $this->data = $response;
    }

    public function actionPackage($robotId)
    {
        $model = $this->loadModel($robotId);

        $this->isJsonResponse = false;

        $rootDir = $robotId;

        $zip = new ZipArchive();
        $xml = new Xml();

        $package = new UpdatePackage();
        $hashString = $package->generatePathHash();
        $resourcePath = $package->generatePath($hashString);

        $packageName = $hashString . md5(date('j_m_Y_h_i_s')) . '.zip';
        $packagePath = $resourcePath . DIRECTORY_SEPARATOR . $packageName;

        $res = $zip->open($packagePath, ZipArchive::CREATE);
        $zip->addEmptyDir($rootDir);

        if ($res !== true) {
            throw new CHttpException(400, 'Error creating zip archive', Error::ERROR_INTERNAL);
        }

        $robotInfo = $this->getXmlForRobotId($model, $xml);

        $xml->appendChild($robotInfo);

        if (!$robotInfo) {
            throw new CHttpException(400, 'Error creating xml', Error::ERROR_INTERNAL);
        }

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getImage(200);
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getImage(200, true);
        $zip->addFile($file, $local);

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getImage();
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getImage(0, true);
        $zip->addFile($file, $local);

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getFilePath();
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getFilePath(true);
        $zip->addFile($file, $local);

        if (!is_array(CJSON::decode($model->texture_file))) {
            $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getTextureFileByName($model->texture_file);
            $local = $rootDir . DIRECTORY_SEPARATOR . $model->getTextureFileByName($model->texture_file, true, true);
            $zip->addFile($file, $local);
        } else {
            $textures = CJSON::decode($model->texture_file);
            foreach ($textures as $texture) {
                $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getTextureFileByName($texture, false, true);
                $local = $rootDir . DIRECTORY_SEPARATOR . $model->getTextureFileByName($texture, true, true);
                $zip->addFile($file, $local);
            }
        }

        //Photos
        $photoDir = 'Photo';
        $zip->addEmptyDir($rootDir . DIRECTORY_SEPARATOR . $photoDir);

        $robotPhotos = !empty($model->robotPhotos) ? $model->robotPhotos : array();
        foreach ($robotPhotos as $photo) {
            $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $photo->getImage(50);
            $local = $rootDir . DIRECTORY_SEPARATOR . $photoDir . DIRECTORY_SEPARATOR . $photo->getImage(50, true);
            $zip->addFile($file, $local);

            $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $photo->getImage(400);
            $local = $rootDir . DIRECTORY_SEPARATOR . $photoDir . DIRECTORY_SEPARATOR . $photo->getImage(400, true);
            $zip->addFile($file, $local);

            $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $photo->getImage(0);
            $local = $rootDir . DIRECTORY_SEPARATOR . $photoDir . DIRECTORY_SEPARATOR . $photo->getImage(0, true);
            $zip->addFile($file, $local);
        }

        //Equipment images
        $modelEquipment = !empty($model->robotEquipments) ? $model->robotEquipments : array();
        foreach ($modelEquipment as $index => $equipmentItem) {
            if (intval($equipmentItem->value) !== 0) {
                $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $equipmentItem->equipment->getImage(0);
                $local = $rootDir . DIRECTORY_SEPARATOR . $equipmentItem->equipment->getImage(0, true);
                $zip->addFile($file, $local);
            }
        }

        $zip->addFromString($rootDir . DIRECTORY_SEPARATOR . 'Info.xml', $xml->saveXML());

        $dataProvider = new CArrayDataProvider($model->robotFeatures, array(
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));

        $description = $this->render('_description', array(
            'description' => $model->description,
            'dataProvider' => $dataProvider,
        ), true);

        $images = $this->loadImagesFromSource($description);
        $local = $rootDir . DIRECTORY_SEPARATOR . 'images';
        foreach ($images as $url) {
            $data = file_get_contents($url);
            $zip->addFromString($local . DIRECTORY_SEPARATOR . basename($url), $data);
            $description = str_replace($url, 'images' . DIRECTORY_SEPARATOR . basename($url), $description);
        }
        $zip->addFromString($rootDir . DIRECTORY_SEPARATOR . 'Description.html', $description);

        $cleaningText = $this->render('_description', array('description' => $model->cleaning_text), true);

        $images = $this->loadImagesFromSource($cleaningText);
        foreach ($images as $url) {
            $data = file_get_contents($url);
            $zip->addFromString($local . DIRECTORY_SEPARATOR . basename($url), $data);
            $cleaningText = str_replace($url, 'images' . DIRECTORY_SEPARATOR . basename($url), $cleaningText);
        }
        $zip->addFromString($rootDir . DIRECTORY_SEPARATOR . 'CleaningText.html', $cleaningText);

        $zip->close();

        $attributes = array(
            'file_path' => $packageName,
            'check_sum' => md5_file($packagePath),
        );
        $package->attributes = $attributes;
        $package->save();

        $this->sendPackage($packagePath, $packageName);
    }

    protected function getMaxUpdatedAt()
    {
        $db = Yii::app()->db;
        $maxUpdatedAt = $db->createCommand()
            ->select('MAX(updated_at) as maxUpdatedAt')
            ->from('robot')
            ->limit(1)->queryScalar();

        if ($maxUpdatedAt === null) {
            throw new CHttpException(400, 'Data is null', Error::ERROR_EMPTY_RESPONSE);
        }
        return $maxUpdatedAt;
    }

    protected function getJson()
    {
        $model = $this->loadAll();

        $response = array();
        foreach ($model as $item) {
            if (($robot = $this->getJsonForRobotId($item)) !== null) {
                $response[] = $robot;
            }
        }

        $this->data = $response;
    }

    protected function getXml()
    {
        $maxUpdatedAt = $this->maxUpdatedAt;

        $xml = new Xml;

        $model = $this->loadAll();

        $robots = $xml->createElement('Robots');
        $robots->setAttribute('updatedAt', date_format(date_create($maxUpdatedAt), DATE_RFC822));

        foreach ($model as $item) {
            if (($robot = $this->getXmlForRobotId($item, $xml)) !== null) {
                $robots->appendChild($robot);
            }
        }
        $xml->appendChild($robots);
        return $xml;
    }

    /**
     * @throws CHttpException
     * @param $robotId
     * @param null $xml
     * @return null|Xml
     */
    protected function getXmlForRobotId($model, DOMDocument $xml)
    {
        assert($model !== null);

        $robot = $xml->createElement('Robot');
        $robot->setAttribute('updatedAt', date_format(date_create($model->updated_at), DATE_RFC822));

        $robot->appendChild($xml->createElement('ID', $model->id));
        $robot->appendChild($xml->createElement('Name', $model->name));
        $robot->appendChild($xml->createElement('ScreenName', $model->screen_name));
        $robot->appendChild($xml->createElement('Description', 'Description.html'));
        $robot->appendChild($xml->createElement('Preview', $model->getImage(200, true)));
        $robot->appendChild($xml->createElement('Image', $model->getImage(0, true)));
        $robot->appendChild($xml->createElement('MainFile', $model->getFilePath(true)));
        $robot->appendChild($xml->createElement('PanelColor', ''));
        $robot->appendChild($xml->createElement('LinkUrl', $model->link_url));

        $textureNode = $xml->createElement('TextureFile');

        if (!is_array(CJSON::decode($model->texture_file))) {
            $textureNode->nodeValue = $model->getTextureFileByName($model->texture_file, true, true);
        } else {
            $textures = CJSON::decode($model->texture_file);
            foreach ($textures as $texture) {
                $textureNodeChild = $xml->createElement('Texture', $model->getTextureFileByName($texture, true, true));
                $textureNode->appendChild($textureNodeChild);
            }
        }

        $robot->appendChild($textureNode);

        $robot->appendChild($xml->createElement('CleaningText', 'CleaningText.html'));

        $equipments = $xml->createElement('Equipments');
        $modelEquipment = !empty($model->robotEquipments) ? $model->robotEquipments : array();
        foreach ($modelEquipment as $index => $equipmentItem) {
            if (intval($equipmentItem->value) !== 0) {
                $equipment = $xml->createElement('Equipment');
                $equipment->setAttribute('updatedAt', date_format(date_create($equipmentItem->updated_at), DATE_RFC822));
                $equipment->appendChild($xml->createElement('Name', $equipmentItem->equipment->name));
                $equipment->appendChild($xml->createElement('ScreenName', $equipmentItem->equipment->screen_name));
                $equipment->appendChild($xml->createElement('Description', CHtml::encode($equipmentItem->equipment->description)));
                $equipment->appendChild($xml->createElement('Image', $equipmentItem->equipment->getImage(0, true)));

                $equipments->appendChild($equipment);
            }
        }
        $robot->appendChild($equipments);

        $features = $xml->createElement('Features');
        $modelFeatures = !empty($model->robotFeatures) ? $model->robotFeatures : array();
        foreach ($modelFeatures as $index => $featureItem) {
            $feature = $xml->createElement('Feature');
            $feature->setAttribute('updatedAt', date_format(date_create($featureItem->updated_at), DATE_RFC822));
            $feature->appendChild($xml->createElement('Name', $featureItem->feature->name));
            $feature->appendChild($xml->createElement('Type', $featureItem->feature->type));
            $feature->appendChild($xml->createElement('Value', $featureItem->value));

            $features->appendChild($feature);
        }
        $robot->appendChild($features);


        $photos = $xml->createElement('Photos');
        $modelPhotos = !empty($model->robotPhotos) ? $model->robotPhotos : array();
        foreach ($modelPhotos as $index => $photoItem) {
            $photo = $xml->createElement('Photo');
            $photo->setAttribute('updatedAt', date_format(date_create($photoItem->updated_at), DATE_RFC822));
            $photo->appendChild($xml->createElement('Preview50', 'Photo\\' . $photoItem->getImage(50, true)));
            $photo->appendChild($xml->createElement('Preview400', 'Photo\\' . $photoItem->getImage(400, true)));
            $photo->appendChild($xml->createElement('File', 'Photo\\' . $photoItem->getImage(0, true)));

            $photos->appendChild($photo);
        }
        $robot->appendChild($photos);

        $videos = $xml->createElement('Videos');
        $modelVideos = !empty($model->robotVideos) ? $model->robotVideos : array();
        foreach ($modelVideos as $index => $videoItem) {
            $video = $xml->createElement('Video');
            $video->setAttribute('updatedAt', date_format(date_create($videoItem->updated_at), DATE_RFC822));
            $video->appendChild($xml->createElement('FileName', $videoItem->video->getFileName(true)));
            $video->appendChild($xml->createElement('Preview_100', $videoItem->video->getPreviewImage(100, true)));
            $video->appendChild($xml->createElement('Preview_200', $videoItem->video->getPreviewImage(200, true)));

            $videos->appendChild($video);
        }
        $robot->appendChild($videos);

        $xml->appendChild($robot);

        return $robot;
    }

    protected function getJsonForRobotId($model)
    {
        assert($model !== null);

        $response = array(
            'id' => $model->id,
            'name' => $model->name,
            'screenName' => $model->screen_name,
            'description' => $model->description,
            'price' => Yii::app()->numberFormatter->formatCurrency($model->price, 'RUB'),
            'preview' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getImage(200),
            'image' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getImage(0),
            'mainFile' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getFilePath(),
            'mainFilePod' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getFilePathPod(),
            'linkUrl' => $model->link_url,
        );

        if (!is_array(CJSON::decode($model->texture_file))) {
            $texture = Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getTextureFileByName($model->texture_file, false, true);
            $response['texture'] = array($texture);
        } else {
            $buf = CJSON::decode($model->texture_file);
            foreach ($buf as $texture) {
                $textures[] = Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $model->getTextureFileByName($texture, false, true);
            }
            $response['texture'] = $textures;
        }

        $response['equipment'] = array();
        $modelEquipment = !empty($model->robotEquipments) ? $model->robotEquipments : array();
        foreach ($modelEquipment as $index => $equipmentItem) {
            if (intval($equipmentItem->value) !== 0) {
                $response['equipment'][] = array(
                    'updatedAt' => date_format(date_create($equipmentItem->updated_at), DATE_RFC822),
                    'name' => $equipmentItem->equipment->name,
                    'screenName' => $equipmentItem->equipment->screen_name,
                    'description' => CHtml::encode($equipmentItem->equipment->description),
                    'image' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $equipmentItem->equipment->getImage(0),
                );
            }
        }

        $response['feature'] = array();
        $modelFeatures = !empty($model->robotFeatures) ? $model->robotFeatures : array();
        foreach ($modelFeatures as $index => $featureItem) {
            $response['feature'][] = array(
                'updateAt' => date_format(date_create($featureItem->updated_at), DATE_RFC822),
                'name' => $featureItem->feature->name,
                'type' => $featureItem->feature->type,
                'value' => $featureItem->value,
            );
        }

        $response['photo'] = array();
        $modelPhotos = !empty($model->robotPhotos) ? $model->robotPhotos : array();
        foreach ($modelPhotos as $index => $photoItem) {
            $response['photo'][] = array(
                'updateAt' => date_format(date_create($featureItem->updated_at), DATE_RFC822),
                'preview50' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $photoItem->getImage(50),
                'preview400' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $photoItem->getImage(400),
                'file' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $photoItem->getImage(0),
            );
        }

        $response['video'] = array();
        $modelVideos = !empty($model->robotVideos) ? $model->robotVideos : array();
        foreach ($modelVideos as $index => $videoItem) {

            $response['video'][] = array(
                'updateAt' => date_format(date_create($featureItem->updated_at), DATE_RFC822),
                'fileName' => Yii::app()->getBaseUrl(true) . $videoItem->video->getFileName(false),
                'preview100' => Yii::app()->getBaseUrl(true) . $videoItem->video->getPreviewImage(100),
                'preview200' => Yii::app()->getBaseUrl(true) . $videoItem->video->getPreviewImage(200),
            );

        }

        return $response;
    }

    protected function loadModel($id)
    {
        $model = Robot::model()->active()->findByPk($id);

        if (!$model) {
            throw new CHttpException(400, 'Error loading model', Error::ERROR_BAD_REQUEST);
        }

        return $model;
    }


    protected function loadAll()
    {
        $model = Robot::model()->active()->findAll();
        return $model;
    }

    protected function loadImagesFromSource($source)
    {
        $images = array();
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $source, $media);
        $data = preg_replace('/(img|src)(" | \'|="|=\')(.*)/i', "$3", $media[0]);
        foreach ($data as $url) {
            $info = pathinfo($url);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png')
                )
                    array_push($images, $url);
            }
        }
        return $images;
    }
}