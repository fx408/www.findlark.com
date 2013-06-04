<?php

class ToolController extends Controller {
	public function actionIndex() {
		$data = LarkExtends::model()->findAll();
		
		$this->render('index', array('data'=>$data));
	}
	
	public function actionQiyi() {
		$request = Yii::app()->request;
		if($request->isAjaxRequest) {
			
			$loginUrl = Qiyi::model()->loginUrl;
			
			if(!$loginUrl) {
				$r = Qiyi::model()->passwordStatus(2);
				$this->_end(1, '正在重置密码!请稍候尝试!'.$r);
			}
			
			$this->_end(0, $loginUrl);
		}
		/*
		echo Qiyi::model()->password." | ";
		echo Qiyi::model()->newPassword." | ";
		echo Qiyi::model()->passwordStatus();
		*/
		$this->render('qiyi');
	}
	
	public function actionDos() {
		if(isset($_GET['s'])) {
			echo Yii::app()->redis->get('fx_box_count');
			Yii::app()->end();
		}
		
		Yii::app()->redis->incr('fx_box_count');
	}
	
	public function actionNovel() {
		$books = array(
			'http://read.qidian.com/BookReader/2647164.aspx',
			'http://read.qidian.com/BookReader/2291879.aspx'
		);
		
		foreach($books as $url) {
			$this->matchQidian($url);
		}
	}
	
	private function matchQidian($url) {
		$content = Curl::model()->request($url);
		$content = mb_convert_encoding($content, 'GBK', 'UTF-8');
		
		echo '<pre>';
		$reg = "#\<li\s+style=(?:\"|\')width\:33\%\;(?:\"|\')\>(.*?)\<\/li\>#is";
		$m = preg_match_all($reg, $content, $match);
		if($m && isset($match[1])) print_r(array_slice($match[1], -5));
	}
	
	public function actionTest() {
		$pic_original = Yii::app()->basePath.'/../static/QXOOWHQ.jpg';
		$pic_cut = Yii::app()->basePath.'/../static/QXOOWHQ_cut.jpg';
		$pic_small = Yii::app()->basePath.'/../static/QXOOWHQ_cut.jpg';
		
		$hash_original = ImageHash::model()->getImageHash($pic_original);
		$hash_cut = ImageHash::model()->getImageHash($pic_cut);
		$pic_small = ImageHash::model()->getImageHash($pic_small);
		
		echo '<pre>';
		//print_r($hash_original);
		print_r($hash_cut);
	}
	
	public function actionCanny() {
		$img = Yii::app()->basePath.'/../static/test.jpg';
		ImageCanny::model()->run($img);
		
	}
	
}