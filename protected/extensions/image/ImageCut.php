<?php
/**
 * ÕºœÒ◊÷∑˚«–∏Ó
 */
class ImageCut extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function cutImage($img, $turn = false) {
		$this->init($img);
		$imageData = ImageProcess::model()->createGrayImage($this->img);
		
		if($turn) $this->turn($imageData);
		
		// ImageProcess::model()->outputImage($imageData,$this->imgWidth,$this->imgHeight);
		
		$this->cut($imageData);
		//$this->thumb();
	}
	
	// ∑÷∏ÓÕº∆¨…œµƒ ˝◊÷
	public function cut($imageData) {
		$data = array();
		$dataX = array_fill(0, $this->imgWidth, 0);
		$dataX[0] = -1;
		$dataX[$this->imgWidth-1] = -1;
		
		$dataY = array_fill(0, $this->imgHeight, 0);
		$dataY[0] = -1;
		$dataY[$this->imgHeight-1] = -1;
		
		for($i = 1; $i < $this->imgHeight; $i++) {
			for($j = 1; $j < $this->imgWidth; $j++) {
				$index = $i * $this->imgWidth + $j;
				if($imageData[$index] == 0) {
					$dataX[$j]++;
				}
			}
		}
		
		$dataX = $this->dividingLines($dataX, $this->imgWidth);
		
		//  ˙œÚ«–∏ÓÕº∆¨
		for($k = 0; $k < count($dataX)-1; $k++) {
			$data[$k]['width'] = $dataX[$k+1]-$dataX[$k]-1;
			$data[$k]['height'] = $this->imgHeight;
			$data[$k]['data'] = array();
			$data[$k]['count'] = 0;
		}
		
		for($i = 1; $i < $this->imgHeight; $i++) {
			for($j = 1; $j < $this->imgWidth; $j++) {
				$index = $i * $this->imgWidth + $j;
				
				for($k = 0; $k < count($dataY)-1; $k++) {
					if($j > $dataX[$k] && $j < $dataX[$k+1]) {
						$data[$k]['data'][] = $imageData[$index];
						if($imageData[$index]==0) $data[$k]['count']++;
						break;
					}
				}
			}
		}
		
		// »•≥˝ ˙œÚø’∞◊øÈ
		foreach($data as $key => $item) {
			if($item['count'] < 10) unset($data[$key]);
			else $data[$key]['new'] = array();
		}
		
		// œ˚≥˝∫·––ø’∞◊
		foreach($data as $key => $item) {
			$len = count($item['data']);
			$temp = array();
			$c = 0;
			
			for($i = 1; $i <= $len; $i++) {
				if($item['data'][$i-1] == 0) $c++;
				$temp[] = $item['data'][$i-1];
				
				if($i%$item['width'] == 0) {
					if($c > 1) {
						$data[$key]['new'] = array_merge($data[$key]['new'], $temp);
					}
					$c = 0;
					$temp = array();
				}
			}
		}
		
		foreach($data as $key => $item) {
			sleep(1);
			ImageProcess::model()->outputImage(
				$item['new'],
				$item['width'],
				floor(count($item['new'])/$item['width'])
			);
		}
		
		//print_r($dataX);
		//print_r($dataY);
	}
	
	// ≈–∂œ∑÷∏Óœﬂ
	public function dividingLines($data, $n) {
		$max = 1;
		for($i = 1; $i < $n; $i++) {
			if($data[$i] < $max) {
				$data[$i] = -1;
			} else {
				unset($data[$i]);
			}
		}
		foreach($data as $key => $val) {
			if(isset($data[$key-1]) && isset($data[$key+1])) $data[$key] = 0;
		}
		foreach($data as $key => $val) {
			if($val == 0) unset($data[$key]);
		}
		$data = array_keys($data);
		
		return $data;
	}
	
	// …˙≥… 16*16 µƒÕº∆¨
	public function thumb() {
		$dir = dirname(__FILE__).'/temp';
		$files = scandir($dir);
		
		foreach($files as $item) {
			$f = $dir.'/'.$item;
			if(is_file($f) && preg_match("#.jpg$#", $f)) {
				$img = new Imagick($f);
				$img->thumbnailImage(16, 16);
				$img->writeImage($f);
				$img->destroy();
			}
		}
	}
	
	public function read($d = '') {
		$dir = dirname(__FILE__).'/temp/'.$d;
		$files = scandir($dir);
		
		$data = array();
		
		foreach($files as $item) {
			$f = $dir.'/'.$item;
			if(is_file($f) && preg_match("#.jpg$#", $f)) {
				$img = new Imagick($f);
				$data[$item] = ImageProcess::model()->createGrayImage($img);
				$img->destroy();
			}
		}
		
		return $data;
	}
	
	// ±»Ωœ
	public function compare() {
		$sample = $this->read('sample');
		$want = $this->read();
		
		$sims = array();
		
		foreach($want as $k1 => $item) {
			foreach($sample as $k2 => $s) {
				$sims[$k1][$k2] = Sim::get($item, $s);
			}
		}
		
		foreach($sims as $item) {
			arsort($item);
			$keys = array_keys($item);
			echo preg_replace("#(_.*?\.jpg)|(\.jpg)#", '', $keys[0])." => ".$item[$keys[0]]." \n";
		}
	}
	
	// ∑≠◊™∫⁄∞◊
	public function turn(&$imageData) {
		$len = $this->imgWidth*$this->imgHeight;
		
		for($i = 0; $i < $len; $i++) {
			$imageData[$i] = $imageData[$i] > 240 ? 0 : 255;
		}
	}
}