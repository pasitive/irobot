<?php

/**
 * This is the model class for table "equipment".
 *
 * The followings are the available columns in table 'equipment':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 * @property string $screen_name
 *
 * The followings are the available model relations:
 * @property RobotEquipment[] $robotEquipments
 */
class Equipment extends CActiveRecord
{

    public $newImage;

    public $imageSize = array(50, 150);

    public $maxUpdatedAt = null;

    public function init()
    {
        $this->attachEventHandler('onAfterSave', array('ActionLog', 'writeLog'));
        $this->attachEventHandler('onAfterDelete', array('ActionLog', 'writeLog'));
    }

    public function getImage($size = 0, $onlyFileName = false)
    {
        return $this->getResourcePath($this->image, $size, array('onlyFileName' => $onlyFileName));
    }


    /**
     * Returns the static model of the specified AR class.
     * @return Equipment the static model class
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
        return 'equipment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description', 'required'),
            array('name, image, screen_name', 'length', 'max' => 255),
            array('created_at, updated_at', 'safe'),
            array('image', 'file', 'allowEmpty' => false, 'on' => 'insert'),
            array('newImage', 'file', 'allowEmpty' => true, 'on' => 'update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, image, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'robotEquipments' => array(self::HAS_MANY, 'RobotEquipment', 'equipment_id'),
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
            'image' => 'Изображение',
            'newImage' => 'Новое изображение',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'screen_name' => 'Экранное название',
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
        $attribute = $this->isNewRecord ? 'image' : 'newImage';

        $image = CUploadedFile::getInstance($this, $attribute);

        if ($image !== null) {

            $hash = $this->generatePathHash();

            $imageMeta = Common::processImage($this, $image, 0, $hash);
            foreach ($this->imageSize as $imageSize) {
                Common::processImage($this, $image, $imageSize, $hash);
            }

            $this->updateByPk($this->id, array(
                                              'image' => $imageMeta['fileName']
                                         ));
            $this->setAttribute('image', $imageMeta['fileName']);
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
        $criteria->compare('image', $this->image, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('screen_name', $this->screen_name, true);

        return new CActiveDataProvider($this, array(
                                                   'criteria' => $criteria,
                                                   'pagination' => array(
                                                       'pageSize' => 999,
                                                   ),

                                              ));
    }
}