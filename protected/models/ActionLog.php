<?php

/**
 * This is the model class for table "action_log".
 *
 * The followings are the available columns in table 'action_log':
 * @property integer $id
 * @property string $type
 * @property string $message
 * @property string $model_class
 * @property integer $model_id
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class ActionLog extends CActiveRecord
{

    const TYPE_ADDED = 'a';
    const TYPE_MODIFIED = 'm';
    const TYPE_DELETED = 'd';

    private $_typeLabels = array(
        self::TYPE_ADDED => 'Добавлено',
        self::TYPE_MODIFIED => 'Обновлено',
        self::TYPE_DELETED => 'Удалено',
    );

    public function defaultScope()
    {
        return array(
            'order' => 'created_at DESC',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return ActionLog the static model class
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
        return 'action_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, message, model_class, model_id', 'required'),
            array('model_id', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 1),
            array('message, model_class', 'length', 'max' => 255),
            array('data, created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, message, model_class, model_id, data, created_at, updated_at', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Код',
            'type' => 'Тип',
            'typeAsString' => 'Тип',
            'message' => 'Сообщение',
            'model_class' => 'Класс модели',
            'model_id' => 'Код модели',
            'data' => 'Данные',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('model_class', $this->model_class, true);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
                                                   'criteria' => $criteria,
                                              ));
    }

    public function getTypeAsString()
    {
        if (!isset($this->_typeLabels[$this->type])) {
            return '!undefined!';
        }
        return $this->_typeLabels[$this->type];
    }

    public static function writeLog(CEvent $event)
    {
        $sender = $event->sender;

        if ($sender) {
            // Лог активности
            $_class = get_class($sender);
            $_params = array('{class}' => $_class);
            if ($sender->getScenario() == 'insert') {
                $logMessage = Yii::t('app', 'Added object {class}', $_params);
                $logType = self::TYPE_ADDED;
            } elseif ($sender->getScenario() == 'delete') {
                $logMessage = Yii::t('app', 'Deleted object {class}', $_params);
                $logType = self::TYPE_DELETED;
            } else {
                $logMessage = Yii::t('app', 'Updated object {class}', $_params);
                $logType = self::TYPE_MODIFIED;
            }

            $attributes = array(
                'type' => $logType,
                'model_class' => $_class,
                'model_id' => $sender->id,
                'message' => $logMessage,
            );
            
            $actionLog = new ActionLog();
            $actionLog->attributes = $attributes;
            $actionLog->save();
        }
    }
}