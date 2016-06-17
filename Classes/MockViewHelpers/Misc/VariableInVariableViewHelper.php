<?php
namespace Bithost\Fastexport\MockViewHelpers\Misc;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

class VariableInVariableViewHelper {

	/**
	 * Solution for {outer.{inner}} call in fluid
	 *
	 * @param object|array $obj object or array
	 * @param string $prop property name
	 * @return mixed
	 */
	public function render($obj, $prop) {
		if (is_array($obj) && array_key_exists($prop, $obj)) {
			return $obj[$prop];
		}
		if (is_object($obj)) {
			return ObjectAccess::getProperty($obj, $prop);
		}
		return NULL;
	}
}