<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Format;

use TYPO3\CMS\Core\Utility\MathUtility;

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
				throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by \DateTime constructor.', 1241722579);
			}
		}

		if (strpos($format, '%') !== FALSE) {
			return strftime($format, $date->format('U'));
		} else {
			return $date->format($format);
		}

	}
}
