<?php
/**
 * PHP 版的 Canny 边缘识别算法
 */
class ImageCanny extends Image{
	// 图片RGB 数据
	protected $imgData;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getEdge($file, $revise = 0) {
		$this->init($file);
		
		$this->imgData = ImageProcess::model()->createGrayImage($this->img);
		
		$this->check($revise);
	}

	/*
	 * 图像增强
	 * 计算梯度幅值 及 梯度方向
	 */
	public function powerImage() {
		$P = array(); //x向偏导数
		$Q = array(); //y向偏导数
		$M = array(); //梯度幅值
		$T = array(); //梯度方向


		$filter = Gauss::model(array($this->imgData, $this->imgWidth, $this->imgHeight), 0.4)->twoFilter();
		
		//计算x,y方向的偏导数
		for($y=0; $y < ($this->imgHeight - 1); $y++) {
			for($x = 0; $x < ($this->imgWidth - 1); $x++) {
				$key = $y*$this->imgWidth+$x;
				
				$P[$key] = (
					$filter[$y*$this->imgWidth + min($x+1, $this->imgWidth-1)] - 
					$filter[$key] + 
					$filter[min($y+1, $this->imgHeight-1)*$this->imgWidth+min($x+1, $this->imgWidth-1)] - 
					$filter[min($y+1, $this->imgHeight-1)*$this->imgWidth+$x]
				)/2;
				
				$Q[$key] = (
					$filter[$key] - 
					$filter[min($y+1, $this->imgHeight-1)*$this->imgWidth+$x] + 
					$filter[$y*$this->imgWidth+min($x+1, $this->imgWidth-1)] - 
					$filter[min($y+1, $this->imgHeight-1)*$this->imgWidth+min($x+1, $this->imgWidth-1)]
				)/2;
			}
		}

		//计算梯度幅值和梯度的方向
		for($y=0; $y < $this->imgHeight; $y++) {
			for($x=0; $x < $this->imgWidth; $x++) {
				$key = $y*$this->imgWidth+$x;
				
				$M[$key] = floor( sqrt(pow($P[$key], 2) + pow($Q[$key], 2)) + 0.5 );
				$T[$key] = atan2($Q[$key], $P[$key]) * 57.3;
				if($T[$key] < 0) {
					$T[$key] += 360; //将这个角度转换到0~360范围
				}
			}
		}
		
		return array($P, $Q, $M, $T);
	}
	
