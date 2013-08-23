<?php

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
 * Class for updating the db
 *
 * @author	 Pascal Mayer <typo3@simple.ch>
 */

class ext_update  {

	/**
	 * @var array
	 */
	var $recordDef = array();

	/**
	 * @var array
	 */
	var $sqlStatement = array();

	/**
	 * @var array
	 */
	var $records = array();

	/**
	 * @var array
	 */
	var $errors = array();

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string		HTML
	 */
	public function main()	{
		// set record defs
		$this->setRecordDefitions();

		// default page title
		$pageTitle = 'Insert / Update';

		// check if extension is installed
		if ( ! \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('bootstrap_core') ) {
			$content = '<br /><strong>The extension bootstrap_core needs to be installed first.</strong>';
			return $this->getPageOutput($pageTitle, $content);
		}

		// get current action
		$action = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('action');
		// insert and/or update action
		//
		if ($action == 'update') {
			// set db stmts
			$this->setDbRecordStatements();

			$content = '';

			// insert records
			$insertRecordList = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('insertRecord');
			if ( is_array($insertRecordList) ) {

				$content .= '<br><h4>Records inserted:</h4><ul>';

				foreach ( $insertRecordList as $table => $recordList ) {
					if ( is_array($recordList) ) {
						$insertCount = 0;
						foreach ( $recordList as $uid => $selected ) {
							if ( ! isset($this->sqlStatement[$table]['insert'][$uid]) ) {
								$this->errors[] = "No sql statement defined to insert uid '" . $uid . "' into table '" . $table . "'";
							} else {
								$sql = $this->sqlStatement[$table]['insert'][$uid];
								$res = $GLOBALS['TYPO3_DB']->admin_query($sql);
								if ( $res ) {
									$insertCount++;
								} else {
									$this->errors[] = "DB error inserting record with uid '" . $uid . "' into table '" . $table . "': " . $GLOBALS['TYPO3_DB']->sql_error();
								}
							}
						}
						if ( $insertCount > 0 ) {
							$content .= '<li>' . $insertCount . " records in table '" . $table. "'</li>";
						}
					}
				}
				$content .= '</ul>';
			}

			// update records
			$updateRecordList = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('updateRecord');
			if ( is_array($updateRecordList) ) {

				$content .= '<br><h4>Records updated:</h4><ul>';

				foreach ( $updateRecordList as $table => $recordList ) {
					if ( is_array($recordList) ) {
						$updateCount = 0;
						foreach ( $recordList as $uid => $selected ) {
							if ( ! isset($this->sqlStatement[$table]['update'][$uid]) ) {
								$this->errors[] = "No sql statement defined to insert uid '" . $uid . "' into table '" . $table . "'";
							} else {
								$sql = $this->sqlStatement[$table]['update'][$uid];
								$res = $GLOBALS['TYPO3_DB']->admin_query($sql);
								if ( $res ) {
									$updateCount++;
								} else {
									$this->errors[] = "DB error updating record with uid '" . $uid . "' into table '" . $table . "': " . $GLOBALS['TYPO3_DB']->sql_error();
								}
							}
						}
						if ( $updateCount > 0 ) {
							$content .= '<li>' . $updateCount . " records in table '" . $table. "'</li>";
						}
					}
				}
				$content .= '</ul>';
			}

			$pageTitle = 'Database update';
			$content .= '<br><br><b><a href="' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::linkThisScript()) . '">back</a></b>';

			return $this->getPageOutput($pageTitle, $content);
		}

		$content = '</form>';

		$content .= "<style type='text/css'>\n";
		$content .= "h3.recordType {background-color: #E5E5E5; padding: 4px 2px; font-size: 120%; }\n";
		$content .= "table.recordList th {padding: 2px 5px 2px 0; border-bottom: 1px solid #333; }\n";
		$content .= "table.recordList td {padding: 2px 10px 2px 2px; opacity: 0.8;}\n";
		$content .= "table.recordList td.center {text-align: center;}\n";
		$content .= "table.recordList tr:hover td {opacity: 1;}\n";
		$content .= "table.recordList td.diff {background-color: #f89885;}\n";
		$content .= "table.recordList td.diffnocomp {background-color: #eeeeee;}\n";
		$content .= "table.recordList td.equal {background-color: #56ee93;}\n";
		$content .= "</style>\n";

