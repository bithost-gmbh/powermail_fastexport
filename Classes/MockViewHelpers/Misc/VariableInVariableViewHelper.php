<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Misc;

use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/***
 *
 * This file is part of the "Powermail FastExport" Extension for TYPO3 CMS.
 *
 *  (c) 2016 Markus Mächler <markus.maechler@bithost.ch>, Bithost GmbH
 *           Esteban Marin <esteban.marin@bithost.ch>, Bithost GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***/

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