<?php

/**
 * This is the model class for table "school.school_type".
 *
 * The followings are the available columns in table 'school.school_type':
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 */
class SchoolType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SchoolType the static model class
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
		return 'school.school_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parent_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'parent_id' => 'Parent',
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
		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getAList() {
		$data = $this->findAll('parent_id=0');
		$list = array('-1'=>'请选择');
		foreach($data as $item) {
			$list[$item->id] = $item->name;
		}
		
		return $list;
	}
	
	public function getBList() {
		$data = $this->findAll();
		$list = array('-1'=>'请选择');
		foreach($data as $item) {
			$list[$item->id] = $item->name;
		}
		
		return $list;
	}
}