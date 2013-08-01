<?php
class BookController extends AdminController {
	
	public function actionIndex($bookid = 0) {
		$data = array();
		
		if($bookid) {
			$data = Book::model()->findByPk($bookid);
			if(empty($data)) {
				$data = Curl::model()->request('http://api.douban.com/v2/book/'.$bookid);
				$data = CJSON::decode($data, false);
				$data->author = implode(',', $data->author);
				$data->tags = $data->tags[0]->name.', '.$data->tags[1]->name;
				$data->description = '';
				$data->numRaters = $data->rating->numRaters;
				$data->score = $data->rating->average;
				$data->img = $data->images->small;
				$data->bookid = $data->id;
			} else {
				$data = CJSON::decode($data->content, false);
			}
		}
		
		$this->render('index', array('data'=> $data));
	}
	
	public function actionSave($bookid) {
		$data = Book::model()->findByPk($bookid);
		
		$content = Yii::app()->request->getParam('book');
		$content['bookid'] = $bookid;
		$content =  CJSON::encode($content);
		
		$weights = intval( Yii::app()->request->getParam('weights') );
		if(empty($data)) {
			$model = Book::model();
			$model->bookid = $bookid;
			$model->content = $content;
			$model->weights = $weights;
			$model->timeline = time();
			$model->isNewRecord = true;
			if(!$model->save()) {
				throw new Exception(var_export($model->getErrors(), true));
			}
		} else {
			$data->content = $content;
			$data->weights = $weights;
			$data->save();
		}
		
		$this->redirect('/admin/book/index/bookid/'.$bookid);
	}
	
	public function actionDel() {
		
	}
	
	public function actionDownload($keyword = null, $from = null) {
		$froms = array(
			'sina'=>'http://ishare.iask.sina.com.cn/search.php?key=%s&from=file&format=txt%spdf',
		);
		// ÐÂÀË°®ÎÊ
		
		if($keyword && $froms[$from]) {
			$url = sprintf($froms[$from], urlencode($keyword), '%7e');
			$content = Curl::model()->request($url);
			
			preg_match_all("#href\=\"/f/(\d+)\.html\"#", $content, $matchs);
		}
		
		$this->render('download', array('matchs'=> $matchs));
	}
}