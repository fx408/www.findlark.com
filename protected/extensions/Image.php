<?php
class Image extends ExtensionsBase{
	public $imgList;
	public $allowExts = array('jpg', 'gif', 'png', 'jpeg');
	
	public static function model($className = __CLASS__) {
		if (!class_exists('Imagick')) {
			throw new Exception('Must load the Imagick extension');
		}
		
		return parent::model($className);
	}
	
	/*
	 * 检测文件是否存在
	 * @param $file 文件完整路径
	 * return 如果不存在将直接抛出异常，存在 返回 true
	 */
	public function fileExists($file) {
		if(!file_exists($file)) {
			throw new Exception('File does not exist! '.$file);
		}
		
		return true;
	}
	
	/**
	 * 生成文件名
	 * @param String $ext 扩展名
	 * return 8个大写因为字符
	 */
	public function makeFileName($ext = '') {
		$n = microtime();
		$n = explode(' ', $n);
		$n = ($n[1]%86400 + $n[0]) * 100000;

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
	public function makeDir($root = 'upload') {
		$basePath = realpath(Yii::app()->basePath.'/../').'/'.$root;
		$dir = $basePath.'/'.date('Y').'/'.date('md').'/'.floor(time()/3600).'/thumb';
		if(!file_exists($dir)) mkdir($dir, 0700, 1);
		return substr($dir, 0, -5);
	}
	
	// 生成缩略图
	public function makeThumb($imageDir, $imageName, $width = 0, $height = 0) {
		$width = max(0, intval($width));
		$height = max(0, intval($height));
		if($width == 0 && $height == 0) return false;
		
		$file = $imageDir.'/'.$imageName;
		$this->fileExists($file);
		
		$img = new Imagick($file);
		$img->thumbnailImage($width, $height);
		
		$thumbName = sprintf('thumb_%d_%d_%s', $width, $height, $imageName);
		$img->writeImage($imageDir.'thumb/'.$thumbName);
		$img->destroy();
	}
	
	public function getExtName($ext) {
		return '.'.( in_array($ext, $this->allowExts) ? $ext : $this->allowExts[0] );
	}
	
	public function deleteImage($dir, $name) {
		for($i = 0; $i < 4; $i++) {
			$thumb = $dir.'/thumb/thumb_'.($i*100 + 100).'_0_'.$name;
			if(file_exists($thumb)) unlink($thumb);
		}
		
		$file = $dir.$name;
		if(file_exists($file)) unlink($file);
	}
}