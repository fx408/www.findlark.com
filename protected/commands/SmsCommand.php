<?php
class SmsCommand extends CConsoleCommand {
	public function actionIndex() {
		$file = dirname(__FILE__).'/number.txt';
		$logFile = dirname(__FILE__).'/log.txt';
		$str = '';
		
		//$contents = '新年的钟声即将敲响，惊心动魄的2012年即将彻底过去，伟大的2013年即将到来。2013游族网络，与你分享简单的快乐！衷心祝你和你的家人，蛇年吉祥如意，幸福安康！';
		//$contents = '新年的钟声即将敲响，感谢您曾经为游族梦的拼搏，感谢您曾经为游族梦的付出！感谢您，曾经一起战斗的战友！衷心祝您和您的家人，蛇年吉祥如意，幸福安康！';
		//$contents = '恭喜您中了2013年游族好彩头，在13亿人口的几率下，您获得了游族独家特产：金灿灿的未来蓝图，大展拳脚的广阔平台。2013年游族祝您全家安康，蛇年大吉！';
		 $contents = '亲，今天是个特别的日子，你还记得吗？今天是你的生日，我们记得，我们在你身边！祝你生日快乐，新年快乐！';
		
		$fp = fopen($file, 'r');

		while(!feof($fp)) {
			$line = trim( fgets($fp) );
			
			$str .= $line.'  '.SMSHelper::sendSMS($line, $contents)."\r\n";
			
			//$str .= $line.'  '."\r\n";
		}
		
		file_put_contents($logFile, $str);
		
		echo 'END: -';
	}


}
