<?php

/**
 * This is the model class for table "video".
 *
 * The followings are the available columns in table 'video':
 * @property integer $id
 * @property string $file_name
 * @property string $preview_image
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Video extends CActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public $imageSizes = array(100, 200, 400);

    public function getFileName($onlyFileName = false)
    {
        return $this->getResourcePath($this->file_name, 0, array('onlyFileName' => $onlyFileName));
    }

    public function getPreviewImage($size = 0, $onlyFileName = false)
    {
        return $this->getResourcePath($this->preview_image, $size, array('onlyFileName' => $onlyFileName));
    }

    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => 'status=' . self::STATUS_ACTIVE,
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Video the static model class
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
        return 'video';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('file_name, preview_image', 'required'),
            array('file_name, preview_image', 'length', 'max' => 255),
            array('file_name', 'file', 'allowEmpty' => false, 'types' => array('mp4'), 'wrongType' => 'Вы можете закачать видео ТОЛЬКО в формате *.mp4', 'on' => 'insert'),
            array('preview_image', 'file', 'allowEmpty' => false, 'types' => array('jpeg', 'jpg', 'png', 'gif'), 'wrongType' => 'Вы можете закачать видео ТОЛЬКО в формате *.jp(e)g, *.png, *.gif', 'on' => 'insert'),
            array('status', 'numerical', 'integerOnly' => true),
            array('file_name, preview_image', 'length', 'max' => 255),
            array('created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, file_name, preview_image, status, created_at, updated_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Код',
            'file_name' => 'Файл',
            'preview_image' => 'Превью',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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

        // Если деактивировать видео, то не нужно его показывать у роботов.
        // Удаляем из кросс таблицы
        if($this->status == self::STATUS_DISABLED) {
            RobotVideo::model()->deleteAllByAttributes(array(
                'video_id' => $this->id
            ));
        }

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

            foreach ($this->imageSizes as $size) {
                Common::processImage($this, $image, $size, $hashString);
            }

            $this->updateByPk($this->id, array(
                'preview_image' => $imageMeta['fileName'],
            ));
            $this->setAttribute('preview_image', $imageMeta['fileName']);
        }

        parent::afterSave();
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
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('preview_image', $this->preview_image, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}