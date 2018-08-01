<?php
namespace app\common\validate;

use think\Validate;

class Base extends Validate {

	public function _switch($value, $rule, $data, $field) {
		if(isset($data[$field]) && $value == 'on'){
			$data[$field] = 1;
		} else {
			$data[$field] = $rule;
		}
		return true;
	}
	
	public function _default($value, $rule, $data, $field) {
		
	}
}