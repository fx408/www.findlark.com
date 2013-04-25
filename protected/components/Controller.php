<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $pageDescription = 'FindLark, 旅行、摄影、编程、魔方、玩乐、分享，如是人生';
	public $pageKeywords = 'FindLark,BYFX,Lark';
	
	public function register() {
		$cs = Yii::app()->getClientScript();
		switch($this->id) {
			case 'site':
				$cs->registerCssFile(Yii::app()->params->staticUrl.'/css/index.css');
				$cs->registerScriptFile(Yii::app()->params->staticUrl.'/js/index.js');
				break;
			case 'home':
				
				break;
			
			case 'gallery': 
				break;
				
			case 'blog':
				$cs->registerCssFile(Yii::app()->params->staticUrl.'/css/blog.css');
				$cs->registerScriptFile(Yii::app()->params->staticUrl.'/js/shar.js');
				break;
				
			case 'tool':
				break;
			default: break;
		}
	}
	
	// end
	public function _end($error = 0, $msg = 'success!', $params = array()) {
		$arr = array('error'=>$error, 'msg'=>$msg, 'params'=>$params);
		echo CJSON::encode($arr);
		Yii::app()->end();
	}
	
	// 获取 Model 错误信息中的 第一条， 无错误时 返回 null
	public function getModelFirstError($model) {
		$errors = $model->getErrors();
		if(!is_array($errors)) return '';
		
		$firstError = array_shift($errors);
		if(!is_array($firstError)) return '';
		
		return array_shift($firstError);
	}
	
	protected function checkAjaxRequest() {
		if(Yii::app()->request->isAjaxRequest) return true;
		Yii::app()->end();
	}
	
	public function beforeAction($action) {
		$this->pageTitle = '';
		
		return parent::beforeAction($action);
	}
}