<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Condition;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Is {outer.{inner}} a datetime?
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class IsDateTimeVariableInVariableViewHelper {

	/**
	 * Is {outer.{inner}} a datetime?
	 *
	 * @param object $obj
	 * @param string $prop Property
	 * @return bool
	 */
	public function render($obj, $prop) {
		return is_a(ObjectAccess::getProperty($obj, $prop), '\DateTime');
	}
}