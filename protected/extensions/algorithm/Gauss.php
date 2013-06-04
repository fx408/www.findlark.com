<?php
/*
 * 对灰度 图像数据的 高斯滤波
 */
class Gauss{
	protected $sigma = 0;	// 高斯函数的标准差
	protected $size = 0; // 滤波窗口的大小
	protected $center = 0; 	// 滤波窗口中心的索引

	protected $imgData = array(); // 灰度图像数据
	protected $imgWidth = 0; // 高度
	protected $imgHeight = 0; // 宽度
	
	protected $pi = 3.14159;
	
	private static $_model = null;
	public static function model($img = null, $sigma = 0.4) {
		if(self::$_model==null) {
			self::$_model = new self;
			if(!empty($img)) self::$_model->init($img, $sigma);
		}

		return self::$_model;
	}

	/*
	 * 初始化
	 * 设置高斯 函数的参数
	 * 获取图像参数
	 */
	public function init($img, $sigma = 0.4) {
		$this->imgData = $img[0];
		$this->imgWidth = $img[1];
		$this->imgHeight = $img[2];

		$this->sigma = min(max(0, $sigma), 1);
		$this->size = 1 + 2 * ceil(3 * $this->sigma);
		$this->center = floor($this->size / 2);
	}
	
	protected function checkInit() {
		if(empty($this->imgData)) throw new Exception("Please init!");
	}

	/* 一维高斯滤波系数
	 * 一维高斯函数公式
	 * K = pow(e, -1*x*x/2*Sigma*Sigma) / sqrt(2*pi*Sigma)
	 */
	protected function oneKernal() {
		$sum = 0;
		$kernal = array();

		for($i=0; $i < $this->size; $i++) {
			$x = $i - $this->center;
			$kernal[$i] = exp(-0.5*pow($x,2)/pow($this->sigma,2))/(sqrt(2*$this->pi)*$this->sigma);
			$sum += $kernal[$i];
		}

		foreach($kernal as &$item) {
			$item /= $suml;
		}
		return $kernal;
	}

	// 使用一维高斯核 分别对x方向 和y方向进行高斯滤波
	public function oneFilter() {
		$this->checkInit();
		$kernal = $this->oneKernal();

		$tempData = array();
		$filter = array();

		//进行x向的高斯滤波(加权平均)
		for($y = 0; $y < $this->imgHeight; $y++) {
			for($x=0; $x < $this->imgWidth; $x++) {
				$sum_kernal = 0;
				$sum = 0;
				//滤波中间值
				for($limit = -$this->center; $limit <= $this->center; $limit++) {
					//图像不能超出边界
					if(($x+$limit) >= 0 && ($x+$limit) < $this->imgWidth) {
						$key = $y * $this->imgWidth + $x + $limit;

						$sum += $this->imgData[$key] * $kernal[$this->center + $limit];
						$sum_kernal += $kernal[$this->center + $limit];
					}
				}
				$tempData[$y*$this->imgWidth+$x] = $sum/$sum_kernal;
			}
		}

		//进行y向的高斯滤波(加权平均)
		for($x = 0; $x < $this->imgWidth; $x++) {
			for($y = 0; $y < $this->imgHeight; $y++) {
				$sum_kernal = 0.0;
				$sum = 0;
				for($limit = -$this->center; $limit <= $this->center; $limit++) {

					//图像不能超出边界
					if(($y+$limit) >= 0 && ($y+$limit) < $this->imgHeight) {
						$key = ($y + $limit) * $this->imgWidth + $x;

						$sum += $tempData[$key] * $kernal[$this->center + $limit];
						$sum_kernal += $kernal[$this->center + $limit];
					}
				}
				$filter[$y*$this->imgWidth + $x] = max(0, $sum/$sum_kernal);
			}
		}

		return $filter;
	}

	/* 二维高斯滤波系数
	 * 二维高斯函数公式
	 * K = pow(e, -0.5*(x*x+y*y)/(Sigma*Sigma)) / 2*PI*Sigma*Sigma
	 */
	protected function twoKernal() {
		$kernal = array();
		$sum = 0; //求和，进行归一化

		for($i = 0; $i < $this->size; $i++) {
			$x = $i - $this->center;

			for($j = 0; $j < $this->size; $j++) {
				$y = $j - $this->center;
				$k = $i + $j*$this->size;

				$kernal[$k] = exp(-0.5*(pow($x,2)+pow($y,2))/pow($this->sigma,2)) / (2*$this->pi*pow($this->sigma,2));
				$sum += $kernal[$k];
			}
		}

		foreach($kernal as &$item) {
			$item /= $sum;
		}
		
		return $kernal;
	}

	// 二维高斯滤波
	public function twoFilter() {
		$this->checkInit();
		$kernal = $this->twoKernal();

		$x = $y = 0;
		$filter = array();

		for($i = 0; $i < $this->imgHeight; $i++) {
			for($j = 0; $j < $this->imgWidth; $j++) {
				$sum_kernal = 0; $sum = 0;

				for($x=-$this->center; $x <= $this->center; $x++) {
					//判断边缘
					if(($j+$x) >= 0 && ($j + $x) < $this->imgWidth && ($i+$y) >=0 && ($i+$y) < $this->imgHeight) {
						$k1 = ($y+$this->center)*$this->size + $x + $this->center;
						$k2 = ($i+$y)*$this->imgWidth +$j+$x;
						
						$sum += $this->imgData[$k2] * $kernal[$k1];
						$sum_kernal += $kernal[$k1];
					}
				}
				
				$filter[$i*$this->imgWidth + $j] = max(0, $sum/$sum_kernal);
			}
		}
		
		return $filter;
	}
}