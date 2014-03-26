<?php
namespace Simplicity\BootstrapCore\Controller;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2013 Pascal Mayer <typo3@simple.ch>, Simplicity GmbH
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 *
 *
 * @package BootstrapCore
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ContentElementController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\CMS\Core\Resource\FileRepository
	 * @inject
	 */
	protected $sysFileReferenceRepository;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $contentObj;

	/**
	 * @return void
	 */
	public function showTextAction() {
		// check/assign content object
		if ( ! $this->assignContentObject() ) {
			return;
		}
	}

	/**
	 * @return void
	 */
	public function showTextImageAction() {
		// check/assign content object
		if ( ! $this->assignContentObject() ) {
			return;
		}
		// check/assign content object
		if ( ! $this->assignImages() ) {
			return;
		}
	}


	/** @cond
	 *  --- Internal methods ---
	 * @endcond
	 */

	/**
	 * @return bool
	 */
	protected function assignContentObject() {
		// get content object
		$this->contentObj = $this->configurationManager->getContentObject();
		// initial check
		if ( ! is_object($this->contentObj) || ! is_array($this->contentObj->data) ) {
			$this->view->assign('contentData', 0);
			return false;
		}
		// set content data
		$this->view->assign('contentData', $this->contentObj->data);
		return true;
	}

	/**
	 * @return bool
	 */
	protected function assignFirstImage() {
		$images = $this->getImages();
		if ( $images === false ) {
			return false;
		}
		// if multiple added..
		foreach ( $images as $image ) {
			// assign only the first
			$this->view->assign('image', $image);
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	protected function assignImages() {
		$images = $this->getImages();
		if ( $images === false ) {
			return false;
		}
		$this->view->assign('images', $images);
		return true;
	}

	/**
	 * @return array|bool
	 */
	protected function getImages() {
		// check if cObj already fetched
		if ( ! is_object($this->contentObj) || ! is_array($this->contentObj->data) ) {
			return false;
		}
		// check if translated object
		if ( isset($this->contentObj->data['_LOCALIZED_UID']) && $this->contentObj->data['_LOCALIZED_UID'] ) {
			$uid = $this->contentObj->data['_LOCALIZED_UID'];
		} else {
			$uid = $this->contentObj->data['uid'];
		}
		// e.g. for images inline in flexform
		$images = $this->sysFileReferenceRepository->findByRelation('tt_content', 'image', $uid);
		if ( ! $images || count($images) == 0 ) {
			return false;
		}
		return $images;
	}
}
?>