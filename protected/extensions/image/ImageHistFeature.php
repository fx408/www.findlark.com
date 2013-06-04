<?php
/**
 * 相似图片搜索 直方图特征 算法 php实现
 * 
 * 使用 余弦相似度 计算两直方图特征
 * 
 * 适用于 缩略图找 原图， 部分图片找 原图
 */
class ImageHistFeature extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getFeature($file) {
		$this->init($file);
		return $this->getImageFeature();
	}

	// 提取直方图特征
	protected function getImageFeature() {
		$histogram = $this->getImageHistogram();
		$feature = array();
		$len = count($histogram['r']);

		for($i = 0; $i < $len; $i++) {
			for($j = 0; $j < $len; $j++) {
				for($k = 0; $k < $len; $k++) {
					$feature[] = $histogram['r'][$i]+$histogram['g'][$j]+$histogram['b'][$k];
				}
			}
		}

		return $feature;
	}

	/*
	 * 获取特征直方图， 并非全色彩直方图
	 * 0~255分成四个区：0~63为第0区，64~127为第1区，128~191为第2区，192~255为第3区
	 * 从而得到4*4*4 = 64 种组合
	 */
	protected function getImageHistogram() {
		$histogram = array('r'=> array(0,0,0,0), 'g'=> array(0,0,0,0), 'b'=> array(0,0,0,0));

		$pixels = $this->img->getImageHistogram();
		foreach($pixels as $p) {
			$rgb = $p->getColor();
			$colorCount = $p->getColorCount();

			$histogram['r'][floor($rgb['r']/64)] += $colorCount;
			$histogram['g'][floor($rgb['g']/64)] += $colorCount;
			$histogram['b'][floor($rgb['b']/64)] += $colorCount;
		}

		return $histogram;
	}
}