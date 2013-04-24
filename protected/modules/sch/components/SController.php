<?php
class SController extends Controller {
	
	public function beforeAction($action) {
		Yii::app()->session->open();

		return parent::beforeAction($action);
	}
	
}