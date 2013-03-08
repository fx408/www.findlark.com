<?php
class SController extends Controller {
	public $layout = 'sch.views.layout.main';
	
	public function beforeAction($action) {
		Yii::app()->session->open();
		Yii::app()->session->add('school_id', 1);
		return parent::beforeAction($action);
	}
	
}