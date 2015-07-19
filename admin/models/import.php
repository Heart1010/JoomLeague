<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * Import Model
 */
class JoomleagueModelImport extends JModelLegacy {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * return __joomleague_persons table fields name
	 *
	 * @return array
	 */
	function getTableColumns($table) {
	
		$tablesFields = $this->_db->getTableColumns($table);
		$keys = array_keys($tablesFields);

		return $keys;
	}
	
	/**
	 * import data corresponding to fieldsname into the database table
	 *
	 * @param array $fieldsname        	
	 * @param array $data
	 *        	the records
	 * @param boolean $replace
	 *        	replace if id already exists
	 * @param string $table
	 *        	tableclassname e.g Person
	 * @return array ['added', 'updated', 'exists', 'errormsg'] number of records inserted, updated, already exists and a possible db error msg
	 */
	function import($fieldsname, $data, $replace = true, $table) {
		$ignore = array ();
		if (! $replace) {
			$ignore [] = 'id';
		}
		$rec = array (
				'added' => 0,
				'updated' => 0,
				'exists' => 0,
				'errormsg' => '' 
		);
		// parse each row
		foreach ( $data as $row ) {
			$values = array ();
			// parse each specified field and retrieve corresponding value for the record
			foreach ( $fieldsname as $k => $field ) {
				$values [$field] = $row [$k];
			}
			
			$object = JTable::getInstance ( $table, 'Table' );
			
			// print_r($values);exit;
			$object->bind ( $values, $ignore );
			
			// Make sure the data is valid
			if (! $object->check ()) {
				$this->setError ( $object->getError () );
				$rec ['errormsg'] = JText::_ ( 'COM_JOOMLEAGUE_GLOBAL_ERROR_CHECK' ) . $object->getError ();
				continue;
			}
			
			// Store it in the db
			if ($replace) {
				// We want to keep id from database so first we try to insert into database. if it fails,
				// it means the record already exists, we can use store().
				if (! $object->insertIgnore ()) {
					if (! $object->store ()) {
						// echo JText::_('COM_JOOMLEAGUE_GLOBAL_ERROR_STORE').$this->_db->getErrorMsg()."\n";
						$rec ['exists'] ++;
						continue;
					} else {
						$rec ['updated'] ++;
					}
				} else {
					$rec ['added'] ++;
				}
			} else {
				if (! $object->store ()) {
					// show last error message
					// $rec['errormsg'] = JText::_('COM_JOOMLEAGUE_GLOBAL_ERROR_STORE').$this->_db->getErrorMsg()."<br \>\n";
					$rec ['exists'] ++;
					continue;
				} else {
					$rec ['added'] ++;
				}
			}
		}
		return $rec;
	}
	
}
