<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// --- Get extension configuration ---
$extConf = array();
if ( strlen($_EXTCONF) ) {
	$extConf = unserialize($_EXTCONF);
}


// --------------------------------------------------------------------
// Default bootstrap_core page TSconfig
// --------------------------------------------------------------------
//
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tsconfig.ts">');



// --------------------------------------------------------------------
// Optional: Icon Font
// --------------------------------------------------------------------
//
if ( isset($extConf['enableIconFont']) && $extConf['enableIconFont'] != 'none' && $extConf['enableIconFont'] ) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/lib/' . $extConf['enableIconFont'] . '/tsconfig.ts">');
}



// --------------------------------------------------------------------
// Optional: Link wizard extension for "Bootstrap" links ---
// --------------------------------------------------------------------
//
if ( isset($extConf['enableBootstrapLinks']) && $extConf['enableBootstrapLinks'] == 1 ) {
	// --- Hooks in sysext/core/Classes/Html/RteHtmlParser.php ---
	// Add linkhandler
	// additional available hooks: removeParams($parameters, $this), modifyParamsLinksDb($p, $this), modifyParamsLinksRte($p, $this)
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['bootstrap']  = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hooks\LinkHandler';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['modifyParams_LinksRte_PostProc'][] = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hooks\LinkHandler';
	// --- Hook in sysext/rtehtmlarea/Classes/BrowseLinks.php
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][] = 'EXT:bootstrap_core/Classes/Hooks/ElementBrowser.php:Simplicity\BootstrapCore\Hooks\ElementBrowser';
	// --- Hook in sysext/recordlist/Classes/Browser/ElementBrowser.php
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook'][] = 'EXT:bootstrap_core/Classes/Hooks/ElementBrowser.php:Simplicity\BootstrapCore\Hooks\ElementBrowser';
}



// --------------------------------------------------------------------
// Optional: Additional Video Content Type
// --------------------------------------------------------------------
//
if ( isset($extConf['enableNewVideoCType']) && $extConf['enableNewVideoCType'] == 1 ) {
	// Configure plugin as content element
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Simplicity.' . $_EXTKEY,
		'VideoContent',
		array('VideoContent' => 'show',),
		// non-cacheable actions
		array(),
		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
	);

}

// --------------------------------------------------------------------
// Additional Content Elements for special layouts
// --------------------------------------------------------------------
//
// --- Content Element without images
//
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Simplicity.' . $_EXTKEY,
	'TextElement',
	// cacheable actions
	array('ContentElement' => 'showText'),
	// non-cacheable actions
	array(),
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// --- Content Element with image(s)
//
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Simplicity.' . $_EXTKEY,
	'ImageElement',
	// cacheable actions
	array('ContentElement' => 'showTextImage'),
	// non-cacheable actions
	array(),
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

?>