<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "bootstrap_core"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Bootstrap for TYPO3',
	'description' => 'Bootstrap specific changes for the frontend rendering of content elements. Adds sectionframe options and layout options for images and menus.',
	'category' => 'fe',
	'author' => 'Pascal Mayer',
	'author_email' => 'typo3@bsdist.ch',
	'author_company' => '',
	'shy' => '',
	'version' => '1.2.2',
	'priority' => 'top',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => 'tt_content',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.1.99',
			'realurl' => '1.12.0-0.0.0',
		),
		'conflicts' => array(
			'bootstrap_package' => '',
		),
	),
);

?>