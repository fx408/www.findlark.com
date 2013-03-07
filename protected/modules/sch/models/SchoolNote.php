<?php

/**
 * This is the model class for table "school.school_note".
 *
 * The followings are the available columns in table 'school.school_note':
 * @property integer $id
 * @property integer $school_id
 * @property string $title
 * @property string $content
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $status
 */
class SchoolNote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SchoolNote the static model class
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
		return 'school.school_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_id, title, content, create_time', 'required'),
			array('school_id, create_user, create_time, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>20),
			array('content', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, school_id, title, content, create_user, create_time, status', 'safe', 'on'=>'search'),
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
			'school_id' => 'School',
			'title' => 'Title',
			'content' => 'Content',
			'create_user' => 'Create User',
			'create_time' => 'Create Time',
			'status' => 'Status',
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
		$criteria->compare('school_id',$this->school_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}