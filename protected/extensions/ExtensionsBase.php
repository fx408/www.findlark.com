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
	
	public function __get($property) {
		$property = ucfirst($property);
		
		if(method_exists($this, '_get'.$property)) {
			return call_user_func(array($this, '_get'.$property));
		}
		
		return isset(self::$_data['_data'.$property]) ? self::$_data['_data'.$property] : null;
	}
}