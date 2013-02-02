<?php
class ToolController extends AdminController {
	
	public function actionIndex() {
		$data = $this->getList(LarkExtends::model());
		
		$this->render('index', array('data'=>$data));
	}
	
	public function actionAdd() {
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			$model = LarkExtends::model();
			$model->attributes = $request->getParam('Form');
			$model->id = null;
			$model->isNewRecord = true;
			$model->save();
			
			$this->redirect('/admin/tool/add');
		}
		
		$this->render('add');
	}
	
	public function acitonDel($id) {
		LarkExtends::model()->deleteByPk($id);
		
		$this->redirect('/admin/tool/index');
	}
	
	public function actionModify($id) {
		$data = LarkExtends::model()->findByPk($id);
		if(empty($data)) {
			echo 'ERROR ID!';
			Yii::app()->end();
		}
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			$data->title = $request->getParam('title');
			$data->path = $request->getParam('path');
			$data->thumb = $request->getParam('thumb');
			$data->save();
			
			$this->redirect('/admin/tool/index');
		}
		
		$this->render('modify', array('data'=>$data));
	}
}