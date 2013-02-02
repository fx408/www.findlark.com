<?php

/**
 * This is the model class for table "lark_extends".
 *
 * The followings are the available columns in table 'lark_extends':
 * @property integer $id
 * @property string $title
 * @property string $path
 * @property string $thumb
 */
class LarkExtends extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LarkExtends the static model class
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
		return 'lark_extends';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, path, thumb', 'required'),
			array('title, path', 'length', 'max'=>200),
			array('thumb', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, path, thumb', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'path' => 'Path',
			'thumb' => 'Thumb',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('thumb',$this->thumb,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	// 过滤器
	public function getFilter($attributes = null, $isAll = false) {
		$request = Yii::app()->request;
		$filter = $this->attributes;
		foreach($this->attributes as $key => $val) {
			$filter[$key] = $request->getParam($key, null);
		}
		
		return $filter;
	}
	
	// 筛选器
	public function getCriteria($filter) {
		$criteria=new CDbCriteria;
		
		foreach($this->attributes as $key => $val) {
			$fuzzy = in_array($key, array('path', 'title'));
			if($filter[$key] !== null) $criteria->compare($key, $filter[$key], $fuzzy);
		}
		$criteria->order = "`id` DESC";
		return $criteria;
	}
}