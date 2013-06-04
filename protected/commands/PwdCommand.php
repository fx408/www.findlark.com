<?php

class PwdCommand extends CConsoleCommand {
	const FILE_NUM = 100;
	
	public function actionIndex() {
		
	}
	
	// 分割密码文件
	public function actionBreak() {
		$path = Yii::app()->basePath.'/../source/password/';
		$file = $path.'password.txt';
		
		$i = 0;
		$fp = fopen($file, 'r');
		$fps = array();
		
		while(!feof($fp)) {
			$pwfile = $path.'password_'.$i.'.txt';
			if(!$fps[$i]) $fps[$i] = fopen($pwfile, 'a+');
			
			$line = fgets($fp);
			fwrite($fps[$i], $line);
			
			$i++;
			$i = $i % self::FILE_NUM;
		}
		
		fclose($fp);
		foreach($fps as $fp) {
			fclose($fp);
		}
	}
	
	
}
	