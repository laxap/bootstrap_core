<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// --- Get extension configuration ---
$extConf = array();
//if ( isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_core']) ) {
if ( strlen($_EXTCONF) ) {
	$extConf = unserialize($_EXTCONF);
}

// --- Hooks for custom link handler ---
//
// only if enabled in extension configuration
if ( isset($extConf['enableBootstrapLinks']) && $extConf['enableBootstrapLinks'] == 1 ) {

	// --- Hooks in sysext/core/Classes/Html/RteHtmlParser.php ---
	// Add linkhandler
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['bootstrap']  = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hooks\LinkHandler';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['modifyParams_LinksRte_PostProc'][] = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hooks\LinkHandler';
	// calls removeParams($parameters, $this)
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['removeParams_PostProc'][] = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hook\LinkHandler';
	// calls modifyParamsLinksDb($parameters, $this)
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['modifyParams_LinksDb_PostProc'][] = 'EXT:bootstrap_core/Classes/Hooks/LinkHandler.php:&Simplicity\BootstrapCore\Hook\LinkHandler';
	// calls modifyParamsLinksRte($parameters, $this)

	// --- Hook in sysext/rtehtmlarea/Classes/BrowseLinks.php
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][] = 'EXT:bootstrap_core/Classes/Hooks/ElementBrowser.php:Simplicity\BootstrapCore\Hooks\ElementBrowser';

	// --- Hook in sysext/recordlist/Classes/Browser/ElementBrowser.php
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook'][] = 'EXT:bootstrap_core/Classes/Hooks/ElementBrowser.php:Simplicity\BootstrapCore\Hooks\ElementBrowser';
}


// --- Add default page TSconfig ---
//
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tsconfig.ts">');


// --- Add FontAwesome page TSconfig ---
//
// only if enabled in extension configuration
if ( isset($extConf['enableFontIcons']) && $extConf['enableFontIcons'] == 1 ) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/lib/fontawesome/tsconfig.ts">');
}

?>