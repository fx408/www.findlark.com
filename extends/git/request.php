<?php
error_reporting(E_ALL);
set_time_limit(0);
session_start();

class Base{
	private static $_models = array();
	
	public static function model($className = __CLASS__) {
		if(!isset(self::$_models[$className])) {
			self::$_models[$className] = new $className();
		}
		return self::$_models[$className];
	}
}

class Request extends Base{
	public static $refrere = 'http://www.google.com';
	public $userAgent = '';
	public $userIp = '';
	public $userCookie = '';
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	// 发送CURL请求
	public function sendCURLRequest($url, $data=null, $type='get') {
		$cookie = $this->getUserCookie();
		$ip = $this->getUserIp();
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_REFERER, self::$refrere);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
    curl_setopt($ch, CURLOPT_HTTPHEADER , array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		
		if($type == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		
		$contents = curl_exec($ch);
		curl_close($ch);
		
		return $contents;
	}
	
	// 生成用户IP
	public function getUserIp() {
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'temp';
		
		if(!isset($this->userIp[$username])) {
			$this->userIp[$username] = rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);
		}
		
		return $this->userIp[$username];
	}
	
	// 生成用户头信息
	public function getUserAgent() {
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'temp';
		if(!isset($this->userAgent[$username])) {
			switch(rand(0,2)) {
				case 0: $agent = 'Mozilla/'.rand(4,5).'.0 (compatible; MSIE '.rand(6,9).'.0; Windows NT '.rand(5,6).'.2; .NET CLR 1.1.'.rand(11,55).'22)'; break;
				case 1: $agent = 'Mozilla/'.rand(4,5).'.0 (Windows NT '.rand(5,6).'.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/'.rand(10,16).'.0.912.'.rand(50,66).' Safari/535.7';break;
				case 2: $agent = 'Mozilla/'.rand(4,5).'.0 (X11; U; Linux i686; en-US; rv:1.'.rand(3,9).'.5) Gecko/'.rand(2004,2012).''.rand(10,12).''.rand(10,28).' Firefox/'.rand(1,7).'.0';break;
			}
			
			$this->userAgent[$username] = $agent;
		}
		
		return $this->userAgent[$username];
	}
	
	public function getUserCookie() {
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'temp';
		
		if(!isset($this->userCookie[$username])) {
			$dir = dirname(__FILE__).'/cookie/'.'cookie_'.$username;
			if(!file_exists($dir)) mkdir($dir, 0777, true);
			$this->userCookie[$username] = tempnam($dir, 'cookie');
		}
		
		return $this->userCookie[$username];
	}
}