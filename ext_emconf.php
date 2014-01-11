<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "bootstrap_core"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Bootstrap for TYPO3',
	'description' => 'Gridelements for columns, tabs, accordion and slider. Field customizations, page TSconfig and typoscript configurations. Link handler extension.',
	'category' => 'misc',
	'author' => 'Pascal Mayer',
	'author_email' => 'typo3@simple.ch',
	'author_company' => 'Simplicity GmbH',
	'shy' => '',
	'priority' => 'bottom',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => 'tt_content',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.7.1',
	'constraints' => array(
		'depends' => array(
			'extbase' => '6.1.0-',
			'fluid' => '6.1.0-',
			'typo3' => '6.1.0-',
			'gridelements' => '2.0.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'realurl' => '1.12.0-',
		),
	),
);

?>