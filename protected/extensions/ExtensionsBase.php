<?php
class ExtensionsBase{
	private static $_models = array();
	private static $_data = array();
	
	public static function model($className = __CLASS__) {
		if(!isset(self::$_models[$className])) {
			self::$_models[$className] = new $className();
		}
		return self::$_models[$className];
	}
	
	public function __get($name) {
		$getter = '_get'.$name;
		$dater = '_data'.$name;
		
		if(method_exists($this, $getter)) {
			return $this->$getter();
		}
		
		return isset(self::$_data[$dater]) ? self::$_data[$dater] : null;
	}
	
	public function __set($name, $value) {
		
		
		
	}
}