<?php

class PictureUpload extends Image{
	protected $maxWidth = 1024;
	protected $maxHeight = 768;
	protected $thumbDirName = 'thumb';
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	/**
	 * 上传文件
	 * @param $file 客户端提交的文件信息，格式等同于$_FILE['file']
	 * return array*(), 文件完整路径,目录,文件名(不含扩展名),扩展名
	 */
	public function uploadImage($file) {
		if($file['error'] != 0)
			throw new Exception('文件上传错误!');

		if(!preg_match("#^image/\w+$#", $file['type']))
			throw new Exception('文件类型错误!');
		
		$fileExt = 'jpg';
		$uploadDir = $this->makeDir();
		$fileName = $this->makeFileName();
		$filePath = $uploadDir.$fileName.'.'.$fileExt;
		
		// 创建文件
		if(!@ move_uploaded_file($file['tmp_name'], $filePath)) {
			throw new Exception('文件上传失败!');
		}
		
		$this->mkImageThumb($uploadDir, $fileName.'.'.$fileExt, 0, 0, true);
		
		return array('path'=>$filePath, 'dir'=>$uploadDir, 'name'=>$fileName.'.'.$fileExt);
	}
	
	/**
	 * 生成缩略图
	 * @param $imageDir 图片所在目录
	 * @param $imageName 图片完整名称
	 * @param $newWidth 新的高度
	 * @param $newHeight 新的宽度
	 * @param $original 是否覆盖原图，若为 true 则覆盖原图，并不生成缩略图
	 * return 新图完整路径
	 */
	public function mkImageThumb($imageDir, $imageName, $newWidth = 0, $newHeight = 0, $original = false, $fixed = false) {
		try{
			$img = new Imagick($imageDir.$imageName);
		} catch(Exception $e) {
			@ unlink($imageDir.$imageName);
			throw new Exception('不支持的文件类型!');
		}
		$newWidth = intval($newWidth);
		$newHeight = intval($newHeight);
		
		if(!$fixed) {
			$newWidth = min($this->maxWidth, $newWidth);
			$newHeight = min($this->maxHeight, $newHeight);
			
			if($newWidth == 0 && $newHeight == 0) {
				$newWidth = $this->maxWidth;
				$newHeight = $this->maxHeight;
			}
			
			$imgWidth = $img->getImageWidth();
			$imgHeight = $img->getImageHeight();
			
			$r_w = $imgWidth / $newWidth;
			$r_h = $imgHeight / $newHeight;
			
			if($r_w <= 1 && $r_h <= 1) {
				$newWidth = $imgWidth;
				$newHeight = $imgHeight;
			} else if($r_w > $r_h) {
				$newHeight = $imgHeight / $r_w;
			} else {
				$newWidth = $imgWidth / $r_h;
			}
		}
		
		$img->thumbnailImage($newWidth, $newHeight);
		
		$newImg = $original ? $imageDir.$imageName : sprintf("%s%s/%s_%d_%d_%s", 
			$imageDir, $this->thumbDirName, $this->thumbDirName, $newWidth, $newHeight, $imageName);
		
		$img->writeImage($newImg);
		$img->clear();
		$img->destroy();
		return sprintf("%s_%d_%d_%s", $this->thumbDirName, $newWidth, $newHeight, $imageName);
	}
}