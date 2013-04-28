<?php

/**
 * This is the model class for table "lark_novel".
 *
 * The followings are the available columns in table 'lark_novel':
 * @property integer $id
 * @property string $title
 * @property string $summary
 * @property string $content
 * @property integer $display
 */
class LarkNovel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return LarkNovel the static model class
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
		return 'lark_novel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, summary, content', 'required'),
			array('display', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('summary', 'length', 'max'=>300),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, summary, content, display', 'safe', 'on'=>'search'),
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
			'summary' => 'Summary',
			'content' => 'Content',
			'display' => 'Display',
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
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('display',$this->display);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	// ¹ýÂËÆ÷
	public function getFilter($attributes = null, $isAll = false) {
		$request = Yii::app()->request;
		$filter = $this->attributes;
		foreach($this->attributes as $key => $val) {
			$filter[$key] = $request->getParam($key, null);
		}
		
		return $filter;
	}
	
	// É¸Ñ¡Æ÷
	public function getCriteria($filter) {
		$criteria = new CDbCriteria;
		
		foreach($this->attributes as $key => $val) {
			$fuzzy = in_array($key, array('title', 'summary'));
			if($filter[$key] !== null) $criteria->compare($key, $filter[$key], $fuzzy);
		}
		$criteria->order = "`id` DESC";
		return $criteria;
	}
	
	public function newArticles() {
		$criteria = new CDbCriteria;
		$criteria->select = '`id`, `title`';
		$criteria->order = "`id` DESC";
		$criteria->limit = 5;
		
		return $this->findAll($criteria);
	}
}