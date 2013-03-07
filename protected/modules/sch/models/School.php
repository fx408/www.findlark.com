<?php

/**
 * This is the model class for table "school.school".
 *
 * The followings are the available columns in table 'school.school':
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property integer $type
 * @property integer $status
 * @property integer $create_user
 * @property integer $create_time
 */
class School extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return School the static model class
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
		return 'school.school';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, create_time', 'required', 'message'=>'{attribute}'),
			array('type, status, create_user, create_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30, 'tooLong'=>'学校名称不超过30个字符!'),
			array('desc', 'length', 'max'=>500, 'tooLong'=>'学校描述不超过500个字符!'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, desc, type, status, create_user, create_time', 'safe', 'on'=>'search'),
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
			'name' => '请填写学校名称!',
			'desc' => 'Desc',
			'type' => '请选择学校类型!',
			'status' => 'Status',
			'create_user' => 'Create User',
			'create_time' => 'Create Time',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}