<?php
class ToolController extends Controller {
	public function actionIndex() {
		$data = LarkExtends::model()->findAll();
		
		$this->render('index', array('data'=>$data));
	}
}