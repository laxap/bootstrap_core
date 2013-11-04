<?php
namespace Simplicity\BootstrapCore\Hooks;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2013 Simplicity GmbH <typo3@simple.ch>
	 *  All rights reserved
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 * Link wizard handler for twitter bootstrap elements.

 * @package BootstrapCore
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ElementBrowser implements \TYPO3\CMS\Core\ElementBrowser\ElementBrowserHookInterface {

	/**
	 * @var \TYPO3\CMS\Recordlist\Browser\ElementBrowser
	 */
	protected $parentObject;

	/**
	 * @var array
	 */
	protected $additionalParams;

	/**
	 * @var array
	 */
	protected $keyValues;

	/**
	 * @var array
	 */
	protected $linkTypes;

	/**
	 * @var array
	 */
	protected $styleOptions;

	/**
	 * @var array
	 */
	protected $btnSizeOptions;

	/**
	 * @var array
	 */
	protected $popoverPos = array('top', 'right', 'bottom', 'left');

	/**
	 * @var array
	 */
	protected $popoverTrigger = array('hover', 'click', 'focus');

	/**
	 * @var string
	 */
	protected $tabStyle       = 'cursor: pointer; padding: 5px 10px; border-top: 1px solid #999; border-right: 1px solid #999; border-left: 1px solid #999; background-color: #ccc';

	/**
	 * @var string
	 */
	protected $tabActiveStyle = 'cursor: pointer; padding: 5px 10px; border-top: 1px solid #999; border-right: 1px solid #999; border-left: 1px solid #999; background-color: #fff';

	/**
	 * @var string
	 */
	protected $siteUrl = '';

	/**
	 * @var string
	 */
	protected $hrefShort = '';

	/**
	 * @var array
	 */
	protected $extConf = array();

	/**
	 * Initializes the hook object
	 *
	 * @param \TYPO3\CMS\Recordlist\Browser\ElementBrowser $parentObject  Parent browse_links object
	 * @param array $additionalParameters Additional parameters
	 * @return void
	 */
	public function init($parentObject, $additionalParameters) {
		$this->parentObject = &$parentObject;
		$this->additionalParams = $additionalParameters;
		$this->keyValues = array();

		//$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_core']);

		// link types
		$this->linkTypes = array('modal'   => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.linkType.I.0', 'bootstrap_core'),
								 'popover' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.linkType.I.1', 'bootstrap_core'),
								 'button'  => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.linkType.I.2', 'bootstrap_core'));

		// style options
		$this->styleOptions = array(''            => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.0', 'bootstrap_core'),
									'btn-primary' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.1', 'bootstrap_core'),
									'btn-info'    => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.2', 'bootstrap_core'),
									'btn-warning' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.3', 'bootstrap_core'),
									'btn-danger'  => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.4', 'bootstrap_core'),
									'btn-default' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.5', 'bootstrap_core'),
									'btn-link'    => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style.I.6', 'bootstrap_core'));

		// button size options
		$this->btnSizeOptions = array(''          => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.btnsize.I.0', 'bootstrap_core'),
									  'btn-lg' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.btnsize.I.1', 'bootstrap_core'),
									  'btn-sm' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.btnsize.I.2', 'bootstrap_core'),
									  'btn-xs'  => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.btnsize.I.3', 'bootstrap_core'));
	}

	/**
	 * Adds new items to the currently allowed ones and returns them
	 *
	 * @param array $currentlyAllowedItems  Currently allowed items
	 * @return array Currently allowed items plus added items
	 */
	public function addAllowedItems($currentlyAllowedItems) {
		$currentlyAllowedItems[] = 'bootstrap';
		return $currentlyAllowedItems;
	}

	/**
	 * Modifies the menu definition and returns it
	 *
	 * @param array $menuDefinition 	Menu definition
	 * @return array Modified menu definition
	 */
	public function modifyMenuDefinition($menuDefinition) {
		if (in_array('bootstrap', $this->parentObject->allowedItems)) {
			$menuDefinition['bootstrap']['isActive'] = $this->parentObject->act=='bootstrap';
			$menuDefinition['bootstrap']['label'] = 'Bootstrap';
			$menuDefinition['bootstrap']['url'] = '#';
			$menuDefinition['bootstrap']['addParams'] = 'onclick="jumpToUrl(\'' . htmlspecialchars(('?act=bootstrap&mode=' . $this->parentObject->mode . '&bparams=' . $this->parentObject->bparams)) . '\');return false;"';
		}
		return $menuDefinition;
	}

	/**
	 * Returns a new tab for the browse links wizard
	 *
	 * @param  string $act Current link selector action
	 * @return string A tab for the selected link action
	 */
	public function getTab($act) {
		global $LANG;

		// not a bootstrap link?
		if ( $act !== 'bootstrap' ) {
			return '';
		}

		if ($this->isRteMode()) {
			if ( isset($this->parentObject->classesAnchorJSOptions) ) {
				$this->parentObject->classesAnchorJSOptions[$act] = @$this->parentObject->classesAnchorJSOptions['page'];
			}
		}

		// default tab: modal
		if ( ! isset($this->keyValues['type']) ) {
			$this->keyValues['type'] = 'modal';
		}

		$content = '<!-- Twitter Bootstrap Links //-->';
		$content .= $this->getJSCode();
		$content .= '<form action="" name="lurlform" id="lurlform">';

		// get (sub)tabs for bootstrap links
		$content .= $this->getSubTabs();

		// tab content 1: modal
		$content .= $this->getModalTabContent();
		// tab content 2: popover
		$content .= $this->getPopoverTabContent();
		// tab content 3: button
		$content .= $this->getButtonTabContent();

		// default additional fields
		$content .= $this->getAdditionalDefaultFields();

		$content .= '</form>';

		return $content;
	}

	/**
	 * Checks the current URL and determines what to do
	 *
	 * @param string $href
	 * @param string $siteUrl
	 * @param array $info
	 * @return array
	 */
	public function parseCurrentUrl($href, $siteUrl, $info) {
		// Depending on link and setup the href string can contain complete absolute link
		if (substr($href,0,strlen($siteUrl)) == $siteUrl) {
			$hrefShort = substr($href,strlen($siteUrl));
		} else {
			$hrefShort = $href;
		}
		$this->siteUrl = $siteUrl;
		$this->hrefShort = $hrefShort;

		// bootstrap link?
		if (substr($hrefShort,0,10) != 'bootstrap:') {
			return $info;
		}

		// get key/value array with parameters
		$this->keyValues = $this->getKeyValueArray(substr($hrefShort,10));

		// based on bootstrap link type
		switch ( $this->keyValues['type'] ) {
			case 'modal':
				$info['value'] = $this->keyValues['value'];
				break;
			case 'popover':
				break;
			case 'button':
				break;

			// unknown type, return info
			default:
				return $info;
		}

		// is bootstrap link
		$info['act'] = 'bootstrap';
		return $info;
	}


	/**
	 * Returns true if the current linkwizard is from RTE.
	 *
	 * @return bool
	 */
	protected function isRteMode() {
		if ( $this->parentObject->mode == 'rte' ) {
			return true;
		}
		return false;
	}

	/**
	 * Returns the current page id
	 *
	 * @return integer
	 */
	protected function getPageId() {
		if ($this->isRteMode()) {
			$confParts = explode (':',$this->parentObject->RTEtsConfigParams);
			return $confParts[5];
		} else {
			$P = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('P');
			return $P['pid'];
		}
	}


	/**
	 * @param string $valueString
	 * @return array
	 */
	protected function getKeyValueArray($valueString) {
		$keyValuePairs = explode('&', $valueString);
		$keyValueArray = array();
		if ( is_array($keyValuePairs) ) {
			foreach ( $keyValuePairs as $keyValue ) {
				list($key,$value) = explode('=', $keyValue);
				$keyValueArray[$key] = $value;
			}
		}
		return $keyValueArray;
	}

	/**
	 * @return string
	 */
	protected function getJSCode() {
		$content = '
	    <script language="javascript">
	        var bootstrapLinkTypes = new Array("modal", "popover", "button");
	    	function selectBootstrapTab(key) {
	    	    var type = "";
	    		for (i=0;i<bootstrapLinkTypes.length;i++) {
	    			type = bootstrapLinkTypes[i];
	    			if ( type == key ) {
	    				document.getElementById("bootstrapTab-" + type).style.backgroundColor="#fff";
	    	    		document.getElementById("bootstrapTabArea-" + type).style.display="block";
	    		    } else {
	    				document.getElementById("bootstrapTab-" + type).style.backgroundColor="#ccc";
	    	    		document.getElementById("bootstrapTabArea-" + type).style.display="none";
	    		    }
	    		}
	    	}

			function link_bootstrap() {
	    		var linkStyle = document.lurlform.lstyle.options[document.lurlform.lstyle.selectedIndex].value;
	    		var btnSizeClass = document.lurlform.lbtnsize.options[document.lurlform.lbtnsize.selectedIndex].value;
	    		if ( linkStyle != "" ) {
	    			if ( linkStyle != "btn" ) {
	    			   linkStyle = "btn " + linkStyle;
	    			}
	    			linkStyle = linkStyle + " " + btnSizeClass;
	    			browse_links_setClass(linkStyle);
 				}
 				browse_links_setTitle(document.lurlform.ltitle.value);
				if (cur_href!="http://" && cur_href!="mailto:") {
					plugin.createLink(cur_href,cur_target,cur_class,cur_title,additionalValues);
				}
				return false;
			}
	    </script>';

		return $content;
	}

	/**
	 * @return string
	 */
	protected function getSubTabs() {
		$content = '<div class="tabHeaderBootstrap" style="border-bottom: 1px solid #333; padding-bottom: 5px;">';
		// foreach link type
		foreach ( $this->linkTypes as $linkTypeKey => $linkTypeTitle ) {
			$tabId = 'bootstrapTab-' . $linkTypeKey;
			if ( $this->keyValues['type'] == $linkTypeKey ) {
				$content .= '<a id="' . $tabId . '" style="' . $this->tabActiveStyle . '" onclick="selectBootstrapTab(\'' . $linkTypeKey . '\')">';
			} else {
				$content .= '<a id="' . $tabId . '" style="' . $this->tabStyle . '" onclick="selectBootstrapTab(\'' . $linkTypeKey . '\')">';
			}

			$content .= $linkTypeTitle . '</a>';
		}
		$content .= '</div>';

		return $content;
	}

	/**
	 * @return string
	 */
	protected function getModalTabContent() {
		// show content
		$content = '
			  <table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
			  <tbody><tr>
				<td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.modalid', 'bootstrap_core') . '&nbsp;</td>
				<td>
					<input type="text" name="lurl" style="width:192px;" value="' . trim($this->keyValues['value']) . '">
					<input type="submit" value="Set Link"
						onclick="browse_links_setHref(\'bootstrap:type=modal&value=\' + document.lurlform.lurl.value);  browse_links_setAdditionalValue(\'data-htmlarea-external\', \'\'); return link_bootstrap();">
				</td>
			  </tr></tbody>
			  </table>';

		return $this->getTabContentStartDiv('modal') . $content .  '</div>';
	}

	/**
	 * @return string
	 */
	protected function getPopoverTabContent() {
		// popover position
		$poPosOptions = '';
		foreach ( $this->popoverPos as $pos ) {
			if ( $this->keyValues['pos'] == $pos) {
				$poPosOptions .= '<option selected="selected">' . $pos . '</option>';
			} else {
				$poPosOptions .= '<option>' . $pos . '</option>';
			}
		}
		// popover trigger
		$poTriggerOptions = '';
		foreach ( $this->popoverTrigger as $trigger ) {
			if ( $this->keyValues['trigger'] == $trigger) {
				$poTriggerOptions .= '<option selected="selected">' . $trigger . '</option>';
			} else {
				$poTriggerOptions .= '<option>' . $trigger . '</option>';
			}
		}
		// show content
		$content = '
			  <table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
			  <tbody><tr>
				<td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.desc', 'bootstrap_core') . '&nbsp;</td>
				<td>
					<input type="text" name="lpopoverdesc" style="width:252px;" value="' . rawurldecode($this->keyValues['desc']) . '">
				</td>
				<td><input type="submit" value="Set Link"
						onclick="browse_links_setHref(\'bootstrap:type=popover&desc=\' + encodeURI(document.lurlform.lpopoverdesc.value) + \'&pos=\' + document.lurlform.lpopoverpos.value + \'&trigger=\' + document.lurlform.lpopovertrigger.value);  browse_links_setAdditionalValue(\'data-htmlarea-external\', \'\'); return link_bootstrap();">
				</td>
			  </tr><tr>
				<td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.position', 'bootstrap_core') . '&nbsp;</td>
				<td>
					<select name="lpopoverpos">' .  $poPosOptions . '</select>
				</td>
			  </tr><tr>
				<td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.trigger', 'bootstrap_core') . '&nbsp;</td>
				<td>
					<select name="lpopovertrigger">' .  $poTriggerOptions . '</select>
				</td>
			  </tr></tbody>
			  </table>';

		return $this->getTabContentStartDiv('popover') . $content .  '</div>';
	}

	/**
	 * @return string
	 */
	protected function getButtonTabContent() {
		// show content
		// if no btnurl set yet, check if href set from other link type
		if ( ! isset($this->keyValues['btnurl'])  ) {
			// remove ?id=
			if ( trim($this->hrefShort) != '' && substr($this->hrefShort,0,4) == '?id=' ) {
				$btnUrl = substr($this->hrefShort,4);
			} else {
				$btnUrl = '';
			}
		} else {
			$btnUrl = $this->keyValues['btnurl'];
		}

		$content = '
			  <table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
			  <tbody><tr>
				<td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.urloruid', 'bootstrap_core') . '</td>
				</tr><tr>
				<td>
					<input type="text" name="lbtnurl" style="width:192px;" value="' . $btnUrl . '">
				</td>
				<td>&nbsp;<input type="submit" value="Set Link"
						onclick="browse_links_setHref(\'bootstrap:type=button&btnurl=\' + document.lurlform.lbtnurl.value);  browse_links_setAdditionalValue(\'data-htmlarea-external\', \'\'); return link_bootstrap();">
				</td></tr></tbody></table>';

		return $this->getTabContentStartDiv('button') . $content .  '</div>';
	}

	/**
	 * @param $type
	 * @return string
	 */
	protected function getTabContentStartDiv($type) {
		// block or none
		$tabStyleDisplay = ($this->keyValues['type'] == $type)?'block':'none';
		return '<div id="bootstrapTabArea-' . $type . '" style="display: ' . $tabStyleDisplay . '; border: 1px solid #999; padding: 5px;">';
	}

	/**
	 * @return string
	 */
	protected function getAdditionalDefaultFields() {
		// get current link classes
		$linkStyle = '';
		$btnSize = '';
		$classes = explode(' ', trim($this->parentObject->curUrlArray['class']));
		if ( ! is_array($classes) || $classes[0] != 'btn' ) {
			$classes = $this->parentObject->curUrlArray['class'];
		} else {
			$linkStyle = $classes[1];
			if ( isset($classes[2]) ) {
				$btnSize = $classes[2];
			}
		}
		// get current link title
		$curTitle = $this->parentObject->curUrlArray['title'];

		// style options
		$styleOptions = '';
		foreach ( $this->styleOptions as $styleKey => $styleText ) {
			if ( $linkStyle == $styleKey) {
				$styleOptions .= '<option value="' . $styleKey. '" selected="selected">' . $styleText . '</option>';
			} else {
				$styleOptions .= '<option value="' . $styleKey. '">' . $styleText . '</option>';
			}
		}
		// button size options (if button style)
		$btnSizeOptions = '';
		foreach ( $this->btnSizeOptions as $sizeKey => $sizeText ) {
			if ( $btnSize == $sizeKey) {
				$btnSizeOptions .= '<option value="' . $sizeKey. '" selected="selected">' . $sizeText . '</option>';
			} else {
				$btnSizeOptions .= '<option value="' . $sizeKey. '">' . $sizeText . '</option>';
			}
		}
		$content = '
			  <table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
			  <tbody>
			  <tr>
			    <td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.title', 'bootstrap_core') . '&nbsp;</td>
			    <td><input type="text" name="ltitle" style="width:192px;" value="' . $curTitle . '"></td>
			  </tr>
			  <tr>
			    <td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.style', 'bootstrap_core') . '&nbsp;</td>
			    <td><select name="lstyle">' . $styleOptions . '</select></td>
			  </tr>
			  <tr>
			    <td>' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.size', 'bootstrap_core') . '&nbsp;</td>
			    <td><select name="lbtnsize">' . $btnSizeOptions . '</select> (only for button styles)</td>
			  </tr>
			  </tbody>
			  </table>';


		return '<div style="border: 1px solid #ccc; padding: 0.5em; margin-top: 1em;">' . $this->parentObject->barheader(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('browselinks.linkstyle', 'bootstrap_core')) .  $content . '</div>';
	}

}

?>