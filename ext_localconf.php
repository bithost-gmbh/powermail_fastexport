<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:powermail_fastexport/Configuration/TSConfig/tsconfig.ts">'
);

/*
 * Add XCLASS definitions
 *
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['In2code\\Powermail\\Controller\\ModuleController'] = array(
    'className' => 'Bithost\\PowermailFastexport\\Controller\\ModuleController'
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['In2code\\Powermail\\Domain\\Repository\\AnswerRepository'] = array(
    'className' => 'Bithost\\PowermailFastexport\\Domain\\Repository\\AnswerRepository'
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['In2code\\Powermail\\Domain\\Repository\\MailRepository'] = array(
    'className' => 'Bithost\\PowermailFastexport\\Domain\\Repository\\MailRepository'
);
