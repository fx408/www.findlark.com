<?php
class SMSHelper {
	
	/**
	 * @param $number 手机号码，支持多个，一半角逗号（,）分割
	 * @param $message 短信内容
	 * 返回一个XML，包含发送成功的短信数量，接收方手机号码等
	 */
	public static function sendSMS($number, $message) {
		$rand=rand(1, 100);
		if ($rand>=101){
			$params = array(
				'Account'=>'3819',
				'Password'=>'202cb962ac59075b964b07152d234b70',
				'Phone'=>$number,
				'Content'=>$message
			);
			$url = "http://122.226.212.198:8080/ema/http/SendSms";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt($ch, CURLOPT_TIMEOUT,1);
			$result = curl_exec($ch);
			return preg_match("#\<response\>1\<\/response\>.*?\<phone\>[\d]{11}\<\/phone\>\<smsID\>[\da-f]{32}\<\/smsID\>#", $result);
		}else{
			$url='http://122.226.212.198:80/?phone='.$number.'&content='.urlencode($message).'&account=uuzuplatform&password=314159265358';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT,1);
			$result = curl_exec($ch);
			//echo "url=".$url."|result=".$result;
			return ($result>=1);
		}
	}
	
	
	public static function randCode($number = 6)
	{
		$code = '';
		for($i=0; $i<$number; $i++){
			$code .= mt_rand(0, 9);
		}
		return $code;
	}
}
?>