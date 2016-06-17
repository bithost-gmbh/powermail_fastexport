<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Powermail FastExport');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermailfastexport_domain_model_module', 'EXT:powermail_fastexport/Resources/Private/Language/locallang_csh_tx_powermailfastexport_domain_model_module.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_powermailfastexport_domain_model_module');

    },
    $_EXTKEY
);
