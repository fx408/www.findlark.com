<?php

/**
 * This is the model class for table "school.school_pic".
 *
 * The followings are the available columns in table 'school.school_pic':
 * @property integer $id
 * @property integer $school_id
 * @property integer $zone_id
 * @property string $title
 * @property string $name
 * @property string $thumb
 * @property string $path
 */
class SchoolPic extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SchoolPic the static model class
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
		return 'school.school_pic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_id, zone_id, name, path', 'required', 'message'=>'{attribute}'),
			array('school_id, zone_id', 'numerical', 'integerOnly'=>true),
			array('title, name', 'length', 'max'=>20, 'tooLong'=>'图片标题不能超过20个字符!'),
			array('thumb', 'length', 'max'=>30),
			array('path', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, school_id, zone_id, title, name, thumb, path', 'safe', 'on'=>'search'),
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
			'zone_id' => '请填选择校区!',
			'title' => 'Title',
			'name' => 'Name',
			'thumb' => 'Thumb',
			'path' => 'Path',
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
		$criteria->compare('zone_id',$this->zone_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPicList() {
		$criteria=new CDbCriteria;
		
		$criteria->limit = 100;
		$criteria->order = '`id` ASC';
		
		$data = $this->findAll($criteria);
		$list = array();
		
		foreach($data as $item) {
			$list[$item->id] = $item->attributes;
		}
		
		return $list;
	}
}