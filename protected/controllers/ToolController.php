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
	
	public function actionTest() {
		$data = Curl::model()->request('http://m.weather.com.cn/data/101010100.html', array('header'=>0));
		$data = CJSON::decode($data);
		$data = $data['weatherinfo'];
		
		$message = sprintf("%s, 今日:%s, %s, %s; 明日:%s, %s, %s", 
		$data['city'], $data['weather1'], $data['temp1'], $data['wind1'], $data['weather2'], $data['temp2'], $data['wind2']);
		
		PHPFetion::model()->login(Yii::app()->params->myMobile, Yii::app()->params->myMobilePassword);
		$statu = PHPFetion::model()->send(Yii::app()->params->myMobile, $message);
	}
	
}