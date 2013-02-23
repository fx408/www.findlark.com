<?php
class Qiyi extends ExtensionsBase{
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	//
	public function findBySimple($fromCityId, $toCityId) {
		$model = LarkTime::model();
		$fromCitys = $this->getCityById($fromCityId);
		$toCitys = $this->getCityById(null, $toCityId);
		
		$times = array();
		
		foreach($fromCitys as $from) {
			
			if($from->to_id == $toCityId) {
				$times[] = array(
					'from'=> $from->attributes,
					'to'=> null,
					'time' = $from->time
				);
				continue;
			}
			
			foreach($toCitys as $to) {
				if($to->from_id == $from->to_id) {
					$times[] = array(
						'from'=> $from->attributes,
						'to'=> $to->attributes,
						'time' = $from->time + $to->time
					);
					
					break;
				}
			}
			
		}
		
		
	}
	
	//
	public function findByDetail($fromCityId, $toCityId) {
		
		
		
		
	}
	
	
	private function getCityById($fromId = null, $toId = null) {
		$criteria=new CDbCriteria;
		if($fromId !== null) $criteria->compare('from_id', $fromId);
		if($toId !== null) $criteria->compare('to_id', $toId);
		
		$list = array();
		$data = LarkTime::model()->findAll($criteria);
		foreach($data as $item) {
			
			//$list[]
		}
		
		return $data;
	}
	
	
}