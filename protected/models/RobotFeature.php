<?php

/**
 * This is the model class for table "robot_feature".
 *
 * The followings are the available columns in table 'robot_feature':
 * @property integer $id
 * @property integer $robot_id
 * @property integer $feature_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Robot $robot
 * @property Feature $feature
 */
class RobotFeature extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return RobotFeature the static model class
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
        return 'robot_feature';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('robot_id, feature_id, value', 'required'),
            array('robot_id, feature_id', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 255),
            array('created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, robot_id, feature_id, value, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'feature' => array(self::BELONGS_TO, 'Feature', 'feature_id'),
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
            'feature_id' => 'Характеристика',
            'value' => 'Значение',
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
        $criteria->compare('robot_id', $this->robot_id);
        $criteria->compare('feature_id', $this->feature_id);
        $criteria->compare('value', $this->value, true);
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