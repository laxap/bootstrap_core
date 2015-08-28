<?php
namespace Simplicity\BootstrapCore\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Pascal Mayer <typo3(a)bsdist.ch>
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
	 * Create .htaccess file.
	 */
	public function createHtaccessFile() {
        $htAccessFile = GeneralUtility::getFileAbsFileName(".htaccess");
		if ( file_exists($htAccessFile) ) {
            $this->addMessage(FlashMessage::NOTICE, '.htaccess not created', ' File .htaccess exists already in the root directory.');
			return;
		}
		$htAccessContent = GeneralUtility::getUrl(PATH_site .'typo3_src/_.htaccess');
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