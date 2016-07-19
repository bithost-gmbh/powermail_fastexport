<?php
namespace Bithost\PowermailFastexport\Exporter;

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

class XlsExporter extends AbstractExporter
{

	/**
	 * Export xls
	 *
	 * @param $mails
	 * @param array $fieldUids
	 *
	 * @return string
	 */
	public function export(array &$mails, array $fieldUids)
	{
		return '
            <html>
                <head>
                    <meta http-equiv="content-type" content="text/html; charset=utf-8">
                </head>
                <body>
                    <table>
                        ' . $this->renderMails($mails, $fieldUids) . '
                    </table>
                </body>
            </html>
        ';
	}

	/**
	 * Render Mails
	 *
	 * @param $mails
	 * @param array $fieldUids
	 *
	 * @return string
	 */
	protected function renderMails(array &$mails, array $fieldUids)
	{
		$result = $this->renderHeader($fieldUids);

		foreach ($mails as $mail) {
			$result .= $this->renderRecord($mail, $fieldUids);
		}

		return $result;
	}

	/**
	 * @param array $mail
	 * @param array $fieldUids
	 * @return string
	 */
	protected function renderRecord(array &$mail, array $fieldUids)
	{
		$result = '<tr>';

		foreach ($fieldUids as $fieldUid) {
			$result .= '<td>';

			$result .= $this->renderRecordFieldContent($mail, $fieldUid);

			$result .= '</td>';
		}

		$result .= '</tr>';

		return $result;
	}

	/**
	 * Render Header
	 *
	 * @param array $fieldUids
	 *
	 * @return string
	 */
	protected function renderHeader(array $fieldUids)
	{
		$result = '<tr>';

		foreach ($fieldUids as $fieldUid) {
			$result .= '<th>';

			if ($this->isNumber->render($fieldUid)) {
				$result .= $this->getFieldLabelFromUid->render($fieldUid);
			} else {
				$result .= $this->translate('\\In2code\\Powermail\\Domain\\Model\\Mail.' . $this->underscoredToLowerCamelCase->render($fieldUid));
			}

			$result .= '</th>';
		}
		$result .= '</tr>';

		return $result;
	}


}