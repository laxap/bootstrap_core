<?php
namespace Simplicity\BootstrapCore\Service;

/***************************************************************
 *
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2014 Simplicity GmbH, http://www.simple.ch
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;

class InstallService {

	protected $extKey = 'bootstrap_core';

	/**
	 * @param string $extension
	 */
	public function generateConfigFiles($extension = NULL){
		if($extension == $this->extKey) {
			$this->createHtaccessFile();
			$this->createRealUrlConfig();
		}
	}

	/**
	 *
	 */
	public function createRealUrlConfig() {
		$realUrlConfigFile = GeneralUtility::getFileAbsFileName("typo3conf/realurl_conf.php");
		if ( file_exists($realUrlConfigFile) ) {
			$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				'There is already a file typo3conf/realurl_conf.php.<br>'
				. 'An example configuration is located at: <strong>typo3conf/ext/bootstrap_core/Configuration/RealUrl/realurl_conf.php</strong>',
				'File realurl_config.php already exists',
				FlashMessage::NOTICE, true
			);
			FlashMessageQueue::addMessage($message);
			return;
		}

		$realUrlConfigContent = GeneralUtility::getUrl(ExtensionManagementUtility::extPath($this->extKey).'/Configuration/RealUrl/realurl_conf.php');
		GeneralUtility::writeFile($realUrlConfigFile, $realUrlConfigContent, TRUE);

		$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
			'For RealURL a file realurl_conf.php was created in directory typo3conf/.',
			'RealURL Configuration File created.',
			FlashMessage::OK, true
		);
		FlashMessageQueue::addMessage($message);
	}

	/**
	 *
	 */
	public function createHtaccessFile() {
		$htAccessFile = GeneralUtility::getFileAbsFileName(".htaccess");
		if ( file_exists($htAccessFile) ) {
			$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				'There is already a file .htaccess in the root directory.<br>'
				. 'An example configuration is located at: <strong>typo3conf/ext/bootstrap_core/Configuration/RealUrl/.htaccess</strong>',
				'File .htaccess already exists',
				FlashMessage::NOTICE, true
			);
			FlashMessageQueue::addMessage($message);
			return;
		}

		$htAccessContent = GeneralUtility::getUrl(ExtensionManagementUtility::extPath($this->extKey).'/Configuration/RealUrl/.htaccess');
		GeneralUtility::writeFile($htAccessFile, $htAccessContent, TRUE);

		$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
			'For RealURL a file .htaccess was created in the root directory.',
			'.htaccess file created.',
			FlashMessage::OK, true
		);
		FlashMessageQueue::addMessage($message);
	}

}