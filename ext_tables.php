<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// --- Get extension configuration ---
$extConf = array();
if ( strlen($_EXTCONF) ) {
	$extConf = unserialize($_EXTCONF);
}


// --------------------------------------------------------------------
// Default bootstrap_core setup
//
// Add static typoscript configurations
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Bootstrap Core');

// Only if enables in ext conf
if ( isset($extConf['enableHeaderRenderingOption']) && $extConf['enableHeaderRenderingOption'] ) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Header', 'Headers rendering option');
}

// Set custom flexform for tt_content ctype table
$GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config']['ds']['*,table'] = 'FILE:EXT:bootstrap_core/Configuration/FlexForm/flexform_table.xml';


// --------------------------------------------------------------------
// Additional fields
//

// Define field
$tempColumn = array(
	'tx_bootstrapcore_visibility' => array (
		'exclude' => 0,
		'displayCond' => 'FIELD:section_frame:!=:66',
		'label' => 'LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_visibility',
		'config' => array (
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => array(
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.visibility.notset',  ''),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.visible.xs',	'visible-xs'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.visible.sm-xs','visible-xs visible-sm'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.visible.md-lg','visible-md visible-lg'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.visible.lg',   'visible-lg'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.hidden.xs', 	'hidden-xs'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.hidden.sm-xs', 'hidden-xs hidden-sm'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.hidden.md-lg', 'hidden-md hidden-lg'),
				array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.hidden.lg', 	'hidden-lg')
			),
			'size' => 1,
			'maxitems' => 1,
		)
	),
);

// re-add removed field in 7.2
if ( TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7002000 ) {
	// labels still available in 7.3.1
	$tempColumn['imagecaption_position'] = array(
		'label' => 'LLL:EXT:cms/locallang_ttc.xlf:imagecaption_position',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => array(
				array(
					'LLL:EXT:lang/locallang_general.xlf:LGL.default_value',
					''
				),
				array(
					'LLL:EXT:cms/locallang_ttc.xlf:imagecaption_position.I.1',
					'center'
				),
				array(
					'LLL:EXT:cms/locallang_ttc.xlf:imagecaption_position.I.2',
					'right'
				),
				array(
					'LLL:EXT:cms/locallang_ttc.xlf:imagecaption_position.I.3',
					'left'
				)
			),
			'default' => ''
		)
	);
}

// Add field to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumn, 1);

// Add to forms
// visiblity below frames
$GLOBALS['TCA']['tt_content']['palettes']['frames']['showitem'] .= ',--linebreak--, tx_bootstrapcore_visibility;LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_visibility';

?>