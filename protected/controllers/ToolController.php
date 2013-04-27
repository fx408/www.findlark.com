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
	
	public function actionTest() {
		
	}
	
}