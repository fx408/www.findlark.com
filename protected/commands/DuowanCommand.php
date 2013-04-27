<?php

class DuowanCommand extends CConsoleCommand {
	protected $users = array('h_hha'=>'fx1989');
	protected $baseUrl = 'http://bbs.duowan.com';
	protected $fid = 1344;
	
	public function actionIndex() {
		$this->fid = 1344; // 设置板块
		
		foreach($this->users as $username => $password) {
			//$this->_run($username, $password);
		}
		
		$params = array(
			'header' => false,
			'useCookie' => true,
		);
		Curl::model()->createCookie('coo1DCF.tmp');
		Curl::model()->setDefault('referer', 'http://bbs.duowan.com');
		
		$result = Curl::model()->request('http://bbs.duowan.com/home.php?mod=spacecp', $params);
		$isLogin = strpos($result, 'mshing') !== false;
		
		if(!$isLogin) {
			throw new Exception("Cookie Time-Out!");
		}
		
		$this->_run();
	}
	
	protected function _run() {
		$log = Yii::app()->basePath.'/../tmp_cookie/tid_log.log';
		$string = @ file_get_contents($log);
		$string = preg_replace("#\s+#is", "", $string);
		
		$tids = explode(',', $string);
		$fp = fopen($log, 'a+');
		
		foreach($this->threadIds as $tid) {
			if(in_array($tid, $tids)) continue;
			if($this->send($tid)) {
				fwrite($fp, $tid.',');
				
				echo 'Success: '.$tid." \r\n";
			} else {
				echo 'Failed: '.$tid." \r\n";
			}
			
			sleep(60);
		}
		
		fclose($fp);
	}
	
	protected function login($username, $password) {
		$auth = $this->getLoginAuth();
		if(!$auth) throw new Exception("Get auth failed!");
		
		// Curl::model()->request($auth, array('header' => false, 'useCookie' => true));
		
		$auth = @ parse_url($auth);
		parse_str($auth['query'], $auth);
		
		$url = 'http://bbs.duowan.com/login.php?mod=getauthorizedurl';
		$params = array(
			'type' => 'post',
			'header' => false,
			'useCookie' => true,
			'https' => true,
			'data' => array(
				'username' => $username,
				'showpwd' => '密码',
				'password' => $password,
				'oauth_token' => $auth['oauth_token'],
				'denyCallbackURL' => $auth['denyCallbackURL'],
				'securityCode' => ''
			)
		);
		
		$result = Curl::model()->request('https://udb.duowan.com/oauth/server/login_q.do', $params);
		
		//echo str_replace(array('location', 'invokeModelAttrWork'), '--', $result);
		echo $result;
		exit();
		
		$try = preg_match("#var\s+callbackURL\s+\=\s+\"(.*?)\"\;#", $result, $matchs);
		if($try && isset($matchs[1])) {
			$params = array(
				'header' => false,
				'useCookie' => true,
			);
			
			$matchs[1] = str_replace('&amp;', '&', $matchs[1]);
			
			$result = Curl::model()->request($matchs[1], $params);
			
			echo mb_convert_encoding($result, 'GBK', 'UTF-8');
			
			//$result = Curl::model()->request('http://bbs.duowan.com/home.php?mod=spacecp', $params);
			
			//echo strpos($result, 'mshing') !== false ? 1 : 0;
			
		} else {
			throw new Exception("Login failed!");
		}
	}
	
	protected function getLoginAuth() {
		$url = 'http://bbs.duowan.com/login.php?mod=getauthorizedurl';
		$params =  array(
			'type' => 'post',
			'header' => false,
			'data' => array(
				'callbackURL'=>'http://bbs.duowan.com/login.php?mod=callback',
				'denyCallbackURL'=>'http://bbs.duowan.com/login.php?mod=denycallback'
			)
		);
		
		$result = Curl::model()->request($url, $params);
		$result = CJSON::decode($result);
		return $result && isset($result['success']) && $result['success'] == 1 ? $result['url'] : false;
	}
	
	public function getThreadIds() {
		$url = 'http://bbs.duowan.com/forum-'.$this->fid.'-1.html';
		
		$result = Curl::model()->matchContent($url, "#href\=\"thread\-(\d+)\-1\-1\.html\"#", true);

		if(!$result || !isset($result[1])) {
			throw new Exception("Search Thread Failed!");
		}
		
		return array_unique($result[1]);
	}
	
	// 发表回复
	public function send($tid) {
		$url = 'http://bbs.duowan.com/thread-'.$tid.'-1-1.html';
		$params =  array(
			'type' => 'get',
			'header' => false,
			'useCookie'=> true
		);
		
		$result = Curl::model()->matchContent($url, "#name\=\"formhash\"\s+value\=\"(.*?)\"#", true, $params);
		if(!$result || !$result[1]) {
			//throw new Exception("Get FormHash Failed!");
			return false;
		}
		
		$postUrl = $this->baseUrl.'/forum.php?mod=post&action=reply&fid='.$this->fid.'&tid='.$tid.'&extra=page%3D1&replysubmit=yes&infloat=yes&handlekey=fastpost';
		
		$params =  array(
			'type' => 'post',
			'header' => false,
			'useCookie'=> true,
			'referer'=> $url,
			'data' => array(
				'message'=> $this->postsContent,
				'formhash'=> array_shift($result[1]),
				'subject'=> '',
				'replysubmit'=> 'replysubmit'
			)
		);
		
		$result = Curl::model()->request($postUrl, $params);
		return empty($result);
	}
	
	// 拼接帖子内容
	public $face = array('ew50','ew51','ew52','ew53','ew54','ew56','ew57','ew58','ew48','tu','hz19','ew59','ew55','ew35','ew19','ew02');
	public $text = array(
		'唉～ 有啥好说的，就那样了。', '呵呵··', '围观围观， 灌水灌水，挣积分。',
		'吐槽吐槽, 必须吐槽 +10086。', '百度一下，你就知道了。',
	);
	public function getPostsContent() {
		
		$rand_key = array_rand($this->face);
		$face = $this->face[$rand_key];
		
		$rand_key = array_rand($this->text);
		$text = $this->text[$rand_key];
		
		$suffix = rand(100001, 900009);
		
		return sprintf("%s [%s], %s", $text, $face, $suffix);
	}
	
}