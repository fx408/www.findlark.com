<?php

class AlgorithmBase{
	protected $imgData = array(); // �Ҷ�ͼ������
	protected $imgWidth = 0; // �߶�
	protected $imgHeight = 0; // ���
	
	private static $_models = array();
	public static function model($className = __CLASS__, $img = null) {
		if(self::$_models[$className]==null) {
			self::$_models[$className] = new $className;
		}
		
		if(!empty($img)) self::$_models[$className]->init($img);
		return self::$_models[$className];
	}
	
	// ��ʼ��
	protected function init($img) {
		$this->imgData = $img[0];
		$this->imgWidth = $img[1];
		$this->imgHeight = $img[2];
	}
	
	// ��ʼ�����
	protected function checkInit() {
		if(empty($this->imgData)) throw new Exception("Please init!");
	}
}