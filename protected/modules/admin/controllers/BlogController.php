<?php
class BlogController extends AdminController {
	
	public function actionIndex() {
		$data = $this->getList(LarkNovel::model());
		
		$this->render('index', array('data'=>$data));
	}
	
	public function actionAdd() {
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			$model = LarkNovel::model();
			$model->attributes = $request->getParam('Form');
			$model->id = null;
			$model->isNewRecord = true;
			$model->save();
			
			$this->redirect('/admin/blog/add');
		}
		
		$this->render('add');
	}
	
	public function actionDel($id) {
		LarkNovel::model()->deleteByPk($id);
		
		$this->redirect('/admin/blog/index');
	}
	
	public function actionModify($id) {
		$data = LarkNovel::model()->findByPk($id);
		if(empty($data)) {
			echo 'ERROR ID!';
			Yii::app()->end();
		}
		
		$request = Yii::app()->request;
		if($request->isPostRequest) {
			
			$form = $request->getParam('Form');
			
			$data->title = $form['title'];
			$data->summary = $form['summary'];
			$data->content = $form['content'];
			$data->save();
			
			$this->redirect('/admin/blog/index');
		}
		
		$this->render('modify', array('data'=>$data));
	}
}