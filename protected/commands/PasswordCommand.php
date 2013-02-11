<?php

class PasswordCommand extends CConsoleCommand {
	private $sleepTime = 10;
	
	
	public function actionIndex() {
		set_time_limit(0);
		
		$model = Qiyi::model();
		$mailSendTime = 0;
		
		while(TRUE) {
			sleep($this->sleepTime);
			echo "->RUN \n";
			
			try{
				if($model->passwordStatus() == 1) {
					continue;
				} else if($model->passwordStatus() == 2) {
					$sendResult = $model->sendFindPWMail();
					$mailSendTime = time()-1;
					
					if($sendResult) $model->passwordStatus(3); // 邮件发送成功，将状态改为3
					printf("Send find password mail. result: %s \n", $sendResult ? 'TRUE' : 'FALSE');
				} else {
					
					$link = $model->getFindPWLink($mailSendTime);
					printf("Password find link: %s \n", $link);
					
					$bcode = $model->getFindPWBcode($link);
					printf("bcode: %s \n", $bcode);
					
					$reset = $model->resetPassword($bcode);
					printf("Password reset result: %s \n%s\n", $reset ? 'TRUE' : 'FALSE', str_repeat('-', 15) );
					
					if($reset) $model->passwordStatus(1); // 密码重置成功，将状态改为1
				}
			}catch(Exception $e) {
				printf("Exception: %s \n", $e->getMessage());
			}
		}
	}
}