	// 非极大值抑制
	public function notMaxValue() {
		$N = array();  //非极大值抑制结果， 无符号
		$g1 = $g2 = $g3 = $g4 = 0; //用于进行插值，得到亚像素点坐标值
		$g = array();
		$dTmp1 = $dTmp2 = 0.0; //保存两个亚像素点插值得到的灰度数据
		$dWeight = array(0, 0); //插值的权重

		// 初始化边界
		for($x = 0; $x < $this->imgWidth; $x++) {
			$N[$x] = 0;
			$N[($this->imgHeight-1)*$this->imgWidth+$x] = 0;
		}
		for($y = 0; $y < $this->imgHeight; $y++) {
			$N[$y*$this->imgWidth] = 0;
			$N[$y*$this->imgWidth+$this->imgWidth-1] = 0;
		}

		list($P, $Q, $M, $T) = $this->powerImage();

		for($x=1; $x < ($this->imgWidth-1); $x++) {
			for($y=1; $y < ($this->imgHeight-1); $y++) {
				$pointIdx = $x+$y*$this->imgWidth; //当前点在图像数组中的索引值
				if($M[$pointIdx] == 0) {
					$N[$pointIdx] = 0; //如果当前梯度幅值为0，则不是局部最大对该点赋为0
				} else {
				//================周边8个点的值====================//
				/////////       g1  g2  g3               /////////////
				/////////       g4   C  g5               /////////////
				/////////       g6  g7  g8              /////////////
				//================================================//
					$g[1] = $M[$pointIdx-$this->imgWidth-1];
					$g[2] = $M[$pointIdx-$this->imgWidth];
					$g[3] = $M[$pointIdx-$this->imgWidth+1];
					$g[4] = $M[$pointIdx-1];
					$g[5] = $M[$pointIdx+1];
					$g[6] = $M[$pointIdx+$this->imgWidth-1];
					$g[7] = $M[$pointIdx+$this->imgWidth];
					$g[8] = $M[$pointIdx+$this->imgWidth+1];
					
					$dWeight = array(1, 1);
					if($P[$pointIdx]) $dWeight[0] = abs($Q[$pointIdx])/abs($P[$pointIdx]);   //正切
					if($Q[$pointIdx]) $dWeight[1] = abs($P[$pointIdx])/abs($Q[$pointIdx]);   //反正切

				//======首先判断属于那种情况，然后根据情况插值=====//
				////////////////////第一种情况///////////////////////
				/////////       g1  g2                  /////////////
				/////////           C                   /////////////
				/////////           g3  g4              /////////////
				//================================================//
					if( (($T[$pointIdx]>=90)&&($T[$pointIdx]<135)) || (($T[$pointIdx]>=270)&&($T[$pointIdx]<315)) ) {
						// 根据斜率和四个中间值进行插值求解
						$dTmp1 = $g[1]*$dWeight[1]+$g[2]*(1-$dWeight[1]);
						$dTmp2 = $g[8]*$dWeight[1]+$g[7]*(1-$dWeight[1]);
					}
				////////////////////第二种情况///////////////////////
				/////////       g1                      /////////////
				/////////       g2  C   g3              /////////////
				/////////               g4              /////////////
				/////////////////////////////////////////////////////
					else if( (($T[$pointIdx]>=135)&&($T[$pointIdx]<180)) || (($T[$pointIdx]>=315)&&($T[$pointIdx]<360)) ) {
						$dTmp1 = $g[4]*$dWeight[0]+$g[1]*(1-$dWeight[0]);
						$dTmp2 = $g[8]*$dWeight[0]+$g[5]*(1-$dWeight[0]);
					}
				////////////////////第三种情况///////////////////////
				/////////           g1  g2              /////////////
				/////////           C                   /////////////
				/////////       g4  g3                  /////////////
				/////////////////////////////////////////////////////
					else if( (($T[$pointIdx]>=45)&&($T[$pointIdx]<90)) || (($T[$pointIdx]>=225)&&($T[$pointIdx]<270)) ) {
						$dTmp1 = $g[3]*$dWeight[1]+$g[2]*(1-$dWeight[1]);
						$dTmp2 = $g[7]*$dWeight[1]+$g[6]*(1-$dWeight[1]);
					}
					////////////////////第四种情况///////////////////////
					/////////               g1              /////////////
					/////////       g4  C   g2              /////////////
					/////////       g3                      /////////////
					/////////////////////////////////////////////////////
					else if( (($T[$pointIdx]>=0)&&($T[$pointIdx]<45)) || (($T[$pointIdx]>=180)&&($T[$pointIdx]<225)) ) {
						$dTmp1 = $g[3]*$dWeight[0]+$g[5]*(1-$dWeight[0]);
						$dTmp2 = $g[6]*$dWeight[0]+$g[4]*(1-$dWeight[0]);
					}
				}
				//////////进行局部最大值判断，并写入检测结果////////////////
				if(($M[$pointIdx]>=$dTmp1) && ($M[$pointIdx]>=$dTmp2)) {
					$N[$pointIdx] = 128;
				} else {
					$N[$pointIdx] = 0;
				}
			}
		}

		// $this->outputImage($N);
		
		return array($M, $N);
	}

