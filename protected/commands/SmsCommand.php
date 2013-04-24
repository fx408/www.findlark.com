<?php
class SmsCommand extends CConsoleCommand {
	
	public function actionIndex() {
		$data = Curl::model()->request('http://m.weather.com.cn/data/101010100.html', array('header'=>0));
		$data = CJSON::decode($data);
		$data = $data['weatherinfo'];
		
		$message = sprintf("%s, 今日:%s, %s, %s; 明日:%s, %s, %s", 
		$data['city'], $data['weather1'], $data['temp1'], $data['wind1'], $data['weather2'], $data['temp2'], $data['wind2']);
		
		for($i = 0; $i < 3; $i++) {
			if($this->send($message)) break;
		}
	}
	
	private function send($message) {
		PHPFetion::model()->login(Yii::app()->params->myMobile, Yii::app()->params->myMobilePassword);
		return PHPFetion::model()->send(Yii::app()->params->myMobile, $message);
	}
}
