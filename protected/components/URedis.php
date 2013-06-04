<?php
class URedis extends CApplicationComponent{
	
	/**
	 * redis服务器 ip地址
	 *
	 * @var string
	 */
	public $host;
	
	/**
	 * redis服务器端口号
	 *
	 * @var int
	 */
	public $port;
	
	/**
	 * 连接超时时间
	 *
	 * @var int
	 */
	public $timeOut=3;
	
	/**
	 * redis连接句柄
	 *
	 * @var resource
	 */
	protected $_handler;
	
	
	public function init() {
		parent::init();
		if(empty($this->host)) throw new CException("请配置redis host");
		if(empty($this->port)) throw new CException("请配置redis端口号");
	}
	
	public function getHandler() {
		if($this->_handler==null){
			try{
				$this->_handler = new Redis();
				$this->_handler->connect($this->host, $this->port,$this->timeOut);
			} catch(Exception $e) {
				return null;
			}
		}
		return $this->_handler;
	}
	
	public function __call($method, $arg_array) {
		try{
			if($this->handler==null) return false;
			return call_user_func_array(array($this->handler,$method), $arg_array);
		} catch(Exception $e) {
			return false;
		}
//		return call_user_method_array($method, $this->handler, $arg_array);
	}
}