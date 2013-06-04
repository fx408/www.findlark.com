<?php

class ImageUpload extends Image{
	public $allowPicType = array('image/jpg'=>'jpg', 'image/pjpeg'=>'jpg', 'image/jpeg'=>'jpg', 'image/png'=>'png', 'image/gif'=>'gif');
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	/**
	 * 上传文件
	 * @param $file 客户端提交的文件信息，格式等同于$_FILE['file']
	 * return array*(), 文件完整路径,目录,文件名(不含扩展名),扩展名
	 */
	public function uploadFile($file) {
		if(!isset($this->allowPicType[$file['type']])) {
			throw new Exception('File type not allowed!');
		}
		
		$fileExt = $this->allowPicType[$file['type']];
		$uploadDir = $this->makeDir();
		$fileName = $this->makeFileName();
		$filePath = $uploadDir.$fileName.'.'.$fileExt;
		
		# 创建文件
		if(!@ move_uploaded_file($file['tmp_name'], $filePath)) {
			throw new Exception('File upload failed!');
		}
		
		return array('path'=>$filePath, 'dir'=>$uploadDir, 'name'=>$fileName, 'ext'=>$fileExt);
	}
	
	/**
	 * 获取图片信息，直方图、exif等
	 */
	public function getImageInfo($file) {
		$this->fileExists($file);
		
		// 填充数组
		$arr = array_fill_keys(range(0, 255), 0);
		$histogram = array('gray'=> $arr, 'r'=> $arr, 'g'=> $arr, 'b'=> $arr, 'rgb'=> $arr);
		
		$image = new Imagick($file);
		$properties = $image->getImageProperties('*', true);
		unset($properties['exif:MakerNote'], $properties['exif:UserComment']);
		
		if($image->getImageWidth() > 1440) {
			$image->thumbnailImage(1440, 0);
			$image->writeImage($file);
			unset($image);
			$image = new Imagick($file);
		}
		$w = $image->getImageWidth();
		$h = $image->getImageHeight();
		$count = $w * $h;
		$pixels = $image->getImageHistogram();
		foreach($pixels as $p) {
			$rgb = $p->getColor();
			$colorCount = $p->getColorCount();
			
			$gray = $rgb['r'] * 0.3 + $rgb['g'] * 0.59 + $rgb['b'] * 0.11;
			$gray = round($gray);
			
			$histogram['gray'][$gray] += $colorCount;
			$histogram['r'][$rgb['r']] += $colorCount;
			$histogram['g'][$rgb['g']] += $colorCount;
			$histogram['b'][$rgb['b']] += $colorCount;
			$histogram['rgb'][$rgb['r']] += $colorCount/3;
			$histogram['rgb'][$rgb['g']] += $colorCount/3;
			$histogram['rgb'][$rgb['b']] += $colorCount/3;
		}
		
		foreach($histogram['rgb'] as $k => $v) {
			$histogram['rgb'][$k] = round($v);
		}
		
		// 计算
		foreach($histogram as $k => $arr) {
			$histogram[$k]['sum'] = array_sum($arr);
			$histogram[$k]['max'] = max($arr);
		}

		// 总像素
		$histogram['width'] = $w;
		$histogram['height'] = $h;
		$histogram['count'] = $count;
		// 阀值，取总像素的 1/64（参考PhotoShop）
		$histogram['valve'] = $count / 64;
		
		return array('histogram'=>$histogram, 'properties'=>$properties);
	}
	
	// 生成缩略图
	public function mkImageThumb($imageDir, $imageName) {
		$img = new Imagick($imageDir.$imageName);
		$img->thumbnailImage(460, 0);
		$img->writeImage($imageDir.'thumb/thumb460_'.$imageName);
		$img->thumbnailImage(0, 90);
		$img->writeImage($imageDir.'thumb/thumb90_'.$imageName);
		return $imageDir.'thumb/thumb90_'.$imageName;
	}
	
	public function getImageExif() {
		
		
	}
}