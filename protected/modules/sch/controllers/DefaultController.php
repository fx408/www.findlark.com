<?php

class DefaultController extends Controller{
	public function actionIndex() {
		$this->render('index');
	}
	
	public function actionCitys($parent_id = 0) {
		$parent_id = intval($parent_id);
		
		$list = Citys::model()->findAll('parent_id='.$parent_id);
		$data = array();
		
		foreach($list as $item) {
			$data[$item->id] = $item->name;
		}
		
		echo CJSON::encode($data);
	}
	
	
}