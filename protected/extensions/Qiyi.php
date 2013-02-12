<?php
//set_time_limit(10);

class Qiyi extends ExtensionsBase{
	private $_statusKey = 'qiyi_password_status';
	
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	// 发送找回密码邮件
	public function sendFindPWMail() {
		$params = array(
			'type'=>'post', 
			'data'=> array('email'=>'fx4084@gmail.com', 'piccode'=>'')
		);
		
		$url = 'http://passport.qiyi.com/apis/user/backpwd.php?t='.time();
		$return = Curl::model()->request($url, $params);
		
		$return = CJSON::decode($return);
		return isset($return['code']) && $return['code'] == 'A00000';
	}
	
	// 从邮箱获取重置密码 链接
	public function getFindPWLink($time) {
		date_default_timezone_set('Asia/ChongQing');
		$mailbox = @ imap_open(
			'{imap.gmail.com:993/imap/ssl}INBOX',
			Yii::app()->params->gmailAddress,
			Yii::app()->params->gmailPassword
		);
		
		if(!$mailbox) throw new Exception('IMAP open failed!');
		
		$headerList = $dateList = array();
		$allowHost = array('qiyi.com');
		$msgCount = imap_num_msg($mailbox); 
		
		for($i=1; $i <= $msgCount; $i++) {
			$header = @ imap_header($mailbox, $i); 
			if(!$header) continue;
			
			$num = trim($header->Msgno);
			$date = strtotime($header->date);
			$host = isset($header->from[0]) ? $header->from[0]->host : '';
			
			if(empty($host) || !in_array($host, $allowHost)) continue;
			
			$headerList[$num] = array(
				'date'=> $date,
				'num'=> $num,
				'from_host'=> $host,
			);
			
			$dateList[$num] = $date;
		}
		
		arsort($dateList);
		
		$content = '';
		foreach($dateList as $num => $date) {
			$content = @ imap_body($mailbox, $num);
			if($content) break;
		}
		
		imap_close($mailbox);
		$match = preg_match("#http\:\/\/passport\.iqiyi\.com\/user\/resetpwd\.php\?bcode\=(\w+)#i", $content, $array);
		return $match && isset($array[1]) ? 'http://passport.iqiyi.com/user/resetpwd.php?bcode='.$array[1] : FALSE;
	}
	
	// 获取 找回密码 认证码
	public function getFindPWBcode($link) {
		$content = Curl::model()->request($link);
		
		$match = preg_match("#input(?:.*?)id\=\"j-bcode\"\s+value\=\"(.*?)\"#i", $content, $array);
		
		return $match && isset($array[1]) ? $array[1] : '';
	}
	
	// 重置密码
	public function resetPassword($bcode, $newPW = null) {
		if(empty($bcode)) throw new Exception('bcode is null!');
		if(empty($newPW)) $newPW = $this->_getNewPassword(true);
		
		$params = array(
			'type'=> 'post',
			'transfer'=> 1,
			'data'=> array('passwd'=> $newPW, 'bcode'=> $bcode)
		);
		
		$url = 'http://passport.qiyi.com/apis/user/resetpwd.php?t='.time();
		$return = Curl::model()->request($url, $params);
		
		$return = CJSON::decode($return);
		if(isset($return['code']) && $return['code'] == 'A00000') {
			$this->_getPassword(true);
			return true;
		}
		
		throw new Exception('Password reset Failed!'.var_export($return, true));
	}
	
	/**
	 * 获取 or 设置 密码状态
	 * 状态：1=>正常， 2=>已经提交重置请求，3=>正在重置中
	 */
	public function passwordStatus($status = 0) {
		$redis = Yii::app()->redis;
		
		$nowStatus = $redis->get($this->_statusKey);
		if(!$nowStatus) {
			$redis->set($this->_statusKey, 1);
			$nowStatus = $redis->get($this->_statusKey);
		}
		
		if(
			($status == 1 && $nowStatus == 3) ||
			($status == 2 && $nowStatus == 1) ||
			($status == 3 && $nowStatus == 2)
		) {
			$redis->set($this->_statusKey, $status);
			$nowStatus = $redis->get($this->_statusKey);
		}
		
		return $nowStatus;
	}
	
	// 获取密码
	public function _getPassword($refresh = false) {
		if($refresh) {
			Yii::app()->redis->set('my_password', $this->newPassword);
		}
		
		return Yii::app()->redis->get('my_password');
	}
	
	// 获取新密码
	public function _getNewPassword($refresh = false) {
		if($refresh) {
			$time = microtime();
			$newPW = 'findlark'.substr( md5($time), 12, 6 );
			Yii::app()->redis->set('my_new_password', $newPW);
		}
		
		return Yii::app()->redis->get('my_new_password');
	}
	
	// 获取登录地址
	public function _getLoginUrl() {
		if($this->passwordStatus() != 1) {
			return FALSE;
		}
		
		$password = Qiyi::model()->password;
		$loginUrl = 'http://passport.iqiyi.com/apis/user/login.php?email=fx4084@gmail.com&passwd='.$password.'&keeplogin=1&piccode=&fromurl=&cb=__pc__login';
		
		$loginResult = Curl::model()->request($loginUrl);
		$loginResult = substr($loginResult, 17);
		$loginResult = CJSON::decode($loginResult);
		
		return isset($loginResult['code']) && $loginResult['code'] == 'A00000' ? $loginUrl : FALSE;
	}
	
	// 外部获取
	protected function _getRedisKey() {
		return $this->_statusKey;
	}
	
}