		$content .= '<h2>Insert or update pages, backend layouts and gridelements.</h2>';

		// load pages, backend_layouts and gridelements
		$this->loadPages();
		$this->loadSysTemplates();
		$this->loadBackendLayouts();
		$this->loadGridElementsRecords();

		// check (and copy) icons
		$this->checkIcons();

		// show record check
		$content .= '<form action="' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::linkThisScript()) . '" method="post">';
		$content .= '<input type="hidden" name="action" value="update" />';
		$content .= $this->getRecordLists();
		$content .= '<br /><br /><input type="submit" name="btn" value="Insert and/or update" />';
		$content .= '</form>';

		return $this->getPageOutput($pageTitle, $content);
	}


	/**
	 * Required by TYPO3 extension update framework.
	 * @return bool
	 */
	public function access() {
		return true;
	}

	/**
	 * @return string
	 */
	protected function getRecordLists() {
		$content = '';
		foreach ( $this->recordDef as $recordType => $recordTypeDef ) {
			// show record type title
			$content .= '<h3 class="recordType">' . $recordTypeDef['config']['title'] . '</h3>';

			// get fields
			$fieldList = $recordTypeDef['config']['fields'];

			// show records table
			$content .= '<table class="recordList">';
			// header
			$content .= '<tr><th>Action</th><th>uid</th>';
			foreach ( $fieldList as $col => $fieldDef ) {
				$content .= '<th>' . $col . '</th>';
			}
			$content .= '</tr>';

			foreach ( $recordTypeDef['records'] as $uid => $recordDef ) {
				// get db record
				$dbRecord = null;
				if ( isset($this->records[$recordType][$uid]) && is_array($this->records[$recordType][$uid]) ) {
					$dbRecord = $this->records[$recordType][$uid];
				}
				if ( is_null($dbRecord) ) {
					$isInsertAction = true;
				} else {
					$isInsertAction = false;
				}
				$isEqual = true;
				$rowContent = '';
				foreach ( $fieldList as $col => $fieldDef ) {
					if ( $fieldDef['notIfInsert'] && $isInsertAction ) {
						$rowContent .= '<td></td>';
						continue;
					}

					($fieldDef['style'] == 'center') ? $classes = 'center ' : $classes = '';
					if ( $fieldDef['format'] == 'pre' ) {
						$wrapStart = '<pre>'; $wrapEnd = '</pre>';
					} else {
						$wrapStart = ''; $wrapEnd = '';
					}
					if ( ! is_null($dbRecord) ) {
						// set default fix values if not defined in record definitions
						if ( ! isset($recordDef[$col]) && isset($fieldDef['fixValue']) ) {
							$recordDef[$col] = $fieldDef['fixValue'];
						}
						// prepare output value
						if ( isset($fieldDef['rcut']) ) {
							$outputValue = '<span title="' . $dbRecord[$col]. '">...' . htmlspecialchars(substr($dbRecord[$col], -(strlen($dbRecord[$col]) - (strrpos($dbRecord[$col],$fieldDef['rcut']))) )) . '</span>';
						} elseif ( isset($fieldDef['split']) ) {
							$outputValue = str_replace($fieldDef['split'], '<br>', htmlspecialchars($dbRecord[$col]));
						} else {
							$outputValue = htmlspecialchars($dbRecord[$col]);
						}
						// check if same or diff
						if ( $dbRecord[$col] != $recordDef[$col] ) {
							if ( $fieldDef['nocomp'] != true ) {
								$classDiff = ' diff';
								$isEqual = false;
							} else {
								$classDiff = ' diffnocomp';
							}
							$rowContent .= '<td class="' . $classes . $classDiff . '">' . $wrapStart . $outputValue . $wrapEnd . '</td>';
						} else {
							$rowContent .= '<td class="' . $classes . ' equal">' . $outputValue . '</td>';
						}
					} else {
						if ( isset($fieldDef['rcut']) ) {
							$outputValue = '<span title="' . $recordDef[$col]. '">...' . htmlspecialchars(substr($recordDef[$col], -(strlen($recordDef[$col]) - (strrpos($recordDef[$col],$fieldDef['rcut']))) )) . '</span>';
						} elseif ( isset($fieldDef['split']) ) {
							$outputValue = str_replace($fieldDef['split'], '<br>', htmlspecialchars($recordDef[$col]));
						} else {
							$outputValue = htmlspecialchars($recordDef[$col]);
						}
						$rowContent .= '<td>' . $wrapStart . $outputValue . $wrapEnd . '</td>';
					}
				}
				if ( ! is_null($dbRecord) ) {
					if ( ! $isEqual ) {
						$content .= '<tr><td class="center">Update<br><input type="checkbox" name="updateRecord[' . $recordType  . '][' . $uid . ']" value="1" /></td><td class="center">' . $uid . '</td>' . $rowContent . '</tr>';
					} else {
						$content .= '<tr><td class="center">Ok</td><td class="center">' . $uid . '</td>' . $rowContent . '</tr>';
					}
				} else {
					$content .= '<tr><td class="center">Insert<br><input type="checkbox" name="insertRecord[' . $recordType  . '][' . $uid . ']" value="1" checked="checked" /></td><td class="center">' . $uid . '</td>' . $rowContent . '</tr>';
				}
			}
			$content .= '<tr><td>&nbsp;</td></table>';
		}
		return $content;
	}

	/**
	 * @param $title
	 * @param $content
	 * @return string
	 */
	protected function getPageOutput($title, $content) {
		$pageContent = '<div class="tabs">';
		$pageContent .= '<ul><li><a href="" class="current">' . $title . '</a></li></ul>';
		$pageContent .= '<div class="category" style="display: block;">';

		// check for  errors
		if ( count($this->errors) > 0 ) {
			$pageContent .= '<div style="border: 1px solid #990000; padding: 0.5em; margin: 1em 0; color: #990000;">';
			$pageContent .= implode('<br>', $this->errors);
			$pageContent .= '</div>';
		}
		$pageContent .= $content . '</div>';

		$pageContent .= '</div>';
		return $pageContent;
	}

	/**
	 * @return bool
	 */
	protected function loadPages() {
		return $this->loadRecords('pages');
	}

	/**
	 * @return bool
	 */
	protected function loadSysTemplates() {
		return $this->loadRecords('sys_template');
	}

	/**
	 * @return bool
	 */
	protected function loadBackendLayouts() {
		return $this->loadRecords('backend_layout');
	}

	/**
	 * @return bool
	 */
	protected function loadGridElementsRecords() {
		return $this->loadRecords('tx_gridelements_backend_layout');
	}

	/**
	 * @param string $table
	 * @return bool
	 */
	protected function loadRecords($table) {
		// get max uid from recordDef array
		$maxUid = count($this->recordDef[$table]['records']);
		// get fieldList
		$fieldList = implode(',', array_keys($this->recordDef[$table]['config']['fields']));
		// query
		$query = 'SELECT uid,' . $fieldList . ' FROM ' . $table . ' where uid<' . ($maxUid+1);
		$ret = $GLOBALS['TYPO3_DB']->admin_query($query);
		if ( ! $ret ) {
			$this->errors[] = "DB error loading records from table '" . $table . "': " . $GLOBALS['TYPO3_DB']->sql_error();
			return false;
		}
		$this->records[$table] = array();
		while ( $record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ret) ) {
			$this->records[$table][$record['uid']] = $record;
		}
		return true;
	}

	/**
	 * @return bool
	 */
	protected function checkIcons() {
		$srcPath = PATH_site . 'typo3conf/ext/bootstrap_core/Resources/Public/Icons/gridelements';
		$destPath = PATH_site . 'uploads/tx_gridelements';
		// check directory
		if ( ! file_exists($destPath)) {
			mkdir($destPath, 0775);
		}
		foreach ( $this->recordDef['tx_gridelements_backend_layout']['records'] as $uid => $recordElem ) {
			$icon = $recordElem['icon'];

			// check file
			if ( ! file_exists($destPath . '/' .  $icon)) {
				copy($srcPath . '/' . $icon, $destPath . '/' .  $icon);
			}
		}
		return true;
	}

	/**
	 *
	 */
	protected function setRecordDefitions() {
		// record config and data definitions
		$recordDef = array();

		// --- pages ---
		//
		// config
		$recordDef['pages']['config']['title'] = 'Pages';
		$recordDef['pages']['config']['fields'] = array('pid' => array('style' => 'center', 'fixValue' => 0),
														'title' => array('nocomp' => true),
														'doktype' => array('style' => 'center'),
														'TSconfig' => array('format' => 'pre'),
														'deleted' => array('style' => 'center', 'fixValue' => 0));
		// data
		$recordDef['pages']['records'] = array(1 => array('title' => 'Home',            'doktype' => 1,   'TSconfig' => "# Include theme pageTS config\r\n<INCLUDE_TYPOSCRIPT: source=\"FILE: fileadmin/theme/default/typoscript/tsconfig.ts\">"),
											   2 => array('title' => 'Backend Layouts', 'doktype' => 254, 'TSconfig' => '') );

		// --- sys_template ---
		//
		// config
		$recordDef['sys_template']['config']['title'] = 'Sys Templates';
		$recordDef['sys_template']['config']['fields'] = array('pid' => array('style' => 'center', 'fixValue' => 1),
															   'include_static_file' => array('split' => ','),
															   'constants' => array(),
															   'config' => array() );
		$recordDef['sys_template']['records'] = array(1 => array('include_static_file' => 'EXT:css_styled_content/static/,EXT:gridelements/Configuration/TypoScript/,EXT:bootstrap_core/Configuration/TypoScript/core,EXT:bootstrap_core/Configuration/TypoScript/lib/fontawesome,EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/image/responsive', 'constants' => '<INCLUDE_TYPOSCRIPT: source="FILE: fileadmin/theme/default/typoscript/constants.ts">', 'config' => '<INCLUDE_TYPOSCRIPT: source="FILE: fileadmin/theme/default/typoscript/setup.ts">'));

		// --- backend layouts ---
		//
		// config
		$recordDef['backend_layout']['config']['title'] = 'Backend Layouts';
		$recordDef['backend_layout']['config']['fields'] = array('pid' => array('style' => 'center', 'fixValue' => 2),
																 'title' => array('nocomp' => true),
																 'config' => array('format' => 'pre', 'notIfInsert' => true),
																 'deleted' => array('style' => 'center', 'fixValue' => 0));
		// data
		$recordDef['backend_layout']['records'] = array(1 => array('title' => 'Full width',                   'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Hauptspalte\r\n					colPos = 0\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
														2 => array('title' => 'Main column, Sideboard right', 'config' => "backend_layout {\r\n	colCount = 2\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Hauptspalte\r\n					colPos = 0\r\n				}\r\n				2 {\r\n					name = Sideboard rechts\r\n					colPos =1\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
														3 => array('title' => 'Main column, Sideboard left',  'config' => "backend_layout {\r\n	colCount = 2\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Sideboard links\r\n					colPos = 2\r\n				}\r\n				2 {\r\n					name = Hauptspalte\r\n					colPos = 0\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
														4 => array('title' => 'Main column, Sideboard both',  'config' => "backend_layout {\r\n	colCount = 3\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Sideboard links\r\n					colPos = 2\r\n				}\r\n				2 {\r\n					name = Hauptspalte\r\n					colPos = 0\r\n				}\r\n				3 {\r\n					name = Sideboard rechts\r\n					colPos = 1\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
														5 => array('title' => 'Home with Slider',             'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 2\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Slider Bereich\r\n					colPos = 3\r\n				}\r\n			}\r\n		}\r\n		2 {\r\n			columns {\r\n				1 {\r\n					name = Seiteninhalt\r\n					colPos = 0\r\n				}\r\n			}\r\n		}\r\n	}\r\n}") );


		// --- gridelements ---
		//
		// config
		$recordDef['tx_gridelements_backend_layout']['config']['title'] = 'Gridelements';
		$recordDef['tx_gridelements_backend_layout']['config']['fields'] = array('pid' => array('style' => 'center', 'fixValue' => 2),
																				 'title' => array('nocomp' => true),
																				 'pi_flexform_ds_file' => array('rcut' => '/'),
																				 'config' => array('format' => 'pre', 'notIfInsert' => true),
																				 'frame' => array('style' => 'center'),
																				 'icon' => array('rcut' => '/'),
																				 'deleted' => array('style' => 'center', 'fixValue' => 0));
		// data
		$recordDef['tx_gridelements_backend_layout']['records'] = array(1 => array('title' => '2 Columns',   'frame' => '3', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_2col.xml',      'icon' => 'gridlayout_col2.gif',        'config' => "backend_layout {\r\n	colCount = 2\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Spalte links\r\n					colPos = 101\r\n				}\r\n				2 {\r\n					name = Spalte rechts\r\n					colPos = 102\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
																		2 => array('title' => '3 Columns',   'frame' => '3', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_3col.xml',      'icon' => 'gridlayout_col3.gif',        'config' => "backend_layout {\r\n	colCount = 3\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Spalte links\r\n					colPos = 101\r\n				}\r\n				2 {\r\n					name = Mittlere Spalte\r\n					colPos = 102\r\n				}\r\n				3 {\r\n					name = Spalte rechts\r\n					colPos = 103\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n"),
																		3 => array('title' => '4 Columns',   'frame' => '3', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_4col.xml',      'icon' => 'gridlayout_col4.gif',        'config' => "backend_layout {\r\n	colCount = 4\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = 1. Spalte\r\n					colPos = 101\r\n				}\r\n				2 {\r\n					name = 2. Spalte\r\n					colPos = 102\r\n				}\r\n				3 {\r\n					name = 3. Spalte\r\n					colPos = 103\r\n				}\r\n				4 {\r\n					name = 4. Spalte\r\n					colPos = 104\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n"),
																		4 => array('title' => 'Special Box', 'frame' => '3', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_container.xml', 'icon' => 'gridelement_specialbox.gif', 'config' => "backend_layout {\r\n	colCount = 2\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Container\r\n					colPos = 101\r\n				}\r\n			}\r\n		}\r\n	}\r\n}"),
																		5 => array('title' => 'Accordion',   'frame' => '2', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_accordion.xml', 'icon' => 'gridelement_accordion.gif',  'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Accordion Elements\r\n					colPos = 101\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n"),
																		6 => array('title' => 'Tabs',        'frame' => '2', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_tabs.xml',      'icon' => 'gridelement_tabs.gif',       'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Tab Elemente\r\n					colPos = 101\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n"),
																		7 => array('title' => 'Slider',      'frame' => '1', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_slider.xml',    'icon' => 'gridelement_slider.gif',     'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Slider Elemente\r\n					colPos = 101\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n"),
																		8 => array('title' => 'Modal Box',   'frame' => '1', 'pi_flexform_ds_file' => 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_modal.xml',     'icon' => 'gridelement_modal.gif',      'config' => "backend_layout {\r\n	colCount = 1\r\n	rowCount = 1\r\n	rows {\r\n		1 {\r\n			columns {\r\n				1 {\r\n					name = Modal\r\n					colPos = 101\r\n				}\r\n			}\r\n		}\r\n	}\r\n}\r\n") );

		$this->recordDef = $recordDef;
	}


	/**
	 *
	 */
	protected function setDbRecordStatements() {
		$sqlStatement = array();
		// --- pages ---
		//
		// insert sql
		$sqlStatement['pages']['insert'][1] = "INSERT INTO `pages` (`uid`, `pid`, `t3_origuid`, `tstamp`, `sorting`, `deleted`, `perms_userid`, `perms_groupid`, `perms_user`, `perms_group`, `perms_everybody`, `editlock`, `crdate`, `cruser_id`, `hidden`, `title`, `doktype`, `TSconfig`, `storage_pid`, `is_siteroot`, `php_tree_stop`, `tx_impexp_origuid`, `url`, `starttime`, `endtime`, `urltype`, `shortcut`, `shortcut_mode`, `no_cache`, `fe_group`, `subtitle`, `layout`, `url_scheme`, `target`, `media`, `lastUpdated`, `keywords`, `cache_timeout`, `cache_tags`, `newUntil`, `description`, `no_search`, `SYS_LASTCHANGED`, `abstract`, `module`, `extendToSubpages`, `author`, `author_email`, `nav_title`, `nav_hide`, `content_from_pid`, `mount_pid`, `mount_pid_ol`, `alias`, `l18n_cfg`, `backend_layout`, `backend_layout_next_level`) VALUES (1, 0, 0, 1375376840, 512, 0, 0, 0, 0, 0, 0, 0, 1374756220, 0, 0, 'Home', 1, '# Include default pageTS config for twitter bootstrap system\r\n<INCLUDE_TYPOSCRIPT: source=\\\"FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tsconfig.ts\\\">\r\n\r\n# Include theme pageTS config\r\n<INCLUDE_TYPOSCRIPT: source=\\\"FILE: fileadmin/theme/default/typoscript/tsconfig.ts\\\">', 0, 1, 0, 0, '', 0, 0, 0, 0, 0, 0, '', '', 0, 0, '', '0', 0, NULL, 0, '', 0, NULL, 0, 1375309314, NULL, '', 0, '', '', '', 0, 0, 0, 0, '', 0, 1, 2)";
		$sqlStatement['pages']['insert'][2] = "INSERT INTO `pages` (`uid`, `pid`, `t3_origuid`, `tstamp`, `sorting`, `deleted`, `perms_userid`, `perms_groupid`, `perms_user`, `perms_group`, `perms_everybody`, `editlock`, `crdate`, `cruser_id`, `hidden`, `title`, `doktype`, `TSconfig`, `storage_pid`, `is_siteroot`, `php_tree_stop`, `tx_impexp_origuid`, `url`, `starttime`, `endtime`, `urltype`, `shortcut`, `shortcut_mode`, `no_cache`, `fe_group`, `subtitle`, `layout`, `url_scheme`, `target`, `media`, `lastUpdated`, `keywords`, `cache_timeout`, `cache_tags`, `newUntil`, `description`, `no_search`, `SYS_LASTCHANGED`, `abstract`, `module`, `extendToSubpages`, `author`, `author_email`, `nav_title`, `nav_hide`, `content_from_pid`, `mount_pid`, `mount_pid_ol`, `alias`, `l18n_cfg`, `backend_layout`, `backend_layout_next_level`) VALUES (2, 0, 0, 1374757371, 256, 0, 1, 0, 31, 27, 0, 0, 1374757350, 1, 0, 'Backend Layouts', 254, '', 0, 0, 0, 0, '', 0, 0, 1, 0, 0, 0, '', '', 0, 0, '', '0', 0, '', 0, '', 0, '', 0, 0, '', '', 0, '', '', '', 0, 0, 0, 0, '', 0, 0, 0)";
		// update sql
		$sqlStatement['pages']['update'][1] = "UPDATE `pages` SET `deleted`=0, `doktype`=1, `TSconfig`='# Include theme pageTS config\r\n<INCLUDE_TYPOSCRIPT: source=\\\"FILE: fileadmin/theme/default/typoscript/tsconfig.ts\\\">' where uid=1";
		$sqlStatement['pages']['update'][2] = "UPDATE `pages` SET `deleted`=0, `doktype`=254, `TSconfig`='' where uid=2";

		// --- templates ---
		//
		$sqlStatement['sys_template']['insert'][1] = '';
		$sqlStatement['sys_template']['update'][1] = "UPDATE `sys_template` SET `include_static_file`='EXT:css_styled_content/static/,EXT:gridelements/Configuration/TypoScript/,EXT:bootstrap_core/Configuration/TypoScript/core,EXT:bootstrap_core/Configuration/TypoScript/lib/fontawesome,EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/image/responsive', constants='<INCLUDE_TYPOSCRIPT: source=\\\"FILE: fileadmin/theme/default/typoscript/constants.ts\\\">', config='<INCLUDE_TYPOSCRIPT: source=\\\"FILE: fileadmin/theme/default/typoscript/setup.ts\\\">' where uid=1";

		// --- backend layouts ---
		//
		// insert sql
		$sqlStatement['backend_layout']['insert'][1] = "INSERT INTO `backend_layout` (`uid`, `pid`, `t3_origuid`, `tstamp`, `crdate`, `cruser_id`, `hidden`, `deleted`, `sorting`, `title`, `description`, `config`, `icon`) VALUES (1, 2, 0, 1374757667, 1374757667, 1, 0, 0, 256, 'Full width', '', 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '')";
		$sqlStatement['backend_layout']['insert'][2] = "INSERT INTO `backend_layout` (`uid`, `pid`, `t3_origuid`, `tstamp`, `crdate`, `cruser_id`, `hidden`, `deleted`, `sorting`, `title`, `description`, `config`, `icon`) VALUES  (2, 2, 0, 1374757695, 1374757695, 1, 0, 0, 512, 'Main column, Sideboard right', '', 'backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Sideboard rechts\r\n\t\t\t\t\tcolPos =1\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '')";
		$sqlStatement['backend_layout']['insert'][3] = "INSERT INTO `backend_layout` (`uid`, `pid`, `t3_origuid`, `tstamp`, `crdate`, `cruser_id`, `hidden`, `deleted`, `sorting`, `title`, `description`, `config`, `icon`) VALUES (3, 2, 0, 1374757709, 1374757709, 1, 0, 0, 768, 'Main column, Sideboard left', '', 'backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Sideboard links\r\n\t\t\t\t\tcolPos = 2\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '')";
		$sqlStatement['backend_layout']['insert'][4] = "INSERT INTO `backend_layout` (`uid`, `pid`, `t3_origuid`, `tstamp`, `crdate`, `cruser_id`, `hidden`, `deleted`, `sorting`, `title`, `description`, `config`, `icon`) VALUES (4, 2, 0, 1375385474, 1374757726, 1, 0, 0, 1000000000, 'Main column, Sideboard both', '', 'backend_layout {\r\n\tcolCount = 3\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Sideboard links\r\n\t\t\t\t\tcolPos = 2\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = Sideboard rechts\r\n\t\t\t\t\tcolPos = 1\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '')";
		$sqlStatement['backend_layout']['insert'][5] = "INSERT INTO `backend_layout` (`uid`, `pid`, `t3_origuid`, `tstamp`, `crdate`, `cruser_id`, `hidden`, `deleted`, `sorting`, `title`, `description`, `config`, `icon`) VALUES (5, 2, 0, 1375377864, 1374757745, 1, 0, 0, 1280, 'Home with Slider', '', 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 2\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Slider Bereich\r\n\t\t\t\t\tcolPos = 3\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t\t2 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Seiteninhalt\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '')";
		// update sql
		$sqlStatement['backend_layout']['update'][1] = "UPDATE `backend_layout` SET `pid`=2, `deleted`=0, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}' where uid=1";
		$sqlStatement['backend_layout']['update'][2] = "UPDATE `backend_layout` SET `pid`=2, `deleted`=0, `config`='backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Sideboard rechts\r\n\t\t\t\t\tcolPos =1\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}' where uid=2";
		$sqlStatement['backend_layout']['update'][3] = "UPDATE `backend_layout` SET `pid`=2, `deleted`=0, `config`='backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Sideboard links\r\n\t\t\t\t\tcolPos = 2\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}' where uid=3";
		$sqlStatement['backend_layout']['update'][4] = "UPDATE `backend_layout` SET `pid`=2, `deleted`=0, `config`='backend_layout {\r\n\tcolCount = 3\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Sideboard links\r\n\t\t\t\t\tcolPos = 2\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Hauptspalte\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = Sideboard rechts\r\n\t\t\t\t\tcolPos = 1\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}' where uid=4";
		$sqlStatement['backend_layout']['update'][5] = "UPDATE `backend_layout` SET `pid`=2, `deleted`=0, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 2\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Slider Bereich\r\n\t\t\t\t\tcolPos = 3\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t\t2 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Seiteninhalt\r\n\t\t\t\t\tcolPos = 0\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}' where uid=5";

		// --- gridelements ---
		//
		// insert sql
		$sqlStatement['tx_gridelements_backend_layout']['insert'][1] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (1, 2, 1375379043, 1374759282, 1, 0, 256, 0, 0, '2 Columns', 3, '', 0, 'backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Spalte links\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Spalte rechts\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_2col.xml', 'gridlayout_col2.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][2] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (2, 2, 1374759322, 1374759322, 1, 0, 512, 0, 0, '3 Columns', 3, '', 0, 'backend_layout {\r\n\tcolCount = 3\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Spalte links\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Mittlere Spalte\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = Spalte rechts\r\n\t\t\t\t\tcolPos = 103\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_3col.xml', 'gridlayout_col3.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][3] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (3, 2, 1374759352, 1374759352, 1, 0, 768, 0, 0, '4 Columns', 3, '', 0, 'backend_layout {\r\n\tcolCount = 4\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = 1. Spalte\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = 2. Spalte\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = 3. Spalte\r\n\t\t\t\t\tcolPos = 103\r\n\t\t\t\t}\r\n\t\t\t\t4 {\r\n\t\t\t\t\tname = 4. Spalte\r\n\t\t\t\t\tcolPos = 104\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_4col.xml', 'gridlayout_col4.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][4] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (4, 2, 1374759412, 1374759412, 1, 0, 1024, 0, 0, 'Special Box', 3, '', 0, 'backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Container\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_container.xml', 'gridelement_specialbox.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][5] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (5, 2, 1374759464, 1374759464, 1, 0, 1280, 0, 0, 'Accordion', 2, '', 0, 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Accordion Elements\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_accordion.xml', 'gridelement_accordion.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][6] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (6, 2, 1374759507, 1374759507, 1, 0, 1536, 0, 0, 'Tabs', 2, ' ', 0, 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Tab Elemente\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_tabs.xml', 'gridelement_tabs.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][7] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (7, 2, 1374759567, 1374759567, 1, 0, 1792, 0, 0, 'Slider', 1, '', 0, 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Slider Elemente\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_slider.xml', 'gridelement_slider.gif')";
		$sqlStatement['tx_gridelements_backend_layout']['insert'][8] = "INSERT INTO `tx_gridelements_backend_layout` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `t3_origuid`, `sorting`, `deleted`, `hidden`, `title`, `frame`, `description`, `top_level_layout`, `config`, `pi_flexform_ds`, `pi_flexform_ds_file`, `icon`) VALUES (8, 2, 1374759632, 1374759632, 1, 0, 2048, 0, 0, 'Modal Box', 1, '', 0, 'backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Modal\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', '', 'typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_modal.xml', 'gridelement_modal.gif')";
		// update sql
		$sqlStatement['tx_gridelements_backend_layout']['update'][1] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=3, `config`='backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Spalte links\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Spalte rechts\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_2col.xml', `icon`='gridlayout_col2.gif' where uid=1";
		$sqlStatement['tx_gridelements_backend_layout']['update'][2] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=3, `config`='backend_layout {\r\n\tcolCount = 3\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Spalte links\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = Mittlere Spalte\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = Spalte rechts\r\n\t\t\t\t\tcolPos = 103\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_3col.xml', `icon`='gridlayout_col3.gif' where uid=2";
		$sqlStatement['tx_gridelements_backend_layout']['update'][3] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=3, `config`='backend_layout {\r\n\tcolCount = 4\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = 1. Spalte\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t\t2 {\r\n\t\t\t\t\tname = 2. Spalte\r\n\t\t\t\t\tcolPos = 102\r\n\t\t\t\t}\r\n\t\t\t\t3 {\r\n\t\t\t\t\tname = 3. Spalte\r\n\t\t\t\t\tcolPos = 103\r\n\t\t\t\t}\r\n\t\t\t\t4 {\r\n\t\t\t\t\tname = 4. Spalte\r\n\t\t\t\t\tcolPos = 104\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_4col.xml', `icon`='gridlayout_col4.gif' where uid=3";
		$sqlStatement['tx_gridelements_backend_layout']['update'][4] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=3, `config`='backend_layout {\r\n\tcolCount = 2\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Container\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_container.xml', `icon`='gridelement_specialbox.gif' where uid=4";
		$sqlStatement['tx_gridelements_backend_layout']['update'][5] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=2, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Accordion Elements\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_accordion.xml', `icon`='gridelement_accordion.gif' where uid=5";
		$sqlStatement['tx_gridelements_backend_layout']['update'][6] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=2, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Tab Elemente\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_tabs.xml', `icon`='gridelement_tabs.gif' where uid=6";
		$sqlStatement['tx_gridelements_backend_layout']['update'][7] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=1, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Slider Elemente\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_slider.xml', `icon`='gridelement_slider.gif' where uid=7";
		$sqlStatement['tx_gridelements_backend_layout']['update'][8] = "UPDATE `tx_gridelements_backend_layout` SET `pid`=2, `deleted`=0, `frame`=1, `config`='backend_layout {\r\n\tcolCount = 1\r\n\trowCount = 1\r\n\trows {\r\n\t\t1 {\r\n\t\t\tcolumns {\r\n\t\t\t\t1 {\r\n\t\t\t\t\tname = Modal\r\n\t\t\t\t\tcolPos = 101\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n', `pi_flexform_ds_file`='typo3conf/ext/bootstrap_core/Configuration/FlexForm/gridelements/flexform_modal.xml', `icon`='gridelement_modal.gif' where uid=8";

		$this->sqlStatement = $sqlStatement;
	}
}

// XCLASS
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bootstrap_core/class.ext_update.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bootstrap_core/class.ext_update.php']);
}

?>