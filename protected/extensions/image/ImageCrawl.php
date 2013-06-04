<?php
/*
 * 图片抓取
 */
class ImageCrawl extends Image{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	// 从URL 截取扩展名
	public function getExtension($string) {
		$pos = strrpos($string, '.');
		if($pos === false) { // 注意: 三个等号
			return '.jpg';
		}
		
		return $this->getExtName(substr($string, $pos+1, strlen($string)));
	}
	
	/**
	 * 抓图图片 并保存图片信息到数据库
	 * @param String $imgUrl 图片的URL地址
	 * return void
	 */
	public function saveImg($imgUrl) {
		$params = ImageCrawl::model()->saveImageFromUrl($imgUrl);

		if($params) {
			$model = LarkPicture::model();
			$params['hash'] = ImageHash::model()->getImageHash($params['fullDir'].'/thumb/thumb_100_0_'.$params['name'].$params['ext']);
			
			$check = $model->find("`hash`='".$params['hash']."'");
			if(!empty($check)) {
				Image::model()->deleteImage($params['fullDir'], $params['name'].$params['ext']);
				return;
			}

			unset($params['fullDir'], $model->attributes);
			$model->attributes = $params;
			$model->title = $model->name;
			$model->original_url = $imgUrl;
			$model->timeline = time();
			$model->isNewRecord = true;
			$model->ext = substr($model->ext, 1);
			$model->id = null;
			$model->save();
		}
	}
	
	/*
	 * 保存图片到本地，从一个URL。宽度 或 高度小于100的图片将被跳过
	 * @param String $url 图片URL
	 * return Array, 包含图片的一些信息
	 */
	public function saveImageFromUrl($url) {
		$params = array();
		$fileDir = $this->makeDir('download');
		
		$string = Curl::model()->request($url, array('header'=>0));
		
		$img = new Imagick();
		if($string && $img->readImageBlob($string)) {
			$fileExt = $this->getExtension($url);
			$fileName = $this->makeFileName();
			$params['width'] = $img->getImageWidth();
			$params['height'] = $img->getImageHeight();
			
			if($params['width'] < 100 || $params['height'] < 100) return false;
			
			$img->writeImage($fileDir.$fileName.$fileExt);
			for($i = 0; $i < 4; $i++) {
				if(($params['width'] - $i*100 - 100) < 0) {
					break;
				}
				
				$this->makeThumb($fileDir, $fileName.$fileExt, 100+$i*100);
			}
			$img->destroy();

			$basePath = realpath(Yii::app()->basePath.'/../');
			$params['fullDir'] = $fileDir;
			$params['dir'] = str_replace($basePath, '', $fileDir);
			$params['name'] = $fileName;
			$params['ext'] = $fileExt;
			return $params;
		}
		
		return false;
	}
}