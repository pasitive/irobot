<?php
/**
 * Description of Common
 *
 * @author Болдинов Денис Андреевич <denis.a.boldinov@gmail.com>
 */
class Common
{
    public static function generateUniqueCode($length, $makeNewSeed = true)
    {
        if ($makeNewSeed) {
            $seed = microtime(true) * 1000;
            mt_srand($seed);
        }
        $chars = "abcdefghijklmnopqrstuvwxyz1234567890";
        $code = null;
        $size = strlen($chars) - 1;
        while ($length--) {
            $code .= $chars[mt_rand(0, $size)];
        }
        return $code;
    }

    public static function processFile(CModel $model, CUploadedFile $file, $hashString, $saveFileName = false)
    {

        assert($model->asa('ResourcesBehavior') !== null);

        $uploadPath = $model->generatePath($hashString);
        if($saveFileName) {
            $fileName = $file->getName();
            $hashedFileName = $hashString . $fileName;
            $filePath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
            $file->saveAs($filePath);

            return $hashedFileName;
        } else {
            $fileName = md5(microtime(true) . $file->getTempName()) . '.' . $file->getExtensionName();
            $fileName = $hashString . $fileName;
            $filePath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
            $file->saveAs($filePath);

            return $fileName;
        }
    }

    public static function processImage(CModel $model, CUploadedFile $photo, $wh = false, $hashString)
    {
        /**
         * Файлы сохраняются в директорию, имя которой совпадает с именем класса модели.
         * Формат файла - [хеш директории][имя файла[_разрешение]].[расширение]
         */
        assert($model->asa('ResourcesBehavior') !== null);

        $image = Yii::app()->image->load($photo->getTempName());

        $uploadPath = $model->generatePath($hashString);

        if ($wh) {
            $image->resize($wh, $wh);
            $fileName = md5(Yii::app()->user->id . $photo->getName()) . '_' . $wh . '.' . $image->ext;
        } else {
            $fileName = md5(Yii::app()->user->id . $photo->getName()) . '.' . $image->ext;
        }

        $fileName = $hashString . $fileName;

        $file = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

        $image->save($file);

        return array(
            'filePath' => $file,
            'fileName' => $fileName,
        );
    }
}

?>
