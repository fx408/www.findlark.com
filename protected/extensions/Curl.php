<?php
class Curl extends ExtensionsBase{
	private $ip = null;
	private $userAgent = null;
	private $default = array(
		'data'=>null,
		'type'=>'get',
		'useCookie'=>false,
		'referer'=>'http://www.findlark.com',
		'header'=>0,
		'transfer'=>1
	);

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	// 请求
	public function request($url, $params = array()) {
		$params = array_merge($this->default, $params);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_REFERER, $params['referer']);
		curl_setopt($ch, CURLOPT_HEADER, $params['header']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $params['transfer']);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->createAgent());
    curl_setopt($ch, CURLOPT_HTTPHEADER , array('X-FORWARDED-FOR:'.$this->createIp(), 'CLIENT-IP:'.$this->createIp()));  //构造IP

    if($params['useCookie']) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->createCookie());
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->createCookie());
		}

		if($params['type'] == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params['data']);
		} else {
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}

		$contents = curl_exec($ch);
		curl_close($ch);

		return $contents;
	}
	
	// 生成 IP
	public function createIp($refresh = false) {
		return rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);
	}
	
	// 生成 头信息
	public function createAgent($refresh = false) {
		switch(rand(0,2)) {
			case 0: $agent = 'Mozilla/'.rand(4,5).'.0 (compatible; MSIE '.rand(6,10).'.0; Windows NT '.rand(5,6).'.2; .NET CLR 1.1.'.rand(11,55).'22)'; break;
			case 1: $agent = 'Mozilla/'.rand(4,5).'.0 (Windows NT '.rand(5,6).'.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/'.rand(15,21).'.0.912.'.rand(50,66).' Safari/535.7';break;
			case 2: $agent = 'Mozilla/'.rand(4,5).'.0 (X11; U; Linux i686; en-US; rv:1.'.rand(3,9).'.5) Gecko/'.rand(2004,2012).''.rand(10,12).''.rand(10,28).' Firefox/'.rand(1,7).'.0';break;
		}

		return $agent;
	}
	
	//
	public function createCookie() {


	}
	
	/**
	 * 按正则匹配页面内容
	 * @param String $url 页面的URL地址
	 * @param String $regular 匹配链接的正则
	 * return Array 正则匹配的结果
	 */
	public function matchContent($url, $regular) {
		if(!Urls::model()->saveUrl($url)) return false;
		
		$content = Curl::model()->request($url, array('header'=>0));
		
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
		$finalresult = array();
		$returnedOrder = array();
		do{
			curl_multi_exec($master, $running);
			if($running !== $previousActive) {
				$info = curl_multi_info_read($master);
				if($info['handle']) {
					$finalresult[] = curl_multi_getcontent($info['handle']);
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
		
		return array_combine($returnedOrder, $finalresult);
	}
}
?>