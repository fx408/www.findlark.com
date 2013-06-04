<?php
/**
 * 相似图片搜索hash指纹算法  php实现
 * 适用于 缩略图查询原图
 */
class ImageHash extends Image{
	// 缩略图尺寸
	public $thumbWidth = 0;
	public $thumbHeight = 0;
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function getImageHash($file, $thumbWidth = 8, $thumbHeight = 8) {
		$this->init($file);

		$this->thumbHeight = $thumbHeight ? $thumbHeight : floor($this->imgHeight / 10);
		$this->thumbWidth = $thumbWidth ? $thumbWidth : floor($this->imgWidth / 10);
		
		$this->img->thumbnailImage($this->thumbWidth, $this->thumbHeight);
		return $this->getHash();
	}
	
	/**
	 * 计算图片 HASH 值
	 * return Array 返回2进制 及 二维hash数组
	 */
	protected function getHash() {
		$gray = array();
		$total = 0;
		
		for($y=0; $y < $this->thumbHeight; $y++) {
			for($x=0; $x < $this->thumbWidth; $x++) {
				$rgb = $this->img->getImagePixelColor($x, $y)->getColor();
				$l = round($rgb['r'] * 0.3 + $rgb['g'] * 0.59 + $rgb['b'] * 0.11);
				$hash[$y][$x] = $l;
				$total += $l;
			}
		}
		$average = $total / ($this->thumbHeight * $this->thumbWidth);
		$hashString = '';
		foreach($hash as $y => &$row) {
			foreach($row as $x => &$v) {
				$v = $v < $average ? 0 : 1;
				$hashString .= $v;
			}
		}
		
		return array($hash, $hashString);
	}
	
	/**
	 * 计算两个hash的汉明距离，2进制值
	 */
	public function getHashHamming($hash1, $hash2) {
		$hashGmp1 = gmp_init($hash1, 2);
		$hashGmp2 = gmp_init($hash2, 2);
		
		return gmp_hamdist($hashGmp1, $hashGmp2);
		
		$count = 0;
		$negative = gmp_init('-1');
		$xor = gmp_xor($hashGmp1, $hashGmp2);
		
		while(gmp_strval($xor, 2)) {
    	++$count;
    	$xor = gmp_and($xor, gmp_add($xor, $negative));
		}
		return $count;
	}
}