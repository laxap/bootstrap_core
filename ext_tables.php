<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// --- Get extension configuration ---
$extConf = array();
if ( strlen($_EXTCONF) ) {
	$extConf = unserialize($_EXTCONF);
}


// --------------------------------------------------------------------
// Default bootstrap_core page setup
// --------------------------------------------------------------------
//
// Add static typoscript configurations
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/core', 'Bootstrap Core');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/core/multilang', 'Bootstrap: Multilang');

// Set custom flexform for tt_content ctype table
$GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config']['ds']['*,table'] = 'FILE:EXT:bootstrap_core/Configuration/FlexForm/css_styled_content/flexform_ds.xml';



// --------------------------------------------------------------------
// Optional: Icon Font
// --------------------------------------------------------------------
//
if ( isset($extConf['enableIconFont']) && $extConf['enableIconFont'] != 'none' && $extConf['enableIconFont'] ) {
	// load icon font specific select array (defines $iconFontOption)
	include(PATH_site . 'typo3conf/ext/bootstrap_core/ext_tables_' . $extConf['enableIconFont'] . '.php');

	// define field
	$tempColumn = array(
		'tx_bootstrapcore_icon' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_icon',
			'config' => array (
				'type' => 'select',
				'default' => '0',
				'size' => 1,
				'maxitems' => 1,
				'items' => $iconFontOption
			)
		)
	);

	// Add field to tt_content
	//
	if ( isset($extConf['showFontIconsOnSeparateTab']) && $extConf['showFontIconsOnSeparateTab'] == 1 ) {
		// change label (info that icon is for header)
		$tempColumn['tx_bootstrapcore_icon']['label'] = 'LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_iconbeforeheader';
		// show 30 cols of icons (below selectbox)
		$tempColumn['tx_bootstrapcore_icon']['config']['selicon_cols'] = '30';
		// add on extended tab
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumn, 1);
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_bootstrapcore_icon', '', 'before:tx_gridelements_container');
	} else {
		// show icons in select options
		$tempColumn['tx_bootstrapcore_icon']['config']['iconsInOptionTags'] = 1;
		// don't show icons below select box
		$tempColumn['tx_bootstrapcore_icon']['config']['suppress_icons'] = 'ONLY_SELECTED';
		// add after header_layout
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumn, 1);
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'header', 'tx_bootstrapcore_icon', 'after:header_layout');
	}

	// Add static ts configurations for FontAwesome
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/lib/' . $extConf['enableIconFont'], 'Bootstrap: Icon Font');
}



// --------------------------------------------------------------------
// Optional: Modified Image Rendering
// --------------------------------------------------------------------
//
// add imageswidth field
if ( isset($extConf['enableModifiedImageRendering']) && $extConf['enableModifiedImageRendering'] == 1 ) {

	// define field
	$tempColumn = array(
		'tx_bootstrapcore_imageswidth' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_imageswidth',
			'config' => array (
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_imageswidth.I.0', '0'),
					array('1/12',   '1'),
					array('2/12',  '2'),
					array('25%',   '3'),
					array('33%',   '4'),
					array('5/12',  '5'),
					array('50%',   '6'),
					array('7/12',  '7'),
					array('66%',   '8'),
					array('75%',   '9'),
					array('10/12', '10'),
					array('11/12', '11'),
					array('100%',  '12')
				),
				'size' => 1,
				'maxitems' => 1,
			)
		)
	);
	// Add field to tt_content, in form before imagewidth
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumn, 1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'image_settings', 'tx_bootstrapcore_imageswidth', 'before:imagewidth');

	// Add static ts configurations for responsive image rendering
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/core/tt_content/image/responsive', 'Bootstrap: Responsive Images');
}



// --------------------------------------------------------------------
// Optional: Additional Video Content Type
// --------------------------------------------------------------------
//
if ( isset($extConf['enableNewVideoCType']) && $extConf['enableNewVideoCType'] == 1 ) {

	// register plugin, with icon
	$pluginIcon = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/plugin_videocontent.png';
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Simplicity.' . $_EXTKEY,
		'VideoContent',
		'Video (Youtube, Vimeo)',
		$pluginIcon
	);

	// create plugin sig key
	$pluginSignature = str_replace('_','',$_EXTKEY) . '_videocontent';

	// show an icon in the page view
	\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('tt_content', $pluginSignature, $pluginIcon);

	// define used fields
	$TCA['tt_content']['types'][$pluginSignature]['showitem'] = '
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header,
				--div--;Videos,
					image_link,imagewidth,imageheight,imagecaption,imagecaption_position,imagecols,image_zoom,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.textlayout;textlayout,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
}




// --------------------------------------------------------------------
// Add more static ts configurations (extensions, libs)
// --------------------------------------------------------------------
//
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/lib/googleanalytics', 'Bootstrap: Google Analytics');

?>