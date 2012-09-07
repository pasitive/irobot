<?php
/**
 * User: denisboldinov
 * Date: 10/18/11
 * Time: 2:07 PM
 */

class ResourcesBehavior extends CActiveRecordBehavior
{

    /**
     * @var stirng
     */
    public $resourcePath;

    /**
     * @return string
     */
    public function getRelativeResourcePath()
    {
        $owner = $this->getOwner();
        return $this->resourcePath . DIRECTORY_SEPARATOR
               . get_class($owner);
    }

    /**
     * @return string
     */
    public function getAbsoluteResourcePath()
    {
        return Yii::getPathOfAlias('webroot') . $this->getRelativeResourcePath();
    }

    /**
     * @param int $length
     * @return string
     */
    public function generatePathHash($length = 6)
    {
        assert($length < 32);
        $hashString = substr(md5(microtime(true) * 1000), 0, $length);
        return $hashString;
    }

    /**
     * @param $hash
     * @return string
     */
    public function generatePath($hash)
    {
        $hash = str_replace('/', '', $hash);
        $hash = join(DIRECTORY_SEPARATOR, str_split($hash, 2));

        $uploadPath = $this->getAbsoluteResourcePath() . DIRECTORY_SEPARATOR . $hash;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        return $uploadPath;
    }

    /**
     * @param $fileName
     * @param bool $asString
     * @return string
     */
    public function getPathHash($fileName, $asString = false)
    {
        $hashString = substr($fileName, 0, 6);
        return ($asString === false)
                ? join(DIRECTORY_SEPARATOR, str_split($hashString, 2))
                : $hashString;
    }

    /**
     * @param $name
     * @param int $size
     * @param array $options
     * @return mixed|string
     */
    public function getResourcePath($name, $size = 0, $options = array())
    {
        $basePath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.resourcesBehavior.assets'), false, -1, YII_DEBUG);
        $noResourceImage = $basePath . DIRECTORY_SEPARATOR . 'no_resource.png';


        $resourceName = $name;
        if ($size !== 0) {
            $resourceName = str_replace('.', '_' . $size . '.', $name);
        }

        $pathHash = $this->getPathHash($resourceName);

        $resourcePath = $this->getRelativeResourcePath() . DIRECTORY_SEPARATOR .
                        $pathHash . DIRECTORY_SEPARATOR .
                        $resourceName;

        $absoluteResourcePath = $this->getAbsoluteResourcePath() . DIRECTORY_SEPARATOR .
                                $pathHash . DIRECTORY_SEPARATOR .
                                $resourceName;

        if (!file_exists($absoluteResourcePath)) {
            return $noResourceImage;
        }

        // Options

        $result = $resourcePath;

        if(isset($options['onlyFileName']) && $options['onlyFileName'] === true) {
            $result = $resourceName;
        }

        if(isset($options['stripHashName']) && $options['stripHashName'] === true) {
            $result = substr($resourceName, 6);
        }

        return $result;
    }
}
