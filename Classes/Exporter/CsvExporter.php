<?php
namespace Bithost\PowermailFastexport\Exporter;


class CsvExporter extends AbstractExporter
{

    const CSV_DELIMITER = ',';
    const CSV_ENCLOSURE = '"';

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
        $output = '';

        $output .= self::rowArrayToCsv($this->renderHeader($fieldUids), self::CSV_DELIMITER, self::CSV_ENCLOSURE);

        foreach ($mails as $mail) {
            $output .= self::rowArrayToCsv($this->renderRecord($mail, $fieldUids), self::CSV_DELIMITER, self::CSV_ENCLOSURE);
        }

        return $output;
    }

    /**
     * @param array $mail
     * @param array $fieldUids
     * @return array
     */
    protected function renderRecord(array &$mail, array $fieldUids)
    {
        $data = array();

        foreach ($fieldUids as $fieldUid) {
            $data[] = $this->renderRecordFieldContent($mail, $fieldUid);
        }

        return $data;
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
        $data = array();

        foreach ($fieldUids as $fieldUid) {
            if ($this->isNumber->render($fieldUid)) {
                $data[] = $this->getFieldLabelFromUid->render($fieldUid);
            } else {
                $data[] = $this->translate('\\In2code\\Powermail\\Domain\\Model\\Mail.' . $this->underscoredToLowerCamelCase->render($fieldUid));
            }
        }

        return $data;
    }

    /**
     * Generating CSV formatted string from an array.
     * By Sergey Gurevich.
     * Ref. http://www.codehive.net/PHP-Array-to-CSV-1.html
     *
     * @param array $row array to be converted to csv
     * @param string $columnSeparator column separator
     * @param string $quote quote character
     * @param string $rowSeparator row separator
     *
     * @return string
     */
    static function rowArrayToCsv(array $row, $columnSeparator = ",", $quote = '"', $rowSeparator = "\n")
    {
        $output = '';
        if (!is_array($row)) {
            return false;
        }

        $tmp = '';
        $i = 0;
        foreach ($row as $key => $value) {
            //Escaping quotes
            $value = str_replace($quote, $quote . $quote, $value);
            $output .= ($i>0 ? $columnSeparator:'') . $quote . $value . $quote;
            ++$i;
        }
        $output .= $tmp . $rowSeparator;

        return $output;
    }
}