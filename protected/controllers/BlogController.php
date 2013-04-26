<?php
class BlogController extends Controller {
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->limit = 30;
		$criteria->order = '`id` desc';
		$list = LarkNovel::model()->findAll($criteria);
		
		$this->render('index', array('list'=>$list));
	}
	
	/*
	 * AJAX 方式获取列表， 输出JSON
	 */
	public function actionAjaxList() {
		
		$page = intval( Yii::app()->request->getParam('page', 1) );
		$page = max($page, 1);
		$pageSize = $page == 1 ? 20 : 10;
		
		$criteria = new CDbCriteria;
		$criteria->offset = ($page - 1) * $pageSize;
		$criteria->limit = $pageSize;
		$criteria->order = '`id` desc';
		
		$list = LarkNovel::model()->findAll($criteria);
		$result = array();
		if(!empty($list)) {
			foreach($list as $item) {
				$item->content = '';
				$result[$item->id] = $item->attributes;
			}
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}
	
	/*
	 * 查看文章内容
	 */
	public function actionShow($id) {
		$id = intval($id);
		$model = LarkNovel::model();
		$content = $model->findByPk($id);
		if(empty($content)) {
			echo '文章不存在!';
			Yii::app()->end();
		}
		
		$criteria = new CDbCriteria;
		$criteria->compare('`id`', '>'.$id);
		$criteria->order = '`id` ASC';
		$prev = $model->find($criteria);
		
		$criteria = new CDbCriteria;
		$criteria->compare('`id`', '<'.$id);
		$criteria->order = '`id` DESC';
		$next = $model->find($criteria);
		
		$this->pageTitle = $content->title;
		$this->pageKeywords = $content->title;
		$this->pageDescription = $content->summary;
		
		$this->render('show', array('content'=>$content, 'prev'=>$prev, 'next'=>$next));
	}
	
	public function actionList() {
		$page = Yii::app()->request->getParam('page', 1);
		$page = max($page, 1);
		$pageSize = $page == 1 ? 20 : 10;
		
		$criteria = new CDbCriteria;
		if(isset($_GET['tag']) && !empty($_GET['tag'])) {
			$criteria->compare('tag', $_GET['tag'], true);
		} else if(isset($_GET['classify']) && !empty($_GET['classify'])) {
			$criteria->compare('classify', $_GET['classify']);
		} else if(isset($_GET['search']) && !empty($_GET['search'])) {
			$criteria->compare('title', $_GET['search'], true);
		}
		
		$criteria->offset = ($page - 1) * $pageSize;
		$criteria->limit = $pageSize;
		
		$blogList = LarkBlog::model()->findAll($criteria);
		if(empty($blogList)) {
			echo '';
			Yii::app()->end();
		}
		
		$classifys = LarkClassify::model()->findAll();
		$list = array();
		foreach($classifys as $item) {
			$classifyList[$item['id']] = $item['name'];
		}
		
		$list = array();
		$i = 0;
		foreach($blogList as $item) {
			$list[$i] = array(
				'id'=> $item->id,
				'title'=> $item->title,
				'timeline'=> date('Y-m-d H:i', $item->timeline),
				'classify_id'=> $item->classify,
				'classify_name'=> isset($classifyList[$item->classify]) ? $classifyList[$item->classify] : '尚未分类',
				'summary'=> $item->summary,
				'pic'=>  $item->title,
				'comment_count'=> 10,
				'tag'=> explode(',', $item->tag),
			);
			$i++;
		}
		
		echo CJSON::encode($list);
		Yii::app()->end();
	}
	
	public function actionClassify() {
		$classifys = LarkClassify::model()->findAll();
		$list = array();
		foreach($classifys as $item) {
			$list[$item['id']] = $item['name'];
		}
		
		echo 'var classifyList = '.CJSON::encode($list);
	}
}