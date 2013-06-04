<?php
define('WB_AKEY', '104109253');
define('WB_SKEY', '78663e10bd49d78cc0393e3dc8cc2197');
define('WB_CALLBACK_URL', 'http://www.findlark.com/sina/callback');

session_start();

class SinaController extends Controller {
	
	public function actionIndex() {
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		$code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);
		
		$this->redirect($code_url);
		
		echo '<p><a href="'.$code_url.'"><img src="/static/images/weibo_login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>';
	}
	
	public function actionCallback() {
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		
		if(isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken('code', $keys);
			} catch(OAuthException $e) {
					
			}
		}
		
		if($token) {
			$_SESSION['token'] = $token;
			setcookie('weibojs_'.$o->client_id, http_build_query($token));
			echo 'success!';
			
		} else {
			echo 'auth failed!';
		}
		
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		$userinfo = $o->getTokenFromJSSDK();
		
		var_dump($userinfo);
	}
	
	public function actionCenter() {
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $_SESSION['token']['access_token']);
		
		$userinfo = $c->show_user_by_id($_SESSION['token']['uid']);
		var_dump($userinfo);
	}
}