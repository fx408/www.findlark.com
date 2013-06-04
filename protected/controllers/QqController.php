<?php
class QqController extends Controller {
	
	public function actionIndex() {
		$qc = new QC();
		$qc->qq_login();
	}
	
	public function actionCallback() {
		$qc = new QC();
		echo $qc->qq_callback();
		echo '<br>';
		echo $qc->get_openid(); // 05445C016B711A698FD38832BED90140
	}
	
	public function actionCenter() {
		$qc = new QC();
		// w2(*IF&*UF)WFW1
		echo '<pre>';
		print_r($qc->get_user_info());
	}
}