<?php

/**
 * This is the model class for table "school.school_zone".
 *
 * The followings are the available columns in table 'school.school_zone':
 * @property integer $id
 * @property integer $school_id
 * @property integer $name
 * @property string $desc
 * @property integer $type
 * @property string $address
 * @property double $latitude
 * @property double $longitude
 * @property integer $provinces
 * @property integer $city
 * @property integer $county
 * @property integer $status
 * @property integer $create_user
 * @property integer $create_time
 */
class SchoolZone extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SchoolZone the static model class
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
		return 'school.school_zone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_id, name, type, address, create_time', 'required'),
			array('school_id, type, provinces, city, county, status, create_user, create_time', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('desc', 'length', 'max'=>500),
			array('address', 'length', 'max'=>200),
			array('name', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, school_id, name, desc, type, address, latitude, longitude, provinces, city, county, status, create_user, create_time', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'desc' => 'Desc',
			'type' => 'Type',
			'address' => 'Address',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'provinces' => 'Provinces',
			'city' => 'City',
			'county' => 'County',
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
		$criteria->compare('school_id',$this->school_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('provinces',$this->provinces);
		$criteria->compare('city',$this->city);
		$criteria->compare('county',$this->county);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	//
	public function getZoneList($school_id = 0) {
		if(!$school_id) $school_id = Yii::app()->session->get('school_id');
		
		$criteria=new CDbCriteria;
		$criteria->compare('school_id',$this->school_id);
		
		$list = array();
		$data = $this->findAll($criteria);
		foreach($data as $item) {
			$list[$item->id] = $item->name;
		}
		
		return $list;
	}
}