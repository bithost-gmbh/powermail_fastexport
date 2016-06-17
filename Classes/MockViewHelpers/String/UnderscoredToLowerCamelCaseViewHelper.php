<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\String;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class UnderscoredToLowerCamelCaseViewHelper {

    /**
     * Underscored value to lower camel case value (nice_field => niceField)
     *
     * @param string $val
     *
     * @return string
     */
    public function render($val = '') {
        return GeneralUtility::underscoredToLowerCamelCase($val);
    }
}