<?php
class SchoolController extends SController {
	public function actionIndex() {
		
		
		$picList = SchoolPic::model()->picList;
		
		$this->render('index', array('picList'=>$picList));
	}
	
	public function actionPicker() {
		
		$this->render('picker');
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
			$model->status = 0;
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
			$model = SchoolPic::model();
			$form = $request->getParam('Form');
			try{
				$upload = PictureUpload::model()->uploadImage($_FILES['file']);
				$thumb = PictureUpload::model()->mkImageThumb($upload['dir'], $upload['name'], 400, 300);
				PictureUpload::model()->mkImageThumb($upload['dir'], $upload['name'], 45, 45, false, true);
				
				$model->id = null;
				$model->zone_id = empty($form['zone_id']) ? null : $form['zone_id'];
				$model->title = $form['title'];
				$model->school_id = $school_id;
				$model->thumb = $thumb;
				$model->name = $upload['name'];
				$model->path = str_replace(realpath(Yii::app()->basePath.'/../'), '', $upload['dir']);
				$model->isNewRecord = true;
				if(!$model->save()) {
					throw new Exception($this->getModelFirstError($model));
				}
				
			} catch(Exception $e) {
				$this->_end(1, $e->getMessage());
			}
			
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
			
			$model = SchoolNote::model();
			$model->attributes = $form;
			$model->id= null;
			$model->school_id = $school_id;
			$model->create_time = time();
			$model->create_user = 0;
			$model->status = 0;
			$model->isNewRecord = true;
			
			if(!$model->save()) {
				$this->_end(1, $this->getModelFirstError($model));
			}
			
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