	// check
	public function check($revise) {
		list($M, $N) = $this->notMaxValue();
		
		// 计算高、低两个阈值
		$dRatHigh = 0.79; // 0.79
		$dRatLow = 0.5;
		$dThrHigh; // 高阈值
		$dThrLow; // 低阈值
		
		
		$M_sort = $M;
		$M_sort = array_filter($M_sort);
		// sort($M_sort);
		/*
		$total = $this->imgWidth * $this->imgHeight;
		$count_m = count($M_sort);
		$avg = round(array_sum($M_sort)/$count_m);
		$middle = $M_sort[floor($count_m/2)];
		//if($avg/$middle > 2) $middle += floor(($avg-$middle)*$middle/$avg);
		
		$key = floor($count_m * $dRatHigh);
		$dThrHigh = $M_sort[$key];
		*/
		
		$revise = max(1, $revise);
		$M_count = count($M_sort);
		$avg = round(array_sum($M_sort)/$M_count/$revise);
		$dThrHigh = Otsu::threshold($M) * $dRatHigh;
		echo "Otsus threshold: $dThrHigh \n";
		
		$dThrHigh = min(max($avg, $dThrHigh), $avg*2);
		$dThrLow = floor($dThrHigh * $dRatLow + 0.5);
		
		printf("avg: %d \n", $avg);
		//printf("middle: %d \n", $middle);
		printf("count: %d \n", $M_count);
		printf("sum: %d \n", array_sum($M_sort));
		printf("high: %d,  low: %d \n", $dThrHigh, $dThrLow);
		
		//print_r($M_sort);

		for($i=0; $i < $this->imgHeight; $i++) {
			for($j=0; $j < $this->imgWidth; $j++) {
				$key = $i*$this->imgWidth+$j;
				
				if(($N[$key]==128) && ($M[$key] >= $dThrHigh)) {
					$N[$key] = 255;
					$N = $this->TraceEdge($i, $j, $dThrLow, $N, $M);
				}
			}
		}

		//将还没有设置为边界的点设置为非边界点
		for($i=0; $i < $this->imgHeight; $i++) {
			for($j=0; $j < $this->imgWidth; $j++) {
				$key = $i * $this->imgWidth + $j;
				if($N[$key] != 255) {
					$N[$key]  = 0 ;   // 设置为非边界点
				}
			}
		}
		
		//$this->outputImage($N);
		$N = $this->clear($N);
		$this->outputImage($N);
		unset($N);
	}
	
	// 降噪
	public function clear($N) {
		/* 计算某点周围8个点的值，若全为0，则该点为噪点
		 * 1  2  3
		 * 4  C  5
		 * 6  7  8
		 */
		$param = array(
			array(-1,-1),
			array(-1, 0),
			array(-1, 1),
			array(0, -1),
			array(0,  1),
			array(1, -1),
			array(1,  0),
			array(1,  1)
		);
		
		for($x=1; $x < ($this->imgWidth-1); $x++) {
			for($y=1; $y < ($this->imgHeight-1); $y++) {
				$index = $x + $y * $this->imgWidth; //当前点在图像数组中的索引值
				if($N[$index] == 0) continue;
				
				$c = 0;
				for($i=0; $i < 8; $i++) {
					$k = $index + $param[$i][0] * $this->imgWidth + $param[$i][1];
					if($N[$k] == 255) {
						$c++;
						break;
					}
				}
				if($c==0) $N[$index] = 0;
			}
		}
		
		return $N;
	}

	public function TraceEdge($y, $x, $nThrLow, $N, $M) {
		$continue = true;
		$count = 0;
		$xNum = array(1,1,0,-1,-1,-1,0,1);
		$yNum = array(0,1,1,1,0,-1,-1,-1);

		while($continue) {
			$count++;
			if($count > 1000) break;

			$continue = false;
			$yy = $xx = 0;
			for($k=0; $k < 8; $k++) {
				$yy = $y + $yNum[$k];
				$xx = $x + $xNum[$k];

				$key = $yy*$this->imgHeight+$xx;

				if($N[$key]==128 && $M[$key] >= $nThrLow) {
					//该点设为边界点
					$N[$key] = 255;
					//以该点为中心再进行跟踪
					$continue = true;
					$x = $xx;
					$y = $yy;
				}
			}
		}
		return $N;
	}

	// 从 RGB 数据生成图片
	public function outputImage($imgData, $fileName = null) {
		ImageProcess::model()->outputImage($imgData, $this->imgWidth, $this->imgHeight);
	}
}