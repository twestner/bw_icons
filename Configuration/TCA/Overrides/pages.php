<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'bw_icons',
    'Configuration/TSconfig/Page/Typo3Icons.tsconfig',
    'TYPO3 Core Icons'
);

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
);
$bwiconsConf = $extensionConfiguration->get('bw_icons');

if ((int)$bwiconsConf['pages'] === 1) {
    $GLOBALS['TCA']['pages']['columns']['tx_bwicons_icon'] = [
        'exclude' => 1,
        'label' => 'Icon',
        'config' => [
            'type' => 'input',
            'renderType' => 'iconSelection'
        ],
    ];
    $firstBreak = strpos($GLOBALS['TCA']['pages']['palettes']['title']['showitem'], '--linebreak--');
    $GLOBALS['TCA']['pages']['palettes']['title']['showitem'] = substr_replace($GLOBALS['TCA']['pages']['palettes']['title']['showitem'],
        'tx_bwicons_icon,',
        $firstBreak, 0);
}
