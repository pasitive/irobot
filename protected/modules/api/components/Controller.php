<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 9/29/11
 * Time: 5:20 PM
 * To change this template use File | Settings | File Templates.
 */

class Controller extends CController
{

    const FORMAT_XML = 'xml';
    const FORMAT_JSON = 'json';
    /**
     * Response HTTP-status
     * @var
     */
    public $code = 200;

    /**
     * Response error code. Default is 0 (No errors);
     * @var
     */
    public $errorCode = 0;

    /**
     * Data
     * @var
     */
    public $message = null;

    /**
     * @var
     */
    public $data;

    /**
     * @var bool
     */
    protected $isJsonResponse = true;

    /**
     * @return array action filters
     */
    public function filters()
    {
        if (YII_DEBUG) {
            return array();
        } else {
            return array(
                array('SignatureFilter'),
            );
        }
    }


    protected function afterAction($action)
    {
        if ($this->isJsonResponse) {
            $response = array(
                'code' => $this->code,
                'errorCode' => $this->errorCode,
                'message' => $this->message,
                'data' => $this->data,
            );

            Header('Content-type:application/json');
            echo CJSON::encode($response);
        }

        parent::afterAction($action);
    }

    protected function sendPackage($filename, $screenname = null)
    {
        // есл файла нет
        if (!file_exists($filename)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        // получим размер файла
        $fsize = filesize($filename);
        // дата модификации файла для кеширования
        $ftime = date("D, d M Y H:i:s T", filemtime($filename));
        // смещение от начала файла
        $range = 0;

        // пробуем открыть
        $handle = @fopen($filename, "rb");

        // если не удалось
        if (!$handle) {
            header("HTTP/1.0 403 Forbidden");
            exit;
        }

        // Если запрашивающий агент поддерживает докачку
        if (isset($_SERVER["HTTP_RANGE"])) {
            $range = $_SERVER["HTTP_RANGE"];
            $range = str_replace("bytes=", "", $range);
            $range = str_replace("-", "", $range);
            // смещаемся по файлу на нужное смещение
            if ($range) {
                fseek($handle, $range);
            }
        }

        // если есть смещение
        if ($range) {
            header("HTTP/1.1 206 Partial Content");
        } else {
            header("HTTP/1.1 200 OK");
        }

        header("Content-Disposition: attachment; filename=\"{$screenname}\"");
        header("Last-Modified: {$ftime}");
        header("Content-Length: " . ($fsize - $range));
        header("Accept-Ranges: bytes");
        header("Content-Range: bytes {$range}-" . ($fsize - 1) . "/" . $fsize);

        // подправляем под IE что б не умничал
        if (isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
            Header('Content-Type: application/force-download');
        else
            Header('Content-Type: application/octet-stream');

        while (!feof($handle)) {
            $buf = fread($handle, 512);
            print($buf);
        }

        fclose($handle);
    }

}
