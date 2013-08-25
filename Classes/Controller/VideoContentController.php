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
 * @package slider_pack
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class VideoContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	const VIDEO_TYPE_YOUTUBE = 'Youtube';
	const VIDEO_TYPE_VIMEO = 'Vimeo';

	/**
	 * Action show
	 *
	 * @return void
	 */
	public function showAction() {
		// get content object
		$contentObj = $this->configurationManager->getContentObject();
		// initial check
		if ( ! is_object($contentObj) || ! is_array($contentObj->data) ) {
			$this->view->assign('videos', 0);
			return;
		}

		// get content data
		$data = $contentObj->data;
		// check if video links defined
		if ( trim($data['image_link']) == '' ) {
			$this->view->assign('videos', 0);
			return;
		}

		// init default values
		// with/height from settings
		$width = $this->settings['default']['width'];
		$height = $this->settings['default']['height'];
		// caption position
		$captionPosition = '';

		// set pure ts settings
		// link to platform icon
		$this->view->assign('extLinkIcon',$this->settings['default']['extLinkIcon']);

		// get array with videos (in cols, with captions)
		$videoArray = $this->getVideoArray($data['image_link'], $data['imagecaption'], $data['imagecols']);
		if ( $videoArray === false ) {
			$this->view->assign('videos', 0);
			return;
		}

		$spanNum = 0;
		// if more than one video cols, get twitter bootstrap spanX
		if ( count($videoArray) > 1 ) {
			$spanNum = floor(12 / count($videoArray));
		} else {
			// only one col, remove col
			$videoArray = $videoArray[1];
		}

		// get set values
		if ( $data['imagewidth'] ) {
			$width =  $data['imagewidth'];
		}
		if ( $data['imageheight']  ) {
			$height =  $data['imageheight'];
		}
		if ( $data['imagecaption_position']  ) {
			$captionPosition =  $data['imagecaption_position'];
		}

		// assign script configuration array
		$this->view->assign('videos', $videoArray);
		// width and height (if set)
		$this->view->assign('width', $width);
		$this->view->assign('height', $height);
		// caption position
		$this->view->assign('captionPosition', $captionPosition);
		// data
		$this->view->assign('data', $data);
		// span
		$this->view->assign('spanNum', $spanNum);
	}


	/**
	 * @param $links
	 * @param $captions
	 * @param $imageCols
	 * @return array
	 */
	protected function getVideoArray($links, $captions, $imageCols) {
		// video links, as array
		$videoLinkArray = explode("\n", trim($links));

		// captions
		// no trim, first may have no caption but wants to use an empty one (alignment)
		$captionArray = explode("\n", $captions);

		// build array
		$currentCol = 1;
		$videoCount = 0;
		$videoConfArray = array();
		foreach ( $videoLinkArray as $index => $videoLink ) {
			// get type of video (platform)
			$videoType = $this->getVideoType($videoLink);
			// get video link based on video type
			switch ( $videoType ) {
				case $this::VIDEO_TYPE_YOUTUBE:
					$videoLinkConfig = $this->getYouTubeLink($videoLink);
					break;
				case $this::VIDEO_TYPE_VIMEO:
					$videoLinkConfig = $this->getVimeoLink($videoLink);
					break;
				// unknown
				default:
					$videoLinkConfig = false;
					break;
			}

			// if no link, continue
			if ( $videoLinkConfig == false ) {
				continue;
			}
			$videoCount++;

			// get video caption
			// without trim, captions with a space should be outputed (alignment)
			if ( isset($captionArray[$index]) ) {
				$caption = str_replace("\r", "", $captionArray[$index]);
				$videoLinkConfig['caption'] = $caption;
			}

			// add to array
			$videoConfArray[$currentCol][] = $videoLinkConfig;

			// check column
			if ( $imageCols > 1 ) {
				// inc column
				$currentCol++;
				// check if still in num of cols
				if ( $currentCol > $imageCols ) {
					$currentCol = 1;
				}
			}
		}

		return $videoConfArray;
	}


	/**
	 * @param $videoLink
	 * @return string
	 */
	protected function getVideoType($videoLink) {
		// youtube share link
		if ( strpos($videoLink, 'youtu.be/') ) {
			return $this::VIDEO_TYPE_YOUTUBE;
		}
		// vimeo
		if ( strpos($videoLink, 'vimeo.com/') ) {
			return $this::VIDEO_TYPE_VIMEO;
		}
		return false;
	}


	protected function getYouTubeLink($videoLink) {
		// get video id
		$pos = strpos($videoLink, 'youtu.be/');
		$videoId = substr($videoLink, $pos + 9);
		if ( trim($videoId) == '' ) {
			return false;
		}
		$videoConfig = array('embedLink' => $this->settings['youtube']['embedLinkBase'] . $videoId,
		                     'extLink'   => $this->settings['youtube']['extLinkBase'] .$videoId);

		// embed configuration (don't show title, byline and badge)
		if ( isset($this->settings['youtube']['embedLinkAddOn']) ) {
			$videoConfig['embedLink'] .= $this->settings['youtube']['embedLinkAddOn'];
		}
		return $videoConfig;
	}

	protected function getVimeoLink($videoLink) {
		// get video id
		$pos = strpos($videoLink, 'vimeo.com/');
		$videoId = substr($videoLink, $pos + 10);
		if ( trim($videoId) == '' ) {
			return false;
		}
		$videoConfig = array('embedLink' => $this->settings['vimeo']['embedLinkBase'] . $videoId,
							 'extLink'   => $this->settings['vimeo']['extLinkBase'] .$videoId);

		// embed configuration (don't show title, byline and badge)
		if ( isset($this->settings['vimeo']['embedLinkAddOn']) ) {
			$videoConfig['embedLink'] .= $this->settings['vimeo']['embedLinkAddOn'];
		}
		return $videoConfig;
	}

}
?>