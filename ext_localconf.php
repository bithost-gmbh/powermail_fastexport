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
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Bithost\\PowermailFastexport\\Controller\\ModuleController'] = array(
    'className' => 'In2code\\Powermail\\Controller\\ModuleController'
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Bithost\\PowermailFastexport\\Domain\\Repository\\AnswerRepository'] = array(
    'className' => 'In2code\\Powermail\\Domain\\Repository\\AnswerRepository'
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Bithost\\PowermailFastexport\\Domain\\Repository\\MailRepository'] = array(
    'className' => 'In2code\\Powermail\\Domain\\Repository\\MailRepository'
);
