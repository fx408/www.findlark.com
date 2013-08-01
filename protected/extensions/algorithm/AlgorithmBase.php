<?php

class AlgorithmBase{
	protected $imgData = array(); // 灰度图像数据
	protected $imgWidth = 0; // 高度
	protected $imgHeight = 0; // 宽度
	
	private static $_models = array();
	public static function model($className = __CLASS__, $img = null) {
		if(self::$_models[$className]==null) {
			self::$_models[$className] = new $className;
		}
		
		if(!empty($img)) self::$_models[$className]->init($img);
		return self::$_models[$className];
	}
	
	// 初始化
	protected function init($img) {
		$this->imgData = $img[0];
		$this->imgWidth = $img[1];
		$this->imgHeight = $img[2];
	}
	
	// 初始化检查
	protected function checkInit() {
		if(empty($this->imgData)) throw new Exception("Please init!");
	}
}