<?php
class CityCommand extends CConsoleCommand {
	
	public function actionImportCity() {
		$cityFile = dirname(__FILE__).'/city.txt';
		
		$fp = fopen($cityFile, 'r');
		$model = LarkCity::model();
		while(!feof($fp)) {
			$line = fgets($fp);
			$arr = explode('|', $line);
			
			$data = $model->findByPk($arr[5]);
			if($data) {
				$data->name = $arr[1];
				$data->code = $arr[2];
				$data->pinyin = $arr[3];
				$data->short_pinyin = $arr[0];
				$data->save();
			} else {
				$model->id = $arr[5];
				$model->name = $arr[1];
				$model->code = $arr[2];
				$model->pinyin = $arr[3];
				$model->short_pinyin = $arr[0];
				$model->isNewRecord = true;
				$model->save();
			}
		}
		
		echo '->END;';
	}
	
	public function actionSearch() {
		$citys = $this->cityList();
		
		$i = 0;
		foreach($citys as $fromId => $fromCode) {
			foreach($citys as $toId => $toCode) {
				if($fromId == $toId) continue;
				
				printf("Search from: %d to: %d \n", $fromId, $toId);
				
				$i++;
				$sleepTime = ($i = $i % 100) == 0 ? 10 : 1;
				sleep($sleepTime);
				
				$content = $this->search($fromCode, $toCode);
				
				if($content === false) {
					echo "ERROR:--- \n";
					sleep(60);
				}
				
				$data = $this->parseContent($content);
				if($data) $this->saveData($data, $fromId, $toId);
			}
		}
		
		echo '->END;';
	}
	
	private function cityList() {
		$data = LarkCity::model()->findAll();
		
		$list = array();
		foreach($data as $item) {
			$list[$item->id] = $item->code;
		}
		
		return $list;
	}
	
	private $requestUrl = 'http://dynamic.12306.cn/otsquery/query/queryRemanentTicketAction.do';
	private $requestParams = array(
		'method'=>'queryLeftTicket',
		'orderRequest.train_date'=> 0,
		'orderRequest.from_station_telecode'=> '', // FORM
		'orderRequest.to_station_telecode' => '',  // TO
		'orderRequest.train_no'=> '',
		'trainPassType'=>'QB',
		'trainClass'=>'QB#D#Z#T#K#QT#',
		'includeStudent'=>'00',
		'seatTypeAndNum'=>'',
		'orderRequest.start_time_str'=>'00:00--24:00'
	);
	
	private function search($fromCode, $toCode) {
		$this->requestParams['orderRequest.train_date'] = date('Y-m-d', time()+86400);
		$this->requestParams['orderRequest.from_station_telecode'] = $fromCode;
		$this->requestParams['orderRequest.to_station_telecode'] = $toCode;
		
		$params = array('referer'=>'https://www.google.com.hk');
		
		$url = $this->requestUrl.'?'.http_build_query($this->requestParams);
		$content = Curl::model()->request($url, $params);
		$content = preg_replace("#.*?\{#is", "{", $content);
		$content = CJSON::decode($content);
		
		return isset($content['datas']) ? $content['datas'] : false;
	}
	
	private function parseContent($content) {
		if(empty($content)) return false;
		if(strpos($content, '\\n') === false) return false;
		
		$arr1 = explode('\\n', $content);
		
		$times = array();
		foreach($arr1 as $item) {
			$item = explode(',', $item);
			if(isset($item[4])) {
				$temp = explode(':', $item[4]);
				$times[] = intval($temp[0]) * 60 + intval($temp[1]);
			}
		}
		if(empty($times)) return false;
		sort($times);
		
		$content = preg_replace("#\<.*?\>#", "", $content);
		return array(array_pop($times), $content);
	}
	
	private function saveData($data, $fromId, $toId) {
		$model = LarkTime::model();
		
		$criteria=new CDbCriteria;
		$criteria->compare('from_id', $fromId);
		$criteria->compare('to_id', $toId);
		$check = $model->find($criteria);
		if(!empty($check)) {
			$check->time = $data[0];
			$check->content = $data[1];
			$check->save();
		} else {
			$model->id = null;
			$model->time = $data[0];
			$model->content = $data[1];
			$model->from_id = $fromId;
			$model->to_id = $toId;
			
			$model->isNewRecord = true;
			if(!$model->save()) echo "save failed!".var_export($model->getErrors())." \n";
		}
	}
}
