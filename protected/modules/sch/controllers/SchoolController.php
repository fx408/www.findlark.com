<?php
class SchoolController extends SController {
	public function actionIndex() {
		
		
	}
	
	public function beforeAction($action) {
		Yii::app()->session->open();
		
		return parent::beforeAction($action);
	}
	
	// 添加学校
	public function actionAdd() {
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			$model = School::model();
			$model->attributes = $request->getParam("Form");
			$model->id = null;
			if($model->type < 1) $model->type = null;
			$model->create_time = time();
			$model->isNewRecord = true;
			if(!$model->save()) {
				$this->_end(1, $this->getModelFirstError($model));
			}
			
			$link = $request->getParam("Link");
			$linkModel = SchoolLink::model();
			foreach($link['title'] as $k => $title) {
				if(empty($title) || empty($link['url'][$k])) continue;
				
				$linkModel->id = null;
				$linkModel->title = $title;
				$linkModel->url = 'http://'.$link['url'][$k];
				$linkModel->isNewRecord = true;
				$linkModel->save();
			}
			
			Yii::app()->session->add('school_id', $model->id);
			$this->_end(0, "", array('url'=>'/sch/school/addZone'));
		}
		
		$this->render('add');
	}
	
	// 添加校区
	public function actionAddZone() {
		$school_id = Yii::app()->session->get('school_id');
		if(!$school_id) throw new CHttpException(404);
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			$form = $request->getParam('Form');
			$model = SchoolZone::model();
			
			$model->attributes = $form;
			$model->school_id = $school_id;
			$model->id = null;
			$model->create_time = time();
			$model->create_user = 0;
			if($model->type < 1) $model->type = null;
			$model->isNewRecord = true;
			
			if(!$model->save()) {
				$this->_end(1, $this->getModelFirstError($model));
			}
			
			$action = $form['continue'] ? 'addZone' : 'addPic';
			$this->_end(0, "信息为空！", array('url'=>'/sch/school/'.$action));
		}
		
		$this->render('add_zone');
	}
	
	// 添加图片
	public function actionAddPic() {
		$school_id = Yii::app()->session->get('school_id');
		if(!$school_id) throw new CHttpException(404);
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			
			sleep(2);
			
			$this->_end(0, '成功!', array('url'=>'/upload/abc.jpg'));
		}
		
		$this->render('add_pic');
	}
	
	// 添加记事
	public function actionAddNote() {
		$school_id = Yii::app()->session->get('school_id');
		if(!$school_id) throw new CHttpException(404);
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			$form = $request->getParam('Form');
			
			
			
			$action = $form['continue'] ? 'addNote' : 'success';
			$this->_end(0, "信息为空！", array('url'=>'/sch/school/'.$action));
		}
		
		$this->render('add_note');
	}
	
	public function actionCorrect($id) {
		
		
	}
	
	public function actionUpload() {
		
		
	}
	
	public function actionSuccess() {
		
	}
}