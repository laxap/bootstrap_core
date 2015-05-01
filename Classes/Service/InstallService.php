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
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;

class InstallService {

    /**
     * @var string
     */
	protected $extKey = 'bootstrap_core';

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objManager = null;

    /**
     * @var \TYPO3\CMS\Core\Messaging\FlashMessageQueue
     */
    protected $flashMsgQueue = null;

	/**
	 * @param string $extension
	 */
	public function generateConfigFiles($extension = NULL){
		if($extension == $this->extKey) {
            // create object manager and msg queue
            $this->objManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

            /** @var \TYPO3\CMS\Core\Messaging\FlashMessageService $flashMsgService  */
            $flashMsgService = $this->objManager->get('TYPO3\\CMS\\Core\\Messaging\\FlashMessageService');
            if ( VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version ) >= 7000000 ) {
                $this->flashMsgQueue = $flashMsgService->getMessageQueueByIdentifier('extbase.flashmessages.tx_extensionmanager_tools_extensionmanagerextensionmanager');
            } else {
                $this->flashMsgQueue = $flashMsgService->getMessageQueueByIdentifier('core.template.flashMessages');
            }

            // create file(s)
			$this->createHtaccessFile();
		}
	}

	/**
	 * Create realurl_conf.php file. Not needed.
	 */
	public function createRealUrlConfig() {
		$realUrlConfigFile = GeneralUtility::getFileAbsFileName("typo3conf/realurl_conf.php");
		if ( file_exists($realUrlConfigFile) ) {
            $this->addMessage(FlashMessage::NOTICE, 'realurl_config.php not created', 'File realurl_conf.php exists already in directory typo3conf/');
			return;
		}
		$realUrlConfigContent = GeneralUtility::getUrl(ExtensionManagementUtility::extPath($this->extKey).'/Configuration/RealUrl/realurl_conf.php');
		GeneralUtility::writeFile($realUrlConfigFile, $realUrlConfigContent, TRUE);
        $this->addMessage(FlashMessage::OK,  'realurl configuration created', 'File realurl_conf.php was created in directory typo3conf/.');
	}

	/**
	 * Create .htaccess file.
	 */
	public function createHtaccessFile() {
        $htAccessFile = GeneralUtility::getFileAbsFileName(".htaccess");
		if ( file_exists($htAccessFile) ) {
            $this->addMessage(FlashMessage::NOTICE, '.htaccess not created', ' File .htaccess exists already in the root directory.');
			return;
		}
		$htAccessContent = GeneralUtility::getUrl(ExtensionManagementUtility::extPath($this->extKey).'/Configuration/RealUrl/.htaccess');
		GeneralUtility::writeFile($htAccessFile, $htAccessContent, TRUE);
        $this->addMessage(FlashMessage::OK,  '.htaccess file created', 'File .htaccess was created in the root directory.');
	}


    /**
     * @param int $type
     * @param string $title
     * @param string$text
     */
    protected function addMessage($type, $title, $text) {
        /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $message */
        $message = $this->objManager->get('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, $title, $type, true );
        $this->flashMsgQueue->addMessage($message);

    }

}