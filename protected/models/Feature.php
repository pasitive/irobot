<?php

/**
 * This is the model class for table "feature".
 *
 * The followings are the available columns in table 'feature':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property RobotFeature[] $robotFeatures
 */
class Feature extends CActiveRecord
{
    public function init()
    {
        $this->attachEventHandler('onAfterSave', array('ActionLog', 'writeLog'));
        $this->attachEventHandler('onAfterDelete', array('ActionLog', 'writeLog'));
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Feature the static model class
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
        return 'feature';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type', 'required'),
            array('name', 'length', 'max' => 255),
            array('type', 'length', 'max' => 7),
            array('created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, type, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'robotFeatures' => array(self::HAS_MANY, 'RobotFeature', 'feature_id'),
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
            'type' => 'Тип',
            'type_string' => 'Строковый',
            'type_boolean' => 'Булевый',
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
            )
        );
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
        $criteria->compare('type', $this->type, true);
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