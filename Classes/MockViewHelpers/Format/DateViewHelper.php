<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Format;

use TYPO3\CMS\Core\Utility\MathUtility;
use \TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

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

class DateViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Render the supplied DateTime object as a formatted date.
	 *
	 * @param mixed $date either a DateTime object or a string that is accepted by DateTime constructor
	 * @param string $format Format String which is taken to format the Date/Time
	 * @return string Formatted date
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 * @api
	 */
	public function render($date = NULL, $format = '') {
		if ($format === '') {
			$format = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] ?: 'Y-m-d';
		}

		if ($date === NULL) {
			return '';
		}
		if (!$date instanceof \DateTime) {
			try {
				if (MathUtility::canBeInterpretedAsInteger($date)) {
					$date = new \DateTime('@' . $date);
				} else {
					$date = new \DateTime($date);
				}
				$date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
			} catch (\Exception $exception) {
				throw new Exception('"' . $date . '" could not be parsed by \DateTime constructor.', 1241722579);
			}
		}

		if (strpos($format, '%') !== FALSE) {
			return strftime($format, $date->format('U'));
		} else {
			return $date->format($format);
		}

	}
}
