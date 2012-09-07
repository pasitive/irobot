<?php

/**
 * This is the model class for table "robot_photo".
 *
 * The followings are the available columns in table 'robot_photo':
 * @property integer $id
 * @property integer $robot_id
 * @property string $file_name
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Robot $robot
 */
class RobotPhoto extends CActiveRecord
{

    public $photos;

    public $imageSize = array(50, 400, 1024);

    public function init()
    {
        $this->attachEventHandler('onAfterSave', array('ActionLog', 'writeLog'));
        $this->attachEventHandler('onAfterDelete', array('ActionLog', 'writeLog'));
    }

    public function getImage($size = 0, $onlyFileName = false)
    {
        return $this->getResourcePath($this->file_name, $size, array('onlyFileName' => $onlyFileName));
    }

    /**
     * Returns the static model of the specified AR class.
     * @return RobotPhoto the static model class
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
        return 'robot_photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('robot_id', 'required'),
            array('robot_id', 'numerical', 'integerOnly' => true),
            array('file_name', 'length', 'max' => 255),
            array('created_at, updated_at, file_name', 'safe'),
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
            'file_name' => 'Файл',
            'thumb_name' => 'Превью',
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

    public function getAbsoluteResourcePath()
    {
        return Yii::getPathOfAlias('webroot') . $this->getRelativeResourcePath();
    }

    public function getRelativeResourcePath()
    {
        return Yii::app()->params['uploadDir'] . DIRECTORY_SEPARATOR
               . get_class($this);
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