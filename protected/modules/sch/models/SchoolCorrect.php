<?php

/**
 * This is the model class for table "school.school_correct".
 *
 * The followings are the available columns in table 'school.school_correct':
 * @property integer $id
 * @property integer $record_id
 * @property integer $type
 * @property string $content
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $status
 * @property string $reason
 */
class SchoolCorrect extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SchoolCorrect the static model class
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
		return 'school.school_correct';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('record_id, type, content, create_time', 'required'),
			array('record_id, type, create_user, create_time, status', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, record_id, type, content, create_user, create_time, status, reason', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'record_id' => 'Record',
			'type' => 'Type',
			'content' => 'Content',
			'create_user' => 'Create User',
			'create_time' => 'Create Time',
			'status' => 'Status',
			'reason' => 'Reason',
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
		$criteria->compare('record_id',$this->record_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('reason',$this->reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}