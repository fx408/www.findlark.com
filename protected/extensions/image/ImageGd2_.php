<?php
class ImageGD2_ extends ExtensionsBase{
	protected $thumbHieght = 150;
	protected $thumbWidht = 0; // 0 represents 'auto'
	protected $imageMaxHeight = 800;
	protected $imageMaxWidth = 1280; 
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function uploadImage($image, $panoramio_id) {
		$uploadDir = $this->makeDir($panoramio_id);
		$imageName = $this->createImageName('.jpg');
		$filePath = $uploadDir.$imageName;
		
		# 创建文件
		if(!@ move_uploaded_file($image['tmp_name'], $filePath)) {
			throw new Exception('File upload failed!');
		}
		
		return array('path'=>$filePath, 'dir'=>$uploadDir, 'name'=>$imageName);
	}
	
	/**
	 * 生成文件名
	 * @param String $ext 扩展名
	 * return 8个大写因为字符
	 */
	public function createImageName($ext = '') {
		$n = microtime(true) * 100;
		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$fileName = '';
		
		while($n) {
			$x = $n % 26;
			$fileName .= $str[$x];
			$n = floor($n / 26);
		}
		
		return strrev($fileName).$ext;
	}
	
	// 创建目录
	public function makeDir($panoramio_id, $root = 'upload') {
		$basePath = realpath(Yii::app()->basePath.'/../').'/'.$root;
		$dir = sprintf('%s/%s/%d/thumb', $basePath, date('Y'), $panoramio_id);
		if(!file_exists($dir)) mkdir($dir, 0700, true);
		return substr($dir, 0, -5);
	}
	
	/**
	 * 调整图片大小
	 */
	public function changeImageSize($imagePath, $savePath, $toHeight = 0) {
		list($width, $height) = getimagesize($imagePath);
		$r = $this->imageMaxWidth / $this->imageMaxHeight;
		
		if($toHeight == 0) {
			if($width > $this->imageMaxWidth || $height > $this->imageMaxHeight) {
				if($width / $height > $r) {
					$new_width = $this->imageMaxWidth;
					$new_height = $this->imageMaxWidth / $width * $height;
				} else {
					$new_height = $this->imageMaxHeight;
					$new_width = $this->imageMaxHeight / $height * $width;
				}
			} else {
				$new_height = $height;
				$new_width = $width;
			}
		} else {
			$new_height = $h;
			$new_width = $h / $height * $width;
		}
	
		$im = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($imagePath);
		imagecopyresampled($im, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
		imagejpeg($im, $savePath, 100);
	}
}