<?php
class EmailUniqueValidator extends CUniqueValidator {
	protected function validateAttribute($object, $attribute) {
		$criteria=new CDbCriteria;
		$criteria->compare('email', $object->$attribute);
		
		$data = LarkUser::model()->find($criteria);
		if( !empty($data) ) {
			$message = $this->message!==null ? $this->message : '该邮箱已被注册!';
			$this->addError($object, $attribute, $message, array('{value}'=>$value));
		}
	}
}