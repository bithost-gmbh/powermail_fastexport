<?php
namespace Bithost\Fastexport\MockViewHelpers\Condition;

class IsArrayViewHelper {

	/**
	 * View helper check if given value is array or not
	 *
	 * @param mixed $val String or Array
	 * @return bool
	 */
	public function render($val = '') {
		return is_array($val);
	}
}