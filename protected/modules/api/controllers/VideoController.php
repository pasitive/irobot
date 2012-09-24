<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 10/24/11
 * Time: 6:39 PM
 * To change this template use File | Settings | File Templates.
 */

class VideoController extends Controller
{

    public function actionList($robotId = 0, $format = 'xml')
    {
        switch ($format) {
            case 'xml':
                $this->listXml($robotId);
                break;

            case 'json':
                $this->listJson($robotId);
                break;

            default:
                $this->listXml($robotId);
        }
    }

    protected function listJson($robotId = 0)
    {
        $model = $this->loadModel($robotId);

        $response = array();
        foreach ($model as $item) {

            $response[] = array(
                'id' => $item->id,
                'robotId' => $item->robot_id,
                'robotName' => $item->robot->name,
                'file_name' => Yii::app()->getBaseUrl(true) . $item->video->getFileName(),
                'preview_100' => Yii::app()->getBaseUrl(true) . $item->video->getPreviewImage(100),
                'preview_200' => Yii::app()->getBaseUrl(true) . $item->video->getPreviewImage(200),
            );

        }

        $this->data = $response;
    }

    protected function loadModel($robotId)
    {
        if (!empty($robotId)) {
            $model = Robot::model()->findByPk($robotId);
            if (!$model) {
                throw new CHttpException(200, 'Robot not found', Error::ERROR_EMPTY_RESPONSE);
            }
            $robotVideo = $model->robotVideos;
        } else {
            $robotVideo = RobotVideo::model()->findAll();
        }

        if (!$robotVideo) {
            throw new CHttpException(200, 'Robot has ho video', Error::ERROR_EMPTY_RESPONSE);
        }

        return $robotVideo;
    }

    protected function listXml($robotId = 0)
    {
        $this->isJsonResponse = false;

        $xml = new Xml();
        $videos = $xml->createElement('Videos');

        $model = $this->loadModel($robotId);

        foreach ($model as $item) {
            if (is_object($item->video) && get_class($item->video) == 'Video') {
                $video = $xml->createElement('Video');
                $video->setAttribute('updatedAt', date_format(date_create($item->updated_at), DATE_RFC822));
                $video->appendChild($xml->createElement('ID', $item->id));
                $video->appendChild($xml->createElement('RobotId', $item->robot_id));
                $video->appendChild($xml->createElement('RobotName', $item->robot->name));
                $video->appendChild($xml->createElement('FileName', $item->video->file_name));
                $video->appendChild($xml->createElement('Preview_100', $item->video->getPreviewImage(100, true)));
                $video->appendChild($xml->createElement('Preview_200', $item->video->getPreviewImage(200, true)));
                $videos->appendChild($video);
            }
        }

        $xml->appendChild($videos);

        Header('Content-type: application/xml');
        echo $xml->saveXML();
    }

    public function actionPackage($videoId)
    {
        $this->isJsonResponse = false;
        $rootDir = 'Video';

        $model = RobotVideo::model()->findByPk($videoId);

        if (!$model) {
            throw new CHttpException(400, 'RobotVideo not found', Error::ERROR_EMPTY_RESPONSE);
        }

        $model = $model->video;

        $package = new UpdatePackage();
        $hashString = $package->generatePathHash();
        $resourcePath = $package->generatePath($hashString);

        $packageName = $hashString . md5(microtime(true)) . '.zip';
        $packagePath = $resourcePath . DIRECTORY_SEPARATOR . $packageName;

        $zip = new ZipArchive();
        $res = $zip->open($packagePath, ZipArchive::CREATE);

        if ($res !== true) {
            throw new CHttpException(400, 'Error creating zip archive', Error::ERROR_INTERNAL);
        }

        $zip->addEmptyDir($rootDir);

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getFileName();
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getFileName(true);
        $zip->addFile($file, $local);

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getPreviewImage(100);
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getPreviewImage(100, true);
        $zip->addFile($file, $local);

        $file = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $model->getPreviewImage(200);
        $local = $rootDir . DIRECTORY_SEPARATOR . $model->getPreviewImage(200, true);
        $zip->addFile($file, $local);

        $zip->close();

        $attributes = array(
            'file_path' => $packageName,
            'check_sum' => md5_file($packagePath),
        );
        $package->attributes = $attributes;
        $package->save();

        $this->sendPackage($packagePath, $packageName);
    }
}
