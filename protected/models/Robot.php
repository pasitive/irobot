<?php

/**
 * This is the model class for table "robot".
 *
 * The followings are the available columns in table 'robot':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $file_path
 * @property string $created_at
 * @property string $updated_at
 * @property string $image
 * @property string $link_url
 * @property string $screen_name
 * @property string $texture_file
 * @property string $texture_name
 * @property string $cleaning_text
 * @property string $file_path_pod
 * @property integer $sort
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property RobotEquipment[] $robotEquipments
 * @property RobotFeature[] $robotFeatures
 */
class Robot extends CActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public $newFilePath;
    public $newImage;
    public $newTextureFile;
    public $newFilePathPod;

    public $imageSize = array(100, 200, 1024);

    public function init()
    {
        $this->attachEventHandler('onAfterSave', array('ActionLog', 'writeLog'));
        $this->attachEventHandler('onAfterDelete', array('ActionLog', 'writeLog'));
    }

    public function getImage($size = 0, $onlyFileName = false)
    {
        return $this->getResourcePath($this->image, $size, array('onlyFileName' => $onlyFileName));
    }

    public function getFilePath($onlyFileName = false)
    {
        return $this->getResourcePath($this->file_path, 0, array('onlyFileName' => $onlyFileName));
    }

    public function getFilePathPod($onlyFileName = false)
    {
        return $this->getResourcePath($this->file_path_pod, 0, array('onlyFileName' => $onlyFileName));
    }

    public function getTextureFileByName($name, $onlyFileName = false, $stripHashName = false)
    {
        return $this->getResourcePath($name, 0, array(
            'onlyFileName' => $onlyFileName,
            'stripHashName' => $stripHashName,
        ));
    }


    public function defaultScope()
    {
        return array(
            'order' => 'sort DESC',
        );
    }

    public function active()
    {
        $criteria = $this->getDbCriteria();
        $criteria->addColumnCondition(array(
            'status' => Robot::STATUS_ACTIVE,
        ));
        return $this;
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Robot the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'robot';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description, price, cleaning_text', 'required'),
            array('name, file_path, screen_name, link_url', 'length', 'max' => 255),
            array('price', 'length', 'max' => 10),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('created_at, updated_at, newFilePath, newImage, newTextureFile, texture_file, newFilePathPod', 'safe'),
            array('link_url', 'url'),
            array('file_path', 'file', 'allowEmpty' => false, 'types' => '3ds', 'on' => 'insert'),
            array('file_path_pod', 'file', 'allowEmpty' => false, 'types' => 'pod', 'on' => 'insert'),
            array('image', 'file', 'allowEmpty' => false, 'types' => 'jpg,jpeg,gif,png', 'on' => 'insert'),
//            array('texture_file', 'file', 'allowEmpty' => false, 'types' => 'jpg,jpeg,gif,png', 'on' => 'insert'),

            array('id, name, description, price, file_path, created_at, updated_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'robotEquipments' => array(self::HAS_MANY, 'RobotEquipment', 'robot_id'),
            'robotFeatures' => array(self::HAS_MANY, 'RobotFeature', 'robot_id'),
            'robotPhotos' => array(self::HAS_MANY, 'RobotPhoto', 'robot_id'),
            'robotVideos' => array(self::HAS_MANY, 'RobotVideo', 'robot_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Код',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'file_path' => 'Файл 3DS модели',
            'file_path_pod' => 'Файл POD модели',
            'newFilePath' => 'Новый файл 3DS модели',
            'newFilePathPod' => 'Новый файл POD модели',
            'newImage' => 'Новое изображение',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'image' => 'Изображение',
            'link_url' => 'Ссылка',
            'screen_name' => 'Экранное название',
            'texture_file' => 'Файлы текстур модели',
            'newTextureFile' => 'Новые файлы текстур модели',
            'texture_name' => 'Оригинальное имя файла текстуры',
            'cleaning_text' => 'Технология уборки',
            'sort' => 'Сортировка',
            'status' => 'Статус',
        );
    }

    /**
     * @return array behaviors.
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true
            ),
            'ResourcesBehavior' => array(
                'class' => 'ext.resourcesBehavior.ResourcesBehavior',
                'resourcePath' => Yii::app()->params['uploadDir'],
            ),
        );
    }

    protected function afterSave()
    {
        $attribute = $this->isNewRecord ? 'file_path' : 'newFilePath';
        $file = CUploadedFile::getInstance($this, $attribute);

        $hashString = $this->generatePathHash();

        if ($file !== null) {
            $fileName = Common::processFile($this, $file, $hashString);
            $this->updateByPk($this->id, array(
                'file_path' => $fileName,
            ));
            $this->setAttribute('file_path', $fileName);
        }


        $attribute = $this->isNewRecord ? 'file_path_pod' : 'newFilePathPod';
        $file = CUploadedFile::getInstance($this, $attribute);

        if ($file !== null) {
            $fileName = Common::processFile($this, $file, $hashString);
            $this->updateByPk($this->id, array(
                'file_path_pod' => $fileName,
            ));
            $this->setAttribute('file_path_pod', $fileName);
        }

        $attribute = $this->isNewRecord ? 'texture_file' : 'newTextureFile';
        $files = CUploadedFile::getInstances($this, $attribute);

        if (!empty($files)) {
            $texture = array();
            foreach ($files as $file) {
                if ($file !== null) {
                    $fileName = Common::processFile($this, $file, $hashString, true);
                    $texture[] = $fileName;
                }
            }
            $this->updateByPk($this->id, array(
                'texture_file' => CJSON::encode($texture),
            ));
            $this->setAttributes(array(
                'texture_file' => CJSON::encode($texture),
            ));

        }

        $attribute = $this->isNewRecord ? 'image' : 'newImage';
        $image = CUploadedFile::getInstance($this, $attribute);
        if ($image !== null) {
            $imageMeta = Common::processImage($this, $image, 0, $hashString);
            foreach ($this->imageSize as $imageSize) {
                Common::processImage($this, $image, $imageSize, $hashString);
            }
            $this->updateByPk($this->id, array(
                'image' => $imageMeta['fileName'],
            ));
            $this->setAttributes(array(
                'image' => $imageMeta['fileName'],
            ));
        }

        parent::afterSave();
    }

    protected function beforeDelete()
    {
        $this->setScenario('delete');
        return parent::beforeDelete();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('file_path', $this->file_path, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('link_url', $this->link_url, true);
        $criteria->compare('screen_name', $this->screen_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
    }
}