<?php
namespace Bithost\PowermailFastexport\Exporter;

use In2code\Powermail\Domain\Model\Answer;
use In2code\Powermail\Utility\ArrayUtility;
use In2code\Powermail\ViewHelpers\Condition\IsDateTimeVariableInVariableViewHelper;
use In2code\Powermail\ViewHelpers\Getter\GetFieldLabelFromUidViewHelper;
use In2code\Powermail\ViewHelpers\Getter\GetPageNameFromUidViewHelper;
use In2code\Powermail\ViewHelpers\Misc\VariableInVariableViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

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

abstract class AbstractExporter
{
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
	protected $getPageNameFromUid = null;

	/**
	 * @var VariableInVariableViewHelper
	 */
	protected $variableInVariable = null;

	/**
	 * @var ObjectManager
	 */
	protected $objectManager = null;

    /**
     * @var RenderingContext
     */
    protected $renderingContext = null;

	/**
	 * @param ObjectManager $objectManager
	 */
	public function __construct(ObjectManager $objectManager, RenderingContext $renderingContext)
	{
		$this->objectManager = $objectManager;
		$this->renderingContext = $renderingContext;
		$this->isDateTimeVariableInVariable = GeneralUtility::makeInstance(IsDateTimeVariableInVariableViewHelper::class);
		$this->date = GeneralUtility::makeInstance(DateViewHelper::class);
		$this->getFieldLabelFromUid = $this->objectManager->get(GetFieldLabelFromUidViewHelper::class);
		$this->getPageNamerFromUid = $this->objectManager->get(GetPageNameFromUidViewHelper::class);
		$this->variableInVariable = GeneralUtility::makeInstance(VariableInVariableViewHelper::class);
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

		if (is_numeric($fieldUid)) {
			if (is_array($mail['answers'])) {
				foreach ($mail['answers'] as $answer) {
					$answer['value'] = $this->getValue($answer);
					/** @var Answer $answer */
					if (isset($answer['field']) && (int)$fieldUid === (int)$answer['field']) {
						if (is_array($answer['value'])) {
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
            $this->isDateTimeVariableInVariable->setArguments(['obj' => $mail, 'prop' => $fieldUid]);
			if ($this->isDateTimeVariableInVariable->render()) {
                $this->variableInVariable->setArguments(['obj' => $mail, 'prop' => $fieldUid]);
				if ($fieldUid === 'crdate') {
				    $this->date->setArguments(['date' => $this->variableInVariable->render(), 'format' => 'd.m.Y H:i:s']);
					$result .= $this->date->render();
					$result .= $this->translate('Clock');
				} else {
					if ($fieldUid === 'time') {
                        $this->date->setArguments(['date' => $this->variableInVariable->render(), 'format' => '%M:%S']);
						$result .= $this->date->render();
					} else {
                        $this->date->setArguments(['date' => $this->variableInVariable->render(), 'format' => 'H:i:s']);
						$result .= $this->date->render();
					}
				}
			} else {
				if ($fieldUid === 'marketing_page_funnel') {
                    $this->variableInVariable->setArguments(['obj' => $mail, 'prop' => $fieldUid]);
					if (is_array($this->variableInVariable->render())) {
						$i = 0;
						$length = count($this->variableInVariable->render());
						foreach ($this->variableInVariable->render() as $pid) {
							$this->getPageNameFromUid->setArguments(['uid' => $pid]);
							$result .= $this->getPageNameFromUid->render();
							if ($i !== $length - 1) {
								$result .= '&gt;';
							}
						}
					}
				} else {
                    $this->variableInVariable->setArguments(['obj' => $mail, 'prop' => $fieldUid]);
					$result .= $this->variableInVariable->render();
				}
			}
		}

		return $result;
	}

	protected function getValue($answer) {
		$value = $answer['value'];

		// if serialized, change to array
		if (ArrayUtility::isJsonArray($value)) {
			// only if type multivalue or upload
			if ($answer['value_type'] === 1 || $answer['value_type'] === 3) {
				$value = json_decode($value, TRUE);
			}
		}

		// if multitext or upload force array
		if (($answer['value_type'] === 1 || $answer['value_type'] === 3) && !is_array($value)) {
			$value = (empty($value) ? array() : array((string)$value));
		}

		return $value;
	}
}