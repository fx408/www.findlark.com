<?php
class TestCommand extends CConsoleCommand {
	
	public function actionIndex() {
		set_time_limit(0);
		$start = microtime(true);
		
		
		// 生成二值图像
		$dir = Yii::app()->basePath.'/../static/discuz';
		$images = scandir($dir);
		foreach($images as $img) {
			$img = $dir.'/'.$img;
			if(is_file($img) && preg_match("#.(png|jpg|gif)$#", $img)) {
				//ImageCut::model()->cutImage($img, false);
				//ImageColor::model()->get($img);
				//ImageCanny::model()->getEdge($img, 3);
			}
		}
		
		// $img = Yii::app()->basePath.'/../static/discuz/evck.png'; // 5142, 0438, 9320, 8466, 8277, 4986, 
		/*
		 c94m
		*/
		//ImageColor::model()->get($img);
		
		// 验证码识别
		/*
		$img = Yii::app()->basePath.'/../static/discuz_2/cmjc.jpg';
		ImageCut::model()->cutImage($img, false);
		ImageCut::model()->thumb();
		ImageCut::model()->compare();
		*/
		
		// Canny 算法边缘识别
		$img = Yii::app()->basePath.'/../static/pic_300.jpg';
		ImageCanny::model()->getEdge($img, 1.8);
		
		// 相似图片比较
		/*
		$img = Yii::app()->basePath.'/../static/pic.jpg';
		$f1 = ImageHistFeature::model()->getFeature($img);
		
		$img = Yii::app()->basePath.'/../static/112.jpg';
		$f2 = ImageHistFeature::model()->getFeature($img);
		
		$sim = Sim::get($f1, $f2);
		
		echo $sim." \n";
		*/
		//$gray = ImageTwoValue::model()->getValue($img);
		
		printf("Time: %f", microtime(true)-$start);
	}
	
	public $n = 5;
	public function actionEach() {
		$this->_each();
	}
	
	public function _each() {
		$this->n--;
		if($this->n > 0) {
			echo $this->n;
			$this->_each();
		}
	}
	
	public function actionIndex2() {
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