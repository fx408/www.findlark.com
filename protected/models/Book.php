<?php

/**
 * This is the model class for table "book".
 *
 * The followings are the available columns in table 'book':
 * @property integer $bookid
 * @property string $content
 * @property string $timeline
 * @property integer $weights
 */
class Book extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Book the static model class
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
		return 'book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bookid, content, timeline', 'required'),
			array('bookid, weights', 'numerical', 'integerOnly'=>true),
			array('timeline', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bookid, content, timeline, weights', 'safe', 'on'=>'search'),
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
			'bookid' => 'Bookid',
			'content' => 'Content',
			'timeline' => 'Timeline',
			'weights' => 'Weights',
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

		$criteria->compare('bookid',$this->bookid);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('timeline',$this->timeline,true);
		$criteria->compare('weights',$this->weights);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function addBook($bookid) {
		$data = $this->findByPk($bookid);
		if(empty($data)) {
			$data = Curl::model()->request('http://api.douban.com/v2/book/'.$bookid);
			
			if(!$data) {
				echo "failed! ".$bookid." \n";
				return;
			}
			
			$data = CJSON::decode($data, false);
			
			$book = array();
			$book['title'] = $data->title;
			$book['author'] = implode(',', $data->author);
			$book['tags'] = $data->tags[0]->name.', '.$data->tags[1]->name;
			$book['description'] = '';
			$book['numRaters'] = $data->rating->numRaters;
			$book['score'] = $data->rating->average;
			$book['img'] = $data->images->small;
			$book['bookid'] = $data->id;
			$book['summary'] = $data->summary;
			$book['author_intro'] = $data->author_intro;
			$book['catalog'] = $data->catalog;
			// 73628765/ http://api.douban.com/people/73628765/collection?
			$model = Book::model();
			$model->bookid = $bookid;
			$model->content = CJSON::encode($book);
			$model->weights = 1;
			$model->timeline = time();
			$model->isNewRecord = true;
			if(!$model->save()) {
				throw new Exception(var_export($model->getErrors(), true));
			}
		}
	}
	
	public function getList() {
		$page = intval( Yii::app()->request->getParam('page') );
		$page = max($page, 1);
		
		$pageSize = 10;
		$criteria=new CDbCriteria;
		$criteria->order = 'timeline DESC';
		$criteria->offset = ($page-1)*$pageSize;
		$criteria->limit = $pageSize;
		
		$data = $this->findAll($criteria);
		$result = array();
		
		foreach($data as $item) {
			$content = CJSON::decode($item->content);
			
			$tmp = array();
			$tmp['bookid'] = $item->bookid;
			$tmp['author'] = $content['author'];
			$tmp['title'] = $content['title'];
			$tmp['score'] = $content['score'];
			$tmp['numRaters'] = $content['numRaters'];
			$tmp['tags'] = $content['tags'];
			$tmp['img'] = $content['img'];
			$tmp['summary'] = empty($content['description']) ? mb_substr($content['summary'], 0, 60, 'utf8').'...' : $content['description'];
			
			$result[] = $tmp;
		}
		
		return array($page, $result);
	}
}