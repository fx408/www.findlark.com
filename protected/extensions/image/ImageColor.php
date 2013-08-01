<?php
/*
* 色彩统计法
*/
class ImageColor extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function get($file) {
		$this->init($file);
		
		$count = array();
		
		for($y=0; $y < $this->imgHeight; $y++) {
			for($x=0; $x < $this->imgWidth; $x++) {
				$rgb = $this->img->getImagePixelColor($x, $y)->getColor();
				
				$key = sprintf("%02s", dechex($rgb['r'])).sprintf("%02s", dechex($rgb['g'])).sprintf("%02s", dechex($rgb['b']));
				$count[$key]++;
			}
		}
		unset($count['000000'], $count['ffffff']);
		arsort($count);
		$count = array_slice($count, 0, 4, true);
		print_r($count);
		
		$newData = array();
		
		for($y=0; $y < $this->imgHeight; $y++) {
			for($x=0; $x < $this->imgWidth; $x++) {
				$rgb = $this->img->getImagePixelColor($x, $y)->getColor();
				$index = $y*$this->imgWidth + $x;
				
				$key = sprintf("%02s", dechex($rgb['r'])).sprintf("%02s", dechex($rgb['g'])).sprintf("%02s", dechex($rgb['b']));
				
				$newData[$index] = isset($count[$key]) ? 0 : 255;
			}
		}
		
		ImageProcess::model()->outputImage($newData, $this->imgWidth, $this->imgHeight);
		
		return $count;
	}
}