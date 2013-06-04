<?php
class ImageProcess extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	/** ���ɻҶ� ͼ��
	 * ��׼�Ҷ�Ȩ��0.3*R + 0.59*G + 0.11*B
	 * OpenCV �Ҷ�Ȩ��0.212671*R + 0.715160*G + 0.072169*B
	 * Ҳ�в��þ�ֵ����(R + G + B)/3
	 */
	public function createGrayImage($img, $width = 0, $height = 0) {
		$height = $height ? $height : $img->getImageHeight();
		$width = $width ? $width : $img->getImageWidth();
		
		$gray = array();
		for($y=0; $y < $height; $y++) {
			for($x=0; $x < $width; $x++) {
				$rgb = $img->getImagePixelColor($x, $y)->getColor();
				$val = round($rgb['r'] * 0.3 + $rgb['g'] * 0.59 + $rgb['b'] * 0.11);

				$gray[$y*$width+$x] = $val;
			}
		}

		return $gray;
	}
	
	// ���ͼ��
	public function outputImage($imgData, $widht, $height, $saveDir = null, $fileName = null) {
		$img = new Imagick();
		$img->newImage($widht, $height, '#ffffff', 'jpg');

		$iterator = $img->getPixelIterator();
		foreach($iterator as $y => $pixels) {
			foreach($pixels as $x => $pixel) {
				$n = $imgData[$y*$widht+$x];
				$color = sprintf("rgb(%d,%d,%d)", $n, $n, $n);
				$pixel->setColor($color);
			}
			$iterator->syncIterator();
		}

		if(empty($fileName)) $fileName = time().rand(10001, 90009);
		if(!$saveDir) $saveDir = dirname(__FILE__).'/temp/';
		if(!file_exists($saveDir)) mkdir($saveDir, 0777, true);
		
		$img->writeImage($saveDir.$fileName.'.jpg');
		$img->destroy();
	}
}

