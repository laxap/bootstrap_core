<?php
namespace Simplicity\BootstrapCore\Service;

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
 * Image width calculation for modified logic

 * @package BootstrapCore
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ImageWidthCalculator extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin {

	/**
	 * @var array
	 */
	var $rowFluidSpanWidth = array();

	/**
	 * @var array
	 */
	var $beLayoutColWidth = array();

	/**
	 * @var array
	 */
	var $gridElementColWidth = array();

	/**
	 * @var mixed
	 */
	var $pGridContainer = null;

	/**
	 * @param $content
	 * @param array $conf
	 * @return float
	 */
	public function getImageWidth($content, $conf) {
		// get data from original imagewidth field
		$origImageWidth = $this->cObj->data['imagewidth'];
		// get data from new field
		$allImagesSpanWidth = $this->cObj->data['tx_bootstrapcore_imageswidth'];

		// if normal rendering based on fix image width used
		if ( $allImagesSpanWidth == 0 ) return $origImageWidth;
		// set configuration
		if ( ! $this->setConfiguration($conf) ) return $origImageWidth;

		// get colPos in page template
		if ( $this->inGridElement()) {
			$colPos = $this->getGridElementColPos();
		} else {
			$colPos = $this->cObj->data['colPos'];
		}
		// get max width of content column of current page template
		$contentWidth = $this->getBackendLayoutColWidth($colPos);

		// get max width in grid element
		if ( $this->inGridElement() ) {

			// if in parent grid container, calc width in parent grid first
			if ( ! is_null($this->pGridContainer) ) {
				$gridLayout = $this->pGridContainer['tx_gridelements_backend_layout'];
				$gridColPos = $this->cObj->data['parentgrid_tx_gridelements_columns'];
				// get column grid size from flexform field
				switch ( $gridColPos ) {
					case 102:
						$colGridWidth = $this->pi_getFFvalue(\TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($this->pGridContainer['pi_flexform']), 'mdCol2');
						break;
					case 103:
						$colGridWidth = $this->pi_getFFvalue(\TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($this->pGridContainer['pi_flexform']), 'mdCol3');
						break;
					case 104:
						$colGridWidth = $this->pi_getFFvalue(\TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($this->pGridContainer['pi_flexform']), 'mdCol4');
						break;
					default:
						$colGridWidth = $this->pi_getFFvalue(\TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($this->pGridContainer['pi_flexform']), 'mdCol1');
						break;
				}
				// get in grid width
				$contentWidth = $this->getGridElementContentWidth($contentWidth, $gridLayout, $gridColPos, $colGridWidth);
			}

			// calc width in grid
			$gridLayout = $this->cObj->data['parentgrid_tx_gridelements_backend_layout'];
			$gridColPos = $this->cObj->data['tx_gridelements_columns'];
			switch ( $gridColPos ) {
				case 102:
					$colGridWidth = $this->cObj->data['parentgrid_flexform_mdCol2'];
					break;
				case 103:
					$colGridWidth = $this->cObj->data['parentgrid_flexform_mdCol3'];
					break;
				case 104:
					$colGridWidth = $this->cObj->data['parentgrid_flexform_mdCol4'];
					break;
				default:
					$colGridWidth = $this->cObj->data['parentgrid_flexform_mdCol1'];
					break;
			}

			// get in grid width
			$contentWidth = $this->getGridElementContentWidth($contentWidth, $gridLayout, $gridColPos, $colGridWidth);
		}

		// get single image width
		$imageWidth = $this->getSingleImageWidth($allImagesSpanWidth, $contentWidth);
		return round($imageWidth);
	}


	/**
	 * @param array $conf
	 * @return bool
	 */
	protected function setConfiguration($conf) {
		// array with row span width (in percentage)
		if ( ! isset($conf['rowFluidSpanWidth.']) || ! is_array($conf['rowFluidSpanWidth.']) ) {
			$this->devLog('rowFluidSpanWidth is not defined.', 3, $conf['rowFluidSpanWidth.']);
			return false;
		}
		$this->rowFluidSpanWidth = $conf['rowFluidSpanWidth.'];

		// array with page template column widths (colPos)
		if ( ! isset($conf['beLayoutColWidth.']) || ! is_array($conf['beLayoutColWidth.']) ) {
			$this->devLog('beLayoutColWidth is not defined.', 3, $conf['beLayoutColWidth.']);
			return false;
		}
		$this->beLayoutColWidth = $conf['beLayoutColWidth.'];

		// array with grid element content width (colPos)
		if ( ! isset($conf['gridElementColWidth.']) || ! is_array($conf['gridElementColWidth.']) ) {
			$this->devLog('gridElementColWidth is not defined.', 3, $conf['gridElementColWidth.']);
			return false;
		}
		$this->gridElementColWidth = $conf['gridElementColWidth.'];
		return true;
	}

	/**
	 * @return bool
	 */
	protected function inGridElement() {
		return ($this->cObj->data['tx_gridelements_container'] != 0);
	}

	/**
	 * @return int
	 */
	protected function getGridElementColPos() {
		// check if grid-in-grid
		if ( $this->cObj->data['parentgrid_colPos'] == -1 )  {
			// get parent grid container
			$pGridContainer = $this->getParentGridContainer($this->cObj->data['parentgrid_tx_gridelements_container']);
			if ( $pGridContainer && is_array($pGridContainer) ) {
				$this->pGridContainer = $pGridContainer;
				return $pGridContainer['colPos'];
			}
			// "assuming" we are in the main col
			return 0;
		}
		return $this->cObj->data['parentgrid_colPos'];
	}

	/**
	 * @param $colPos
	 * @return mixed
	 */
	protected function getBackendLayoutColWidth($colPos) {
		// get backend_layout from current page
		$curPage = $GLOBALS['TSFE']->page;
		$backendLayout = $curPage['backend_layout'];

		// if backend_layout is set to inherit, get it from rootline
		if ( $backendLayout == 0 ) {
			$rootLineArray = $GLOBALS['TSFE']->sys_page->getRootLine($curPage['uid']);
			// first page in array is current (if 3 pages, check only index 1 and 0)
			$pCount = count($rootLineArray) - 2;
			$backendLayoutNextLevel = 0;
			for ( $i = $pCount; $i >= 0; $i--) {
				$backendLayoutNextLevel = $rootLineArray[$i]['backend_layout_next_level'];
				// if a backend layout is set, stop it
				if ( $backendLayoutNextLevel > 0 ) {
					$backendLayout = $backendLayoutNextLevel;
					break;
				}
			}
			// in case still not found, use layout 1
			// TODO: definable via ts?
			if ( $backendLayout == 0 ) {
				$backendLayout = 1;
			}
		}

		// get page template col width from backend layout
		if ( ! isset($this->beLayoutColWidth[$backendLayout . '.']) ) {
			$beLayoutColWidth = $this->beLayoutColWidth['default.'][$colPos];
		} else {
			$beLayoutColWidth = $this->beLayoutColWidth[$backendLayout . '.'][$colPos];
		}

		return $beLayoutColWidth;
	}

	/**
	 * @param $contentWidth
	 * @param $gridLayout
	 * @param $gridColumn
	 * @param $colLayout
	 * @return float|int
	 */
	protected function getGridElementContentWidth($contentWidth, $gridLayout, $gridColumn, $colLayout) {
		// Column grids
		// Attention: works only because 2-col has uid 1, 3-col has uid 2 and 4-col has uid 3
		if ( $gridLayout < 4 ) {
			/*
			$gridCols = $gridLayout + 1;
			$gridElementCol = $gridColumn;
			// col nr: 102 - 101 = Col 1
			$gridElementColNr = $gridElementCol - 101;
			// layout: 3-3-6, split to array
			$gridElementColLayout = explode('-', $colLayout);
			// get colwith from array
			$gridElementColWidthSpan = $gridElementColLayout[$gridElementColNr];
			*/
			$gridElementColWidthSpan = $colLayout;

			// get width in percentage for span
			$spanWidthPercent = $this->rowFluidSpanWidth[$gridElementColWidthSpan];

			// return reduced width
			return ($contentWidth * $spanWidthPercent);
		}

		// Other grids
		$gridElementColWidthArray = $this->gridElementColWidth[$gridLayout . '.'];
		$gridElementCol = $gridColumn;
		$gridElementColWidth = $gridElementColWidthArray[$gridElementCol];

		// if percentage value
		if ( substr($gridElementColWidth, -1) == '%' ) {
			$percentWidth = \TYPO3\CMS\Core\Utility\MathUtility::convertToPositiveInteger(substr($gridElementColWidth, 0, strlen($gridElementColWidth) - 1));
			return (($percentWidth / 100) * $contentWidth);
		}
		// if negative value
		if ( $gridElementColWidth < 0 ) {
			// substract fix negative value
			return ($contentWidth - $gridElementColWidth);
		}
		// not percent, not negative => fix value, get positive int and if >0 return it
		$gridElementColWidth =  \TYPO3\CMS\Core\Utility\MathUtility::convertToPositiveInteger($gridElementColWidth);
		if ( $gridElementColWidth > 0 ) {
			return $gridElementColWidth;
		}
		// return given contentWidth
		return $contentWidth;
	}

	/**
	 * @param int $allImagesSpanWidth  Width for all images in "grid size" (1-12)
	 * @param float $contentWidth	   Width in pixel.
	 * @return float
	 */
	protected function getSingleImageWidth($allImagesSpanWidth, $contentWidth) {
		// check image orientation first?
		//$imageOrient = $this->cObj->data['imageorient'];
		// get width for images (all columns)
		$allImagesWidth = ($contentWidth * $this->rowFluidSpanWidth[$allImagesSpanWidth]);

		// get image columns
		$imageCols = $this->cObj->data['imagecols'];
		if ( $imageCols == 1 ) {
			return $allImagesWidth;
		}

		// get span width of each column (percentage)
		$imageColSpanWidth = \TYPO3\CMS\Core\Utility\MathUtility::convertToPositiveInteger(12 / $imageCols);
		$imageColWidthPercent = $this->rowFluidSpanWidth[$imageColSpanWidth];
		return ($allImagesWidth * $imageColWidthPercent);
	}

	/**
	 * @param $cObjUid
	 * @return bool
	 */
	protected function getParentGridContainer($cObjUid) {
		// create sql
		$sqlFields = 'uid, colPos, tx_gridelements_backend_layout, tx_gridelements_container, tx_gridelements_columns, pi_flexform';
		$dbTable = 'tt_content';
		$where = '`uid`=' . $cObjUid . ' AND `pid`=' . $GLOBALS['TSFE']->id . ' AND `hidden`=0 AND `deleted`=0';
		$groupBy = '';
		$orderBy = '';
		$limit = 1;
		$ret = $GLOBALS['TYPO3_DB']->exec_SELECTquery($sqlFields, $dbTable, $where, $groupBy, $orderBy, $limit);
		if ( $ret ) {
			if ( $parentGridObject = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ret) ) {
				return $parentGridObject;
			}
		}
		return false;
	}

	/**
	 * @param $title
	 * @param int $severity
	 * @param mixed $dataVar
	 */
	protected function devLog($title, $severity = 0, $dataVar = false) {
		if (TYPO3_DLOG) \TYPO3\CMS\Core\Utility\GeneralUtility::devLog($title, 'bootstrap_core', $severity, $dataVar);
	}

}
?>