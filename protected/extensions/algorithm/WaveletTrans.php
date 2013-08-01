<?php
class WaveletTrans extends AlgorithmBase{
	protected $scale = 1;
	protected $halfWidth = 0;
	protected $halfHeight = 0;
	
	public static function model($img, $className = __CLASS__) {
		return parent::model($className, $img);
	}
	
	public function BasicWaveletTrans($scale = 2) {
		$this->scale = min(max(1, intval($scale)), 5);
		$this->halfWidth = floor($this->imgWidth/2);
		$this->halfHeight = floor($this->imgHeight/2);
		
		$imgDataOut = array();
		// int lineByte=(m_imgWidth*m_nBitCount/8+3)/4*4;
		$this->WavlDecmposeTrans($imgDataOut, $this->imgData, $this->imgWidth, $this->imgHeight);
		
		return $imgDataOut;
	}
	
	protected function WavlDecmposeTrans(&$imgDataOut, $inputData = array(), $imgWidth = 0, $imgHeight = 0) {
		$halfWidth = floor($imgWidth/2);
		$halfHeight = floor($imgHeight/2);
		
		//对图像矩阵进行行采样
		list($temp1, $temp2) = $this->MatrixSampleRow($inputData, $imgWidth, $halfHeight);
		//行差分，得到高频与低频数据
		$temp1 = $this->MatrixSub($temp1, $temp2, $imgWidth, $halfHeight);
		
		//对低频数据进行列采样
		list($temp00, $temp01) = $this->MatrixSampleLine($temp2, $halfWidth, $halfHeight);
		//列差分得到LL和LH
		$temp01 = $this->MatrixSub($temp01, $temp00, $halfWidth, $halfHeight);
		
		//对高频数据进行列采样
		list($temp11, $temp10) = $this->MatrixSampleLine($temp1, $halfWidth, $halfHeight);
		//列差分，得到HL和HH
		$temp11 = $this->MatrixSub($temp11, $temp10, $halfWidth, $halfHeight);
		
		$this->MatrixRegionCopy($temp01, $halfWidth, $halfHeight, $imgDataOut, $imgWidth*$halfHeight+$halfWidth, $imgWidth, $imgHeight);
		$this->MatrixRegionCopy($temp10, $halfWidth, $halfHeight, $imgDataOut, 0, $imgWidth, $imgHeight);
		$this->MatrixRegionCopy($temp11, $halfWidth, $halfHeight, $imgDataOut, $halfWidth, $imgWidth, $imgHeight);
		//释放空间
		unset($temp1, $temp2, $temp01, $temp10, $temp11);
		
		$this->scale--;
		//继续对LL进行递归分解
		if($this->scale > 0) {
			$this->WavlDecmposeTrans($temp00, $temp00, $halfWidth, $halfHeight);
		}
		$this->MatrixRegionCopy($temp00, $halfWidth, $halfHeight, $imgDataOut, $imgWidth*$halfHeight, $imgWidth, $imgHeight);
		//unset($temp00, $halfWidth, $halfHeight);
	}

	/**********************************************************************
	* 函数名称： MatrixSampleRow()
	* 参数： unsigned char *matrixInput 待采样矩阵数组指针
	*        unsigned char *matrixOutputOdd 奇数行采样生成的矩阵
	*        unsigned char *matrixOutputEven 偶数行采样生成的矩阵
	*        int heightOutput 输出矩阵高度
	*        int widthOutput 输出矩阵宽度
	*        int widthInput 输入矩阵宽度
	* 返回值： void
	* 说明： 对输入矩阵进行行抽点采样
	 **********************************************************************/
	public function MatrixSampleRow($imgData, $width, $height) {
		for($h = 0; $h < $height; $h++) {
			for($w = 0; $w < $width; $w++) {
				$key = $h * $width + $w;
				
				$odd[$key] = $imgData[$key + $h * $width];
				$even[$key] = $imgData[$key + ($h + 1) * $width];
			}
		}
		
		return array($odd, $even);
	}


/**********************************************************************
* 函数名称： MatrixSub()
* 参数： unsigned char *matrixA 待求差矩阵A数组指针
*        unsigned char *matrixB 待求差矩阵B数组指针
*        int height 高度
*        int width 宽度
*        unsigned char *result 差矩阵数组指针
* 返回值： void
* 说明： 将输入的两个矩阵A和B求差
**********************************************************************/
public function MatrixSub($matrixA, $matrixB, $width, $height) {
	$result = array();
	
	for($h = 0; $h < $height; $h++) {
		for($w=0; $w < $width; $w++) {
			$key = $h * $width + $w;
			$result[$key] = $matrixA[$key] - $matrixB[$key] + 128;
		}
	}
	
	return $result;
}

/**********************************************************************
 * 函数名称： MatrixSampleLine()
 * 参数： unsigned char *matrixInput 待采样矩阵数组指针
 *        unsigned char *matrixOutputOdd 奇数列采样生成的矩阵
 *        unsigned char *matrixOutputEven 偶数列采样生成的矩阵
 *        int heightOutput 输出矩阵高度
 *        int widthOutput 输出矩阵宽度
 *        int widthInput 输入矩阵宽度
 * 返回值：void
 * 说明： 对输入矩阵进行列抽点采样
 **********************************************************************/
public function MatrixSampleLine($matrixInput, $widthOutput, $heightOutput) {
	for ($h=0; $h < $heightOutput; $h++) {
		for ($w = 0; $w < $widthOutput; $w++) {
			$key = $h * $widthOutput + $w;
			$even[$key] = $matrixInput[$h * $this->imgWidth + $w * 2];
			$odd[$key] = $matrixInput[$h * $this->imgWidth + $w * 2 + 1];
		}
	}
	
	return array($odd, $even);
}


/**********************************************************************
 * 函数名称： MatrixPlus()
 * 参数： unsigned char *matrixA 待求和矩阵A数组指针
 *        unsigned char *matrixB 待求和矩阵B数组指针
 *        int height 高度
 *        int width 宽度
 *        unsigned char *result 和矩阵数组指针
 * 返回值：void
 * 说明： 将输入的两个矩阵A和B求和
 **********************************************************************/
public function MatrixPlus($matrixA, $matrixB, $width, $height) {
	$result = array();
	for ($h=0; $h < $height; $h++) {
		for ($w=0; $w < $width; $w++) {
			$key = $h * $width + $w;
			
			$result[$key] = $matrixA[$key] + $matrixB[$key] - 128;
		}
	}
	
	return $result;
}

/**********************************************************************
* 函数名称：
* MatrixRegionCopy()
* 参数： unsigned char *matrixRegion 源矩阵数组指针
*        int heightRegion 复制区域的高度
*        int widthRegion 复制区域的宽度
*        unsigned char *matrixDest 目的矩阵数组指针
*        int heightDest 目的矩阵区域的高度
*        int widthDest 目的矩阵区域的宽度
* 返回值：void
* 说明： 将源矩阵的指定区域数据复制到目的矩阵的指定区域
**********************************************************************/
public function MatrixRegionCopy($matrixRegion, $widthRegion, $heightRegion,
																&$matrixDest, $offset, $widthDest, $heightDest) {
	//计算区域高度和宽度
	$heightMin = min($heightRegion, $heightDest);
	$widthMin = min($widthRegion, $widthDest);
	for($h = 0; $h < $heightMin; $h++) {
		for($w = 0; $w < $widthMin; $w++) {
			$key = $h * $widthDest + $w;
			$matrixDest[$key+$offset] = $matrixRegion[$key];
		}
	}
}
		
}