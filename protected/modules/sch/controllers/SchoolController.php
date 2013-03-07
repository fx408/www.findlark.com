<?php
class SchoolController extends SController {
	public function actionIndex() {
		
		
	}
	
	// 添加学校
	public function actionAdd() {
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			
			
			
			$this->_end(0, "信息为空！", array('url'=>'/sch/school/addZone'));
		}
		
		$this->render('add');
	}
	
	// 添加校区
	public function actionAddZone() {
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			$form = $request->getParam('Form');
			
			
			
			$action = $form['continue'] ? 'addZone' : 'addPic';
			$this->_end(0, "信息为空！", array('url'=>'/sch/school/'.$action));
		}
		
		$this->render('add_zone');
	}
	
	// 添加图片
	public function actionAddPic() {
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			
			
			
			$this->redirect('/sch/school/addZone');
		}
		
		$this->render('add_pic');
	}
	
	// 添加记事
	public function actionAddNote() {
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			
			
			
		}
		
		$this->render('add_note');
	}
	
	
	
	public function actionCorrect($id) {
		
		
	}
	
	public function actionUpload() {
		
		
	}
	
}