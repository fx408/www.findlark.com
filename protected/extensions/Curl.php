<?php
class Curl extends ExtensionsBase{
	protected $ip = null;
	protected $userAgent = null;
	protected $cookie = null;
	
	protected $default = array(
		'data'=> array(),
		'type'=> 'get',
		'useCookie'=> false,
		'referer'=>'http://www.findlark.com',
		'https' => false,
		'header'=> false,
		'transfer'=> true,
		'timeout'=> 10
	);
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function __set($name, $value) {
		if(isset($this->default[$name])) {
			return $this->default[$name] = $value;
		}
		
		return parent::__set($name, $value);
	}
	
	public function __get($name) {
		return isset($this->default[$name]) ? $this->default[$name] : parent::__get($name);
	}
	
	// 默认参数设置
	public function setDefault($mixed, $value = null) {
		if(is_array($mixed)) {
			foreach($mixed as $key => $val) {
				if(isset($this->default[$key])) $this->default[$key] = $val;
			}
		} else if(is_string($mixed)) {
			if(isset($this->default[$mixed])) $this->default[$mixed] = $value;
		}
		
		return false;
	}
	
	// 请求
	public function request($url, $params = array()) {
		$params = array_merge($this->default, $params);
		$ip = $this->createIp();

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $params['timeout']);
		curl_setopt($ch, CURLOPT_REFERER, $params['referer']);
		curl_setopt($ch, CURLOPT_HEADER, $params['header']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $params['transfer']);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->createAgent());
    curl_setopt($ch, CURLOPT_HTTPHEADER , array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));

    if(true == $params['useCookie']) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->createCookie());
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->createCookie());
		}
		
		if(true == $params['https']) {
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			
			// 证书地址： http://curl.haxx.se/ca/cacert.pem
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_CAINFO, Yii::app()->basePath.'/../source/cacert.pem');
		}

		if('post' == strtolower($params['type'])) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params['data']);
		} else {
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}

		$contents = curl_exec($ch);
		curl_close($ch);

		return $contents;
	}
	
	// 创建 访问IP
	public function createIp($refresh = false) {
		if(empty($this->ip) || $refresh) {
		 $this->ip = rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);
		}
		
		return $this->ip;
	}
	
	// 创建 浏览器信息
	public function createAgent($refresh = false) {
		if(empty($this->agent) || $refresh) {
			switch(rand(0,2)) {
				case 0: $agent = 'Mozilla/'.rand(4,5).'.0 (compatible; MSIE '.rand(6,10).'.0; Windows NT '.rand(5,6).'.2; .NET CLR 1.1.'.rand(11,55).'22)'; break;
				case 1: $agent = 'Mozilla/'.rand(4,5).'.0 (Windows NT '.rand(5,6).'.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/'.rand(15,28).'.0.912.'.rand(50,66).' Safari/535.7';break;
				case 2: $agent = 'Mozilla/'.rand(4,5).'.0 (X11; U; Linux i686; en-US; rv:1.'.rand(3,9).'.5) Gecko/'.rand(2004,2012).''.rand(10,12).''.rand(10,28).' Firefox/'.rand(1,7).'.0';break;
			}
			
			$this->agent = $agent;
		}

		return $this->agent;
	}
	
	// 创建 cookie 临时文件
	public function createCookie($refresh = false) {
		if(empty($this->cookie) || $refresh) {
			$this->cookie = $this->cookieDir.'/'.tempnam($baseDir, 'cookie');
		}
		
		return $this->cookie;
	}
	
	// 设置 访问IP
	public function _setIp($ip) {
		return $this->ip = $ip;
	}
	
	// 设置 浏览器信息
	public function _setAgent($agent) {
		return $this->agent = $agent;
	}
	
	// 设置 cookie 文件名称
	public function _setCookie($cookie) {
		$cookie = $this->cookieDir.'/'.$cookie;
		if(!file_exists($cookie)) file_put_contents($cookie, '');
		
		return $this->cookie = $cookie;
	}
	
	// 获取 Cookie 存放目录
	public function _getCookieDir() {
		$dir = Yii::app()->basePath.'/../tmp_cookie';
		if(!file_exists($dir)) mkdir($dir, 0755, true);
		
		return $dir;
	}
	
	/**
	 * 按正则匹配页面内容
	 * @param String $url 页面的URL地址
	 * @param String $regular 匹配链接的正则
	 * @param Array $params 参数
	 * return Array 正则匹配的结果
	 */
	public function matchContent($url, $regular, $params = array()) {
		$params['header'] = 0;
		$content = Curl::model()->request($url, $params);

		$match = preg_match_all($regular, $content, $result);
		return $match ? $result : false;
	}
	
	// 批量请求
	public function multipleRequest($nodes, $params = array()) {
		$params = array_merge($this->default, $params);
		$node_count = count($nodes);
	
		$curl_arr = array();
		$master = curl_multi_init();
		
		for($i = 0; $i < $node_count; $i++) {
			$curl_arr[$i] = curl_init($nodes[$i]);
			curl_setopt($curl_arr[$i], CURLOPT_HEADER, $params['header']);
			curl_setopt($curl_arr[$i], CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl_arr[$i], CURLOPT_FRESH_CONNECT, true);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_arr[$i], CURLOPT_REFERER, $params['referer']);
			curl_setopt($curl_arr[$i], CURLOPT_HTTPGET, 1);
			curl_setopt($curl_arr[$i], CURLOPT_USERAGENT, $this->createAgent());
	    curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER , array('X-FORWARDED-FOR:'.$this->createIp(), 'CLIENT-IP:'.$this->createIp()));  //构造IP
			curl_setopt($curl_arr[$i], CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl_arr[$i], CURLOPT_TIMEOUT, 30);
			
			curl_multi_add_handle($master, $curl_arr[$i]);
		}

		$previousActive = -1;
		$finalResult = array();
		$returnedOrder = array();
		do{
			curl_multi_exec($master, $running);
			if($running !== $previousActive) {
				$info = curl_multi_info_read($master);
				if($info && isset($info['handle'])) {
					$finalResult[] = curl_multi_getcontent($info['handle']);
					$returnedOrder[] = array_search($info['handle'], $curl_arr, true);
					curl_multi_remove_handle($master, $info['handle']);
					curl_close($curl_arr[end($returnedOrder)]);
					// echo 'downloaded '.$nodes[end($returnedOrder)].'. We can process it further straight away, but for this example, we will not.';
					ob_flush();
					flush();
				}
			}
			$previousActive = $running;
		} while($running > 0);
		curl_multi_close($master);
		
		return array_combine($returnedOrder, $finalResult);
	}
}
?>