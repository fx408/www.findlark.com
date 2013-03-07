<?php

class ImageUpload extends Image{
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
	
	// 生成缩略图
	public function mkImageThumb($imageDir, $imageName, $newWidth, $newHeight) {
		$newWidth = intval($newWidth);
		$newHeight = intval($newHeight);
		
		$img = new Imagick($imageDir.$imageName);
		$img->thumbnailImage($newWidth, $newHeight);
		
		$thumb = sprintf("%sthumb/thumb%d_%d_%s", $imageDir, $newWidth, $newHeight, $imageName);
		
		$img->writeImage($thumb);
		return $thumb;
	}
}