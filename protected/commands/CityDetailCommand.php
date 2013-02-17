<?php
class TimeDetailCommand extends CConsoleCommand {
	
	public function actionImportCity() {
		$id = 0;
		while(true) {
			$criteria=new CDbCriteria;
			$criteria->compare('id', '>'.$id);
			$criteria->order = '`id` ASC';
			$data = LarkTime::model()->find($criteria);
			
			if(empty($data)) break;
			
			$id = $data->id;
			$this->parseContent($data);
			usleep(100);
		}
		
		echo '->END;';
	}

	private function parseData($data) {
		$arr1 = explode('\\n', $data->content);
		
		$params = array('tid'=> $data->id);
		
		foreach($arr1 as $item) {
			$item = explode(',', $item);
			
			$params['time'] = $this->parseTime($item[4]);
			$params['number'] = trim($temp[1]);
			$params['start_time'] = $this->parseTime($item[2]);
			$params['finish_time'] = ($params['time'] + $params['start_time']) % 1440;
			
			$this->saveData($params);
		}
	}
	
	// 字符串转时间，(分钟)
	private function parseTime($string) {
		$match = preg_match("#(\d+)\:(\d+)#", $string, $arr);
		$time = $match ? intval($arr[0]) * 60 + intval($arr[1]) : 0;
		return $time;
	}
	
	// 保存数据
	private function saveData($params) {
		$model = LarkTimeDetail::model();
		
		$criteria=new CDbCriteria;
		$criteria->compare('tid', $params['tid']);
		$criteria->compare('number', $params['number']);
		$data = $model->find($criteria);
		
		if(!empty($data)) {
			$data->attributes = $params;
			if(!$data->save()) echo "Update failed!".var_export($data->getErrors())." \n";
		} else {
			$model->attributes = $params;
			$model->id = null;
			$model->isNewRecord = true;
			if(!$model->save()) echo "Insert failed!".var_export($model->getErrors())." \n";
		}
	}
}
