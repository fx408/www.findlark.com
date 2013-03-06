<?php
class TestCommand extends CConsoleCommand {
	
	public function actionIndex() {
		$file = dirname(__FILE__).'/data/city2.txt';
		
		$fp = fopen($file, 'r');
		$model = cityList::model();
		
		$parent_id = 0;
		while(!feof($fp)) {
			$line = fgets($fp);
			$first = substr($line, 0, 1);
			if($first == 'a') {
				$type = 0;
				$parent_id = 0;
			}
			else if($first == '1') $type = 1;
			else $type = 2;
			
			$line = preg_replace("/[\s\r\n\ta1]+/", "", trim($line));
			
			if(empty($line)) continue;
			echo $line;
			echo "\r\n";
			
			$model->id = null;
			$model->name = $line;
			$model->parent_id = $parent_id;
			$model->type = $type;
			$model->isNewRecord = true;
			if(!$model->save()) {
				print_r($model->getErrors());
				exit();
			}
			
			if($type == 0) {
				$parent_id = $model->id;
			}
		}
	}
	
}