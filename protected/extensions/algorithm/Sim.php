<?php
/*
 * ������������� �������ƶ�
 */
class Sim{
	protected static $arr1;
	protected static $arr2;
	
	// ������������� �������ƶ�
	public static function get($arr1, $arr2) {
		if(!is_array($arr1) || !is_array($arr2) || count($arr1) != count($arr2)) {
			throw new Exception("Params Error!");
		}
		
		self::$arr1 = $arr1;
		self::$arr2 = $arr2;
		return self::pointMulti()/self::sqrtMulti();
	}
	
	// �������� �и���Ԫ�ص�ƽ���� �ĳ˻� �Ŀ���
	protected static function sqrtMulti() {
		return sqrt(self::squares(self::$arr1) * self::squares(self::$arr2));
	}
	
	// �������� �и���Ԫ�ص�ƽ����
	protected static function squares($arr) {
		$result = 0;
		foreach($arr as $item) {
			$result += pow($item, 2);
		}
		return $result;
	}
	
	// ��˷� ���
	protected static function pointMulti() {
		$result = 0;
		foreach(self::$arr1 as $key => $item) {
			$result += self::$arr1[$key] * self::$arr2[$key];
		}
		return $result;
	}
}