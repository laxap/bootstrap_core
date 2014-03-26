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
// Custom config
// --------------------------------------------------------------------
//
if ( file_exists(PATH_site . 'typo3conf/tx_bootstrapcore_custom.php') ) {
	include(PATH_site . 'typo3conf/tx_bootstrapcore_custom.php');
}

// --------------------------------------------------------------------
// Optional: Icon Font
// --------------------------------------------------------------------
//
if ( isset($extConf['enableIconFont']) && $extConf['enableIconFont'] != 'none' && $extConf['enableIconFont'] ) {
	// icon font key/name
	$iconFont = $extConf['enableIconFont'];

	// If not yet defined via custom config
	if ( ! is_array($iconFontOption) || count($iconFontOption) == 0 ) {
		// Load default icon font specific select array
		include(PATH_site . 'typo3conf/ext/bootstrap_core/ext_tables_' . $iconFont . '.php');
	}

	// Define field
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
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'headers', 'tx_bootstrapcore_icon', 'after:header_layout');
	}

	// Add static ts configurations for FontAwesome
	switch ( $iconFont ) {
		case 'fontello':
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/lib/' . $iconFont, 'Bootstrap: FontIcon Fontello');
			break;
		case 'fontawesome':
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/lib/' . $iconFont, 'Bootstrap: FontIcon FontAwesome');
			break;
	}
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/lib/fonticon', 'Bootstrap: FontIcons Setup');

}



// --------------------------------------------------------------------
// Optional: Modified Image Rendering
// --------------------------------------------------------------------
//
// add imageswidth field
if ( isset($extConf['enableModifiedImageRendering']) && $extConf['enableModifiedImageRendering'] == 1 ) {

	// If not yet defined via custom config
	if ( ! is_array($imageWidthOption) || count($imageWidthOption) == 0 ) {
		// Set default image width options
		$imageWidthOption = array(
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
		);
	}

	// define field
	$tempColumn = array(
		'tx_bootstrapcore_imageswidth' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_bootstrapcore_imageswidth',
			'config' => array (
				'type' => 'select',
				'items' => $imageWidthOption,
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



// --------------------------------------------------------------------
// Additional Content Elements for special layouts
// --------------------------------------------------------------------
//
// create new frames-palette without layout (layout is a "primary" field on first tab)
$TCA['tt_content']['palettes']['tx_bootstrapcore'] = array(
	'showitem' => 'spaceBefore;LLL:EXT:cms/locallang_ttc.xml:spaceBefore_formlabel, spaceAfter;LLL:EXT:cms/locallang_ttc.xml:spaceAfter_formlabel, section_frame;LLL:EXT:cms/locallang_ttc.xml:section_frame_formlabel',
	'canNotCollapse' => 1
);

// --- Content Element without images
//
// register plugin, with icon
$pluginIcon = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/plugin_textelement.png';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Simplicity.' . $_EXTKEY,
	'TextElement',
	'Spezieller Textinhalt',
	$pluginIcon
);
// create plugin sig key
$pluginSignature = str_replace('_','',$_EXTKEY) . '_textelement';
// show an icon in the page view
\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('tt_content', $pluginSignature, $pluginIcon);
// define used fields
$TCA['tt_content']['types'][$pluginSignature]['showitem'] = '
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
					layout;LLL:EXT:cms/locallang_ttc.xml:layout_formlabel,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.headers;headers,
					bodytext;Text;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],
					rte_enabled;LLL:EXT:cms/locallang_ttc.xml:rte_enabled_formlabel,' . '--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;tx_bootstrapcore,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';


// --- Content Element with image(s)
//
// register plugin, with icon
$pluginIcon = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/plugin_imageelement.png';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Simplicity.' . $_EXTKEY,
	'ImageElement',
	'Spezieller Bildtextinhalt',
	$pluginIcon
);
// create plugin sig key
$pluginSignature = str_replace('_','',$_EXTKEY) . '_imageelement';
// show an icon in the page view
\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('tt_content', $pluginSignature, $pluginIcon);
// define used fields
$TCA['tt_content']['types'][$pluginSignature]['showitem'] = '
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
					layout;LLL:EXT:cms/locallang_ttc.xml:layout_formlabel,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.headers;headers,
					bodytext;Text;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],
					rte_enabled;LLL:EXT:cms/locallang_ttc.xml:rte_enabled_formlabel,' . '--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.images,
					image,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.imagelinks;imagelinks,' . '--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;tx_bootstrapcore,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.image_settings;image_settings,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.imageblock;imageblock,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.textlayout;textlayout,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';

?>