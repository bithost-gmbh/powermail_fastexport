<?php
defined('TYPO3_MODE') or die();


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:powermail_fastexport/Configuration/TSConfig/tsconfig.ts">'
);

/*
 * Add XCLASS definitions
 *
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Controller\ModuleController::class] = [
    'className' => \Bithost\PowermailFastexport\Controller\ModuleController::class
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Domain\Repository\AnswerRepository::class] = [
    'className' => \Bithost\PowermailFastexport\Domain\Repository\AnswerRepository::class
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Domain\Repository\MailRepository::class] = [
    'className' => \Bithost\PowermailFastexport\Domain\Repository\MailRepository::class
];
