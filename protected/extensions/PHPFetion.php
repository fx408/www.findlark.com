<?php
/**
 * PHP飞信发送类
 */
class PHPFetion {
	/**
	 * 发送者手机号
	 * @var string
	 */
	protected $mobile;

	/**
	 * 飞信密码
	 * @var string
	 */
	protected $password;
	
	/**
	 * Cookie字符串
	 * @var string
	 */
	protected $cookie = '';

	/**
	 * Uid缓存
	 * @var array
	 */
	protected $_uids = array();

	/**
	 * csrfToken
	 * @var string
	 */
	protected $_csrfToten = null;
	
	private static $_model = null;
	
	/**
	 * 单例模式
	 */
	public static function model($className = __CLASS__) {
		if(self::$_model === null) {
			self::$_model = new $className;
		}
		
		return self::$_model;
	}
	
	private function __construct() {}

	/**
	 * 析构函数
	 */
	public function __destruct() {
		$this->_logout();
	}

	/**
	 * 登录
	 * @return string
	 */
	public function login($mobile, $password) {
		if(!preg_match("#^\d{11}$#", $mobile) || empty($password)) {
			throw new Exception("Mobile or Password is error!");
		}
		
		if($this->cookie !== null) {
			$this->_logout();
		}
		
		$this->mobile = $mobile;
		$this->password = $password;
		
		$uri = '/huc/user/space/login.do?m=submit&fr=space';
		$data = 'mobilenum='.$this->mobile.'&password='.urlencode($this->password);
		
		$result = $this->_post($uri, $data);

		//解析Cookie
		$match = preg_match_all('/.*?\r\nSet-Cookie:\s+(.*?);.*?/si', $result, $matches);
		if($match && isset($matches[1])) {
			$this->cookie = implode('; ', $matches[1]);
		}
		
		$result = $this->_post('/im/login/cklogin.action', '');

		return $result;
	}

	/**
	 * 向指定的手机号发送飞信
	 * @param string $mobile 手机号(接收者)
	 * @param string $message 短信内容
	 * @return string
	 */
	public function send($toMobile, $message) {
		if($this->cookie === null) {
			throw new Exception("Please login first or login failed!");
		}
		if(!preg_match("#^\d{11}$#", $toMobile)) {
			throw new Exception("Mobile is error!");
		}
		if(empty($message)) {
			throw new Exception("Message is empty!");
		}

		//判断是给自己发还是给好友发
		if ($toMobile == $this->mobile) {
			return $this->_toMyself($message);
		} else {
			$uid = $this->_getUid($toMobile);

			return $uid === '' ? '' : $this->_toUid($uid, $message);
		}
	}

	/**
	 * 获取飞信ID
	 * @param string $mobile 手机号
	 * @return string
	 */
	protected function _getUid($mobile) {
		if (empty($this->_uids[$mobile]))
		{
			$uri = '/im/index/searchOtherInfoList.action';
			$data = 'searchText='.$mobile;
			
			$result = $this->_post($uri, $data);
			
			//匹配
			preg_match('/toinputMsg\.action\?touserid=(\d+)/si', $result, $matches);

			$this->_uids[$mobile] = isset($matches[1]) ? $matches[1] : '';
		}
		
		return $this->_uids[$mobile];
	}

	/**
	 * 获取csrfToken，给好友发飞信时需要这个字段
	 * @param string $uid 飞信ID
	 * @return string
	 */
	protected function _getCsrfToken($uid)
	{
		if ($this->_csrfToten === null)
		{
			$uri = '/im/chat/toinputMsg.action?touserid='.$uid;
			
			$result = $this->_post($uri, '');
			
			preg_match('/name="csrfToken".*?value="(.*?)"/', $result, $matches);

			$this->_csrfToten = isset($matches[1]) ? $matches[1] : '';
		}

		return $this->_csrfToten;
	}

	/**
	 * 向好友发送飞信
	 * @param string $uid 飞信ID
	 * @param string $message 短信内容
	 * @return string
	 */
	protected function _toUid($uid, $message)
	{
		$uri = '/im/chat/sendMsg.action?touserid='.$uid;
		$csrfToken = $this->_getCsrfToken($uid);
		$data = 'msg='.urlencode($message).'&csrfToken='.$csrfToken;
		
		$result = $this->_post($uri, $data);
		
		return $result;
	}

	/**
	 * 给自己发飞信
	 * @param string $message
	 * @return string
	 */
	protected function _toMyself($message) {
		$uri = '/im/user/sendMsgToMyselfs.action';
		$result = $this->_post($uri, 'msg='.urlencode($message));

		return strpos($result, '短信发送成功!') !== false;
	}

	/**
	 * 退出飞信
	 * @return string
	 */
	protected function _logout() {
		$uri = '/im/index/logoutsubmit.action';
		$result = $this->_post($uri, '');
		
		$this->cookie = null;
		return $result;
	}

	/**
	 * 携带Cookie向f.10086.cn发送POST请求
	 * @param string $uri
	 * @param string $data
	 */
	protected function _post($uri, $data) {
		$fp = fsockopen('f.10086.cn', 80);
		fputs($fp, "POST $uri HTTP/1.1\r\n");
		fputs($fp, "Host: f.10086.cn\r\n");
		fputs($fp, "Cookie: {$this->cookie}\r\n");
		fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1\r\n");
		fputs($fp, "Content-Length: ".strlen($data)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data);

		$result = '';
		while(!feof($fp)) {
			$result .= fgets($fp);
		}
		fclose($fp);
		return $result;
	}

}