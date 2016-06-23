<?php
namespace Bithost\PowermailFastexport\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;

use In2code\Powermail\Utility\StringUtility;

/***
 *
 * This file is part of the "Powermail FastExport" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Markus Mächler <markus.maechler@bithost.ch>, Bithost GmbH
 *           Esteban Marin <esteban.marin@bithost.ch>, Bithost GmbH
 *
 ***/

/**
 * ModuleController
 */
class ModuleController extends \In2code\Powermail\Controller\ModuleController
{
    /**
     * @var \Bithost\PowermailFastexport\Domain\Repository\MailRepository
     * @inject
     */
    protected $mailRepository;

    /**
     * @var \Bithost\PowermailFastexport\Domain\Repository\AnswerRepository
     * @inject
     */
    protected $answerRepository;

    /**
     * Export Action for XLS Files
     *
     * @return String
     */
    public function exportXlsAction() {

        $mails = $this->getMailsAsArray();
        /** @var \Bithost\PowermailFastexport\Exporter\XlsExporter $xlsExporter */
        $xlsExporter = GeneralUtility::makeInstance('Bithost\\PowermailFastexport\\Exporter\\XlsExporter', $this->objectManager);
        $fieldUids = GeneralUtility::trimExplode(
            ',',
            StringUtility::conditionalVariable($this->piVars['export']['fields'], ''),
            TRUE
        );
        $fileName = StringUtility::conditionalVariable($this->settings['export']['filenameXls'], 'export.xls');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Pragma: no-cache');

        return $xlsExporter->export($mails, $fieldUids);
    }

    /**
     * Export Action for CSV Files
     *
     * @return String
     */
    public function exportCsvAction() {

        $mails = $this->getMailsAsArray();
        /** @var \Bithost\PowermailFastexport\Exporter\CsvExporter $csvExporter */
        $csvExporter = GeneralUtility::makeInstance('Bithost\\PowermailFastexport\\Exporter\\CsvExporter', $this->objectManager);
        $fieldUids = GeneralUtility::trimExplode(
            ',',
            StringUtility::conditionalVariable($this->piVars['export']['fields'], ''),
            TRUE
        );
        $fileName = StringUtility::conditionalVariable($this->settings['export']['filenameCsv'], 'export.csv');

        header('Content-Type: text/x-csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Pragma: no-cache');

        return $csvExporter->export($mails, $fieldUids);
    }

    /**
     * get all mails as array
     *
     * @return array
     */
    private function getMailsAsArray() {
        if(!empty($this->settings['maxExecutionTime']))
        {
            ini_set('max_execution_time', (int)$this->settings['maxExecutionTime']);
        }
        if(!empty($this->settings['memoryLimit']))
        {
            ini_set('memory_limit', $this->settings['memoryLimit']);
        }

        $dbMails = $this->mailRepository->findAllInPidRaw($this->id, $this->settings, $this->piVars);
        $mails = array();

        foreach ($dbMails as $mail) {
            $mails[$mail['uid']] = $mail;
        }

        $answers = $this->answerRepository->findByMailUidsRaw(array_keys($mails));

        foreach ($answers as $answer) {
            if (!is_array($mails[$answer['mail']]['answers'])) {
                $mails[$answer['mail']]['answers'] = array();
            }
            $mails[$answer['mail']]['answers'][$answer['uid']] = $answer;
        }

        return $mails;
    }

}
