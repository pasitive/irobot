<?php

class EquipmentController extends Controller
{
    public function actionCheck()
    {
        $maxUpdatedAt = $this->maxUpdatedAt;
        $response = array(
            'updatedAt' => date_format(date_create($maxUpdatedAt), DATE_RFC822),
        );
        $this->data = $response;
    }

    public function actionList()
    {
        $maxUpdatedAt = $this->maxUpdatedAt;
        $model = Equipment::model()->findAll();

        if (!$model) {
            throw new CHttpException(200, 'No data to pack', Error::ERROR_EMPTY_RESPONSE);
        }
        $response = array();
        foreach ($model as $index => $item) {
            $response[] = array(
                'id' => $item->id,
                'name' => $item->name,
                'screenName' => $item->screen_name,
                'description' => $item->description,
                'image' => Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $item->getImage(),
            );
        }
        $this->data = $response;
    }

    public function actionPackage()
    {
        $this->isJsonResponse = false;

        $rootDir = 'Equipment';
        $maxUpdatedAt = $this->maxUpdatedAt;

        $model = Equipment::model()->findAll();

        if (!$model) {
            throw new CHttpException(200, 'No data to pack', Error::ERROR_EMPTY_RESPONSE);
        }

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
            throw new CHttpException(400, 'Error creating zip archive');
        }

        $equipments = $xml->createElement('Equipments');
        $equipments->setAttribute('updatedAt', date_format(date_create($maxUpdatedAt), DATE_RFC822));

        foreach ($model as $index => $item) {

            $equipment = $xml->createElement('Equipment');
            $equipment->appendChild($xml->createElement('ID', $item->id));
            $equipment->appendChild($xml->createElement('Name', $item->name));
            $equipment->appendChild($xml->createElement('ScreenName', $item->screen_name));
            $equipment->appendChild($xml->createElement('Description', $item->description));
            $equipment->appendChild($xml->createElement('Image', $item->image));
            $equipments->appendChild($equipment);

            $image = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $item->getImage();
            $zip->addFile($image, $rootDir . DIRECTORY_SEPARATOR . $item->image);

        }

        $xml->appendChild($equipments);
        $zip->addFromString($rootDir . DIRECTORY_SEPARATOR . 'Equipment.xml', $xml->saveXML());
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
            ->from('equipment')
            ->limit(1)->queryScalar();
        if ($maxUpdatedAt === null) {
            throw new CHttpException(200, 'Data is null', Error::ERROR_INTERNAL);
        }
        return $maxUpdatedAt;
    }
}