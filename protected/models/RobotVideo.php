<?php

/**
 * This is the model class for table "robot_video".
 *
 * The followings are the available columns in table 'robot_video':
 * @property integer $id
 * @property integer $robot_id
 * @property integer $video_id
 * @property string $created_at
 * @property string $updated_at
 */
class RobotVideo extends CActiveRecord
{

    public $status;

	/**
	 * Returns the static model of the specified AR class.
	 * @return RobotVideo the static model class
	 */
	public static function model($className=__CLASS__)
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
		return array(
			array('robot_id, video_id', 'numerical', 'integerOnly'=>true),
			array('created_at, updated_at, status', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, robot_id, video_id, created_at, updated_at', 'safe', 'on'=>'search'),
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
            'video' => array(self::BELONGS_TO, 'Video', 'video_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'robot_id' => 'Robot',
			'video_id' => 'Video',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('robot_id',$this->robot_id);
		$criteria->compare('video_id',$this->video_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}