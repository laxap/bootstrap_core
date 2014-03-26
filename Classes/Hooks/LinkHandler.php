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
 * Linkhandler to for special twitter bootstrap elements.

 * @package BootstrapCore
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LinkHandler {

	/**
	 * Process the link generation
	 *
	 * @param string $linktxt The link text
	 * @param array $conf Configuration
	 * @param string $linkHandlerKeyword Should be bootstrap
	 * @param string $linkHandlerValue Table and uid of the record
	 * @param string $linkParams Full link params
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer
	 * @return string
	 */
	public function main($linktxt, $conf, $linkHandlerKeyword, $linkHandlerValue, $linkParams, &$contentObjectRenderer) {

		// build the typolink when the requested record and the nessesary cofiguration are available
		$cObjectRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$cObjectRenderer->start(null);
		//$type = $cObjectRenderer->data['type'];

		// get link params after handler keyword and value
		$typo3LinkParams = trim(substr($linkParams,strlen($linkHandlerKeyword . ":" . $linkHandlerValue)));

		// get key-value array from linkHandlerValue
		$linkValueArray = $this->getKeyValueArray($linkHandlerValue);
		// if type not set, return normal link
		if ( ! isset($linkValueArray['type']) ) {
			$cObjectRenderer->typoLink($linktxt, array('parameter' => $linkParams));
		}

		$lconf = array();
		// based on link type
		switch ( $linkValueArray['type'] ) {
			case 'modal':
				$lconf = array('parameter' => $GLOBALS['TSFE']->id . ' ' . $typo3LinkParams,
							   'section' => $linkValueArray['value'],
							   'ATagParams' => 'data-toggle="modal"');
				break;

			case 'popover':
				// popover tag attributes
				$aTagParams = 'data-toggle="popover"';
				$aTagParams .= ' data-placement="' . $linkValueArray['pos'] . '"';
				$aTagParams .= ' data-trigger="' . $linkValueArray['trigger'] . '"';
				$aTagParams .= ' data-content="' . rawurldecode($linkValueArray['desc']) . '"';
				// link config
				$lconf = array('parameter' => $GLOBALS['TSFE']->id . ' ' . $typo3LinkParams,
							   'ATagParams' => $aTagParams);
				break;

			case 'button':
				// link config
				$lconf = array('parameter' => $linkValueArray['btnurl'] . ' ' . $typo3LinkParams);
				break;

			// if unknown type, return normal link
			default:
				$cObjectRenderer->typoLink($linktxt, array('parameter' => $linkParams));
				break;
		}

		// build the full link to the record
		//$link = $cObjectRenderer->typoLink($linktxt, array('parameter' => $detailPageUid, 'additionalParams' => '&bootstrap='.$linkHandlerValue));
		$link = $contentObjectRenderer->typoLink($linktxt, $lconf);
		return $link;
	}


	/**
	 * @param array $parameters
	 * @param mixed $parentObj
	 * @return array
	 */
	public function modifyParamsLinksRte($parameters, $parentObj) {
		$href = $parameters['url'];
		$tagCode = $parameters['tagCode'];
		$error = $parameters['error'];
		if ( substr($href,0,10) == 'bootstrap:' ) {
			$external = 0;
		} else {
			$external = $parameters['external'];
		}
		$bTag = '<a href="' . htmlspecialchars($href) . '"' . ($tagCode[2] && $tagCode[2] != '-' ? ' target="' . htmlspecialchars($tagCode[2]) . '"' : '') . ($tagCode[3] && $tagCode[3] != '-' ? ' class="' . htmlspecialchars($tagCode[3]) . '"' : '') . ($tagCode[4] ? ' title="' . htmlspecialchars($tagCode[4]) . '"' : '') . ($external ? ' data-htmlarea-external="1"' : '') . ($error ? ' rteerror="' . htmlspecialchars($error) . '" style="background-color: yellow; border:2px red solid; color: black;"' : '') . '>';
		$eTag = '</a>';
		return $bTag . $parentObj->TS_links_rte($parentObj->removeFirstAndLastTag($parameters['currentBlock'])) . $eTag;
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
}


?>