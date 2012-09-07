<?php

/**
 * This is the model class for table "robot_video".
 *
 * The followings are the available columns in table 'robot_video':
 * @property integer $id
 * @property integer $robot_id
 * @property string $file_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $preview_image
 *
 * The followings are the available model relations:
 * @property Robot $robot
 */
class RobotVideo extends CActiveRecord
{

    public $newFileName;

    public $newPreviewImage;

    public $imageSize = array(100, 200);

    public function init()
    {
        $this->attachEventHandler('onAfterSave', array('ActionLog', 'writeLog'));
        $this->attachEventHandler('onAfterDelete', array('ActionLog', 'writeLog'));
    }

    public function getFileName($onlyFileName = false)
    {
        return $this->getResourcePath($this->file_name, 0, array('onlyFileName' => $onlyFileName));
    }

    public function getPreviewImage($size = 0, $onlyFileName = false)
    {
        return $this->getResourcePath($this->preview_image, $size, array('onlyFileName' => $onlyFileName));
    }

    /**
     * Returns the static model of the specified AR class.
     * @return RobotVideo the static model class
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
        return 'robot_video';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        //@todo указать максимальный размер файла видео
        return array(
            array('robot_id', 'required'),
            array('robot_id', 'numerical', 'integerOnly' => true),
            array('file_name, preview_image', 'length', 'max' => 255),
            array('file_name', 'file', 'allowEmpty' => false, 'types' => array('wmv', 'avi'), 'wrongType' => 'Вы можете закачать видео ТОЛЬКО в формате *.wmv, *.avi'),
            array('preview_image', 'file', 'allowEmpty' => false, 'types' => array('jpeg', 'jpg', 'png', 'gif'), 'wrongType' => 'Вы можете закачать видео ТОЛЬКО в формате *.jp(e)g, *.png, *.gif'),
            array('created_at, updated_at, newFileName, newPreviewImage', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, robot_id, file_name, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'robot' => array(self::BELONGS_TO, 'Robot', 'robot_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Код',
            'robot_id' => 'Модель робота',
            'file_name' => 'Файл видео',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'preview_image' => 'Изображение предпросмотр',
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

    public function getAbsoluteResourcePath()
    {
        return Yii::getPathOfAlias('webroot') . $this->getRelativeResourcePath();
    }

    public function getRelativeResourcePath()
    {
        return Yii::app()->params['uploadDir'] . DIRECTORY_SEPARATOR
               . get_class($this);
    }

    protected function afterSave()
    {

        $file = CUploadedFile::getInstance($this, 'file_name');

        $hashString = $this->generatePathHash();

        if ($file !== null) {
            $fileName = Common::processFile($this, $file, $hashString);
            $this->updateByPk($this->id, array(
                                              'file_name' => $fileName,
                                         ));
            $this->setAttribute('file_name', $fileName);
        }

        $image = CUploadedFile::getInstance($this, 'preview_image');

        if ($image !== null) {
            $imageMeta = Common::processImage($this, $image, false, $hashString);

            foreach ($this->imageSize as $imageSize) {
                Common::processImage($this, $image, $imageSize, $hashString);
            }

            $this->updateByPk($this->id, array(
                                              'preview_image' => $imageMeta['fileName'],
                                         ));
            $this->setAttribute('preview_image', $imageMeta['fileName']);
        }

        parent::afterSave();
    }

    /**
     * @return bool
     */
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
        $criteria->compare('robot_id', $this->robot_id);
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
                                                   'criteria' => $criteria,
                                                   'pagination' => array(
                                                       'pageSize' => 999,
                                                   ),
                                              ));
    }
}