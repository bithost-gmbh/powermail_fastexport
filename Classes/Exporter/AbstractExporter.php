<?php
namespace Bithost\PowermailFastexport\Exporter;

use Bithost\PowermailFastexport\MockViewHelpers\Condition\IsArrayViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Condition\IsDateTimeVariableInVariableViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Condition\IsNumberViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Format\DateViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Getter\GetFieldLabelFromUidViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Getter\GetPageNameFromUidViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\Misc\VariableInVariableViewHelper;
use Bithost\PowermailFastexport\MockViewHelpers\String\UnderscoredToLowerCamelCaseViewHelper;
use In2code\Powermail\Domain\Model\Answer;
use In2code\Powermail\Utility\Div;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

abstract class AbstractExporter
{
	/**
	 * @var IsNumberViewHelper
	 */
	protected $isNumber = null;

	/**
	 * @var IsArrayViewHelper
	 */
	protected $isArray = null;

	/**
	 * @var IsDateTimeVariableInVariableViewHelper
	 */
	protected $isDateTimeVariableInVariable = null;

	/**
	 * @var DateViewHelper
	 */
	protected $date = null;

	/**
	 * @var GetFieldLabelFromUidViewHelper
	 */
	protected $getFieldLabelFromUid = null;

	/**
	 * @var GetPageNameFromUidViewHelper
	 */
	protected $getPageNamerFromUid = null;

	/**
	 * @var UnderscoredToLowerCamelCaseViewHelper
	 */
	protected $underscoredToLowerCamelCase = null;

	/**
	 * @var VariableInVariableViewHelper
	 */
	protected $variableInVariable = null;

	/**
	 * @var ObjectManager
	 */
	protected $objectManager = null;

	/**
	 * @param ObjectManager $objectManager
	 */
	public function __construct(ObjectManager $objectManager)
	{
		$this->objectManager = $objectManager;
		$this->isNumber = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\Condition\IsNumberViewHelper');
		$this->isArray = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\Condition\IsArrayViewHelper');
		$this->isDateTimeVariableInVariable = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\Condition\IsDateTimeVariableInVariableViewHelper');
		$this->date = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\Format\DateViewHelper');
		$this->getFieldLabelFromUid = $this->objectManager->get('Bithost\PowermailFastexport\MockViewHelpers\Getter\GetFieldLabelFromUidViewHelper');
		$this->getPageNamerFromUid = $this->objectManager->get('Bithost\PowermailFastexport\MockViewHelpers\Getter\GetPageNameFromUidViewHelper');
		$this->underscoredToLowerCamelCase = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\String\UnderscoredToLowerCamelCaseViewHelper');
		$this->variableInVariable = GeneralUtility::makeInstance('Bithost\PowermailFastexport\MockViewHelpers\Misc\VariableInVariableViewHelper');
	}

	/**
	 * @param array $mails
	 * @param array $fieldUids
	 * @return string
	 */
	abstract public function export(array &$mails, array $fieldUids);

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	protected function translate($key)
	{
		return LocalizationUtility::translate($key, 'powermail');
	}

	/**
	 * @param array $mail
	 * @param $fieldUid
	 * @return string
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	protected function renderRecordFieldContent(array &$mail, $fieldUid)
	{
		$result = '';

		if ($this->isNumber->render($fieldUid)) {
			if (is_array($mail['answers'])) {
				foreach ($mail['answers'] as $answer) {
					$answer['value'] = $this->getValue($answer);
					/** @var Answer $answer */
					if (isset($answer['field']) && $fieldUid == $answer['field']) {
						if ($this->isArray->render($answer['value'])) {
							$i = 0;
							$length = count($answer['value']);
							foreach ($answer['value'] as $singleValue) {
								if ($singleValue) {
									$result .= $singleValue;
									if ($i !== $length - 1) {
										$result .= ',';
									}
								}
								$i++;
							}
						} else {
							$result .= $answer['value'];
						}
					}
				}
			}
		} else {
			if ($this->isDateTimeVariableInVariable->render($mail, $fieldUid)) {
				if ($fieldUid == 'crdate') {
					$result .= $this->date->render($this->variableInVariable->render($mail, $fieldUid), 'd.m.Y H:i:s');
					$result .= $this->translate('Clock');
				} else {
					if ($fieldUid == 'time') {
						$result .= $this->date->render($this->variableInVariable->render($mail, $fieldUid), '%M:%S');
					} else {
						$result .= $this->date->render($this->variableInVariable->render($mail, $fieldUid), 'H:i:s');
					}
				}
			} else {
				if ($fieldUid == 'marketing_page_funnel') {
					if ($this->isArray->render($this->variableInVariable->render($mail, $fieldUid))) {
						$i = 0;
						$length = count($this->variableInVariable->render($mail, $fieldUid));
						foreach ($this->variableInVariable->render($mail, $fieldUid) as $pid) {
							$result .= $this->getPageNameFromUid->render($pid);
							if ($i !== $length - 1) {
								$result .= '&gt;';
							}
						}
					}
				} else {
					$result .= $this->variableInVariable->render($mail, $fieldUid);
				}
			}
		}

		return $result;
	}

	protected function getValue($answer) {
		$value = $answer['value'];

		// if serialized, change to array
		if (Div::isJsonArray($value)) {
			// only if type multivalue or upload
			if ($answer['value_type'] == 1 || $answer['value_type'] == 3) {
				$value = json_decode($value, TRUE);
			}
		}

		// if multitext or upload force array
		if (($answer['value_type'] == 1 || $answer['value_type'] == 3) && !is_array($value)) {
			$value = (empty($value) ? array() : array(strval($value)));
		}

		return $value;
	}
}