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

        $output .= self::arrayToCsv($this->renderHeader($fieldUids), false, self::CSV_DELIMITER, self::CSV_ENCLOSURE);

        foreach ($mails as $mail) {
            $output .= self::arrayToCsv($this->renderRecord($mail, $fieldUids), false, self::CSV_DELIMITER, self::CSV_ENCLOSURE);
        }

        return $output;
    }

    /**
     * @param array $mail
     * @param array $fieldUids
     * @return string
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
     * @param array $array array to be converted to csv
     * @param bool $headerRow whether to print the array keys as the first row
     * @param string $columnSeparator column separator
     * @param string $quote quote character
     * @param string $rowSeparator row separator
     *
     * @return string
     */
    static function arrayToCsv(array $array, $headerRow = true, $columnSeparator = ",", $quote = '"', $rowSeparator = "\n")
    {
        $output = '';
        if (!is_array($array) or !is_array($array[0])) {
            return false;
        }

        //Header row.
        if ($headerRow) {
            foreach ($array[0] as $key => $value) {
                //Escaping quotes
                $key = str_replace($quote, $quote . $quote, $key);
                $output .= $columnSeparator . $quote . $key . $quote;
            }
            $output = substr($output, 1) . $rowSeparator;
        }
        //Data rows.
        foreach ($array as $row) {
            $tmp = '';
            foreach ($row as $key => $value) {
                //Escaping quotes
                $value = str_replace($quote, $quote . $quote, $value);
                $output .= $columnSeparator . $quote . $value . $quote;
            }
            $output .= substr($tmp, 1) . $rowSeparator;
        }

        return $output;
    }
}