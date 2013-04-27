<?php
class TestCommand extends CConsoleCommand {
	
	public function actionIndex() {
		Yii::import('sch.models.*');
		$file = dirname(__FILE__).'/data/city2.txt';
		
		$fp = fopen($file, 'r');
		$model = Citys::model();
		
		$parent_0 = 0; $parent_1 = 0;
		while(!feof($fp)) {
			$line = fgets($fp);
			$first = substr($line, 0, 1);
			if($first == 'a') {
				$type = 0;
				$parent_0 = 0;
			} else if($first == '1') {
				$type = 1;
				$parent_1 = 0;
			}
			else $type = 2;
			
			$line = preg_replace("/[\s\r\n\ta1]+/", "", trim($line));
			
			if(empty($line)) continue;
			echo $line;
			echo "\r\n";
			
			$model->id = null;
			$model->name = $line;
			$model->parent_id = ($type == 2 ? $parent_1 : $parent_0);
			$model->type = $type;
			$model->isNewRecord = true;
			if(!$model->save()) {
				print_r($model->getErrors());
				exit();
			}
			
			if($type == 0) {
				$parent_0 = $model->id;
			} else if($type == 1) {
				$parent_1 = $model->id;
			}
		}
	}
	
	public function actionOpen100() {
		$url = 'http://bbs.duowan.com/?fromuid=49937681';
		
		for($i = 0; $i < 100; $i++) {
			Curl::model()->request($url);
			
			sleep(1);
		}
	}
	
}