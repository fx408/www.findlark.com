<?php
/**
 * 图片二值法 特征提取
 * 首先将图片转换为 固定大小的 缩略图，然后做灰度化处理的到灰度图片，
 * 依次判断每个像素点，大于阀值 设置为255， 小于阀值 设置为 0
 * 从而得到一张只有和白的图片
 */
class ImageTwoValue extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getValue($file) {
		$this->init($file);
		
		//$this->img->thumbnailImage($this->thumbWidth, $this->thumbHeight);

		return $this->getTwoValue();
	}

	// 转换为二值图像
	public function getTwoValue() {
		$imageData = ImageProcess::model()->createGrayImage($this->img);
		$threshold = Otsu::threshold($imageData);

		echo "threshold: ".$threshold." \n";
		foreach($imageData as &$v) {
			$v = $v >= $threshold ? 255 : 0;
		}
		$edgeData = $this->edgeTrace($imageData);
		
		ImageProcess::model()->outputImage($imageData, $this->imgWidth, $this->imgHeight);
		ImageProcess::model()->outputImage($edgeData, $this->imgWidth, $this->imgHeight);
		return $imageData;
	}
	
	/* 二值图像边缘提取
	 * 计算某点周围8个点的值，若有其中一个为 255 则该点为边界点
	 * 1  2  3
	 * 4  C  5
	 * 6  7  8
	 */
	public function edgeTrace($imageData) {
		$imgDataOut = array_fill(0, $this->imgHeight*$this->imgWidth, 0);
		$direction = array(
			array(-1,-1),
			array(-1, 0),
			array(-1, 1),
			array(0, -1),
			array(0,  1),
			array(1, -1),
			array(1,  0),
			array(1,  1)
		);
		
		//循环变量，图像坐标
		for($i = 0; $i < $this->imgHeight; $i++) {
			for($j = 0; $j < $this->imgWidth; $j++) {
				$index = $i * $this->imgWidth + $j;
				if($imageData[$index] == 255) continue;
				
				foreach($direction as $item) {
					$point = $index + $item[0] * $this->imgWidth + $item[1];
					if($imageData[$point] == 255) {
						$imgDataOut[$index] = 255;
						break;
					}
				}
			}
		}
		
		return $imgDataOut;
	}
}