<?php
namespace Bithost\PowermailFastexport\Exporter;

use In2code\Powermail\Domain\Model\Answer;
use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermailextended\Domain\Model\Field;

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
				$result .= $this->translate('\In2code\Powermail\Domain\Model\Mail.' . $this->underscoredToLowerCamelCase->render($fieldUid));
			}

			$result .= '</th>';
		}
		$result .= '</tr>';

		return $result;
	}


}