<?php
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Joomleague JLTable Table class
 *
 * @package	Joomleague
 */
class JLTable extends JTable {

	
	public function bind($array, $ignore = '') 
	{
		// extended
		if (key_exists('extended', $array) && is_array($array['extended'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['extended']);
			$array['extended'] = $registry->toString('ini');
		}
		
		// rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JRules($array['rules']);
			$this->setRules($rules);
		}
		return parent::bind($array, $ignore);
	}

	/**
	 * try to insert first, update if fails
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @param boolean If false, null object variables are not updated
	 * @return null|string null if successful otherwise returns and error message
	 */
	public function insertIgnore($updateNulls = false) {
		$k = $this->_tbl_key;

		$ret = $this->_insertIgnoreObject($this->_tbl, $this, $this->_tbl_key);
		if (!$ret) {
			$this->setError(get_class($this) . '::store failed - ' . $this->getDbo()->getErrorMsg());
			return false;
		}
		return true;
	}

	/**
	 * Inserts a row into a table based on an objects properties, ignore if already exists
	 *
	 * @access  public
	 * @param string  The name of the table
	 * @param object  An object whose properties match table fields
	 * @param string  The name of the primary key. If provided the object property is updated.
	 * @return int number of affected row
	 */
	public function _insertIgnoreObject($table, & $object, $keyName = NULL) {
		$fmtsql = 'INSERT IGNORE INTO ' . $this->getDbo()->quoteName($table) . ' ( %s ) VALUES ( %s ) ';
		$fields = array ();
		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') {
				// internal field
				continue;
			}
			$fields[] = $this->getDbo()->quoteName($k);
			$values[] = $this->getDbo()->quote($v);
		}
		$this->getDbo()->setQuery(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));
		if (!$this->getDbo()->execute()) {
			return false;
		}
		$id = $this->getDbo()->insertid();
		if ($keyName && $id) {
			$object-> $keyName = $id;
		}
		return $this->getDbo()->getAffectedRows();
	}

	/**
	 * Method to determine if a row is checked out and therefore uneditable by
	 * a user. If the row is checked out by the same user, then it is considered
	 * not checked out -- as the user can still edit it.
	 *
	 * @param   integer  $with     The userid to preform the match with, if an item is checked
	 * out by this user the function will return false.
	 * @param   integer  $against  The userid to perform the match against when the function
	 * is used as a static function.
	 *
	 * @return  boolean  True if checked out.
	 *
	 * @link    http://docs.joomla.org/JTable/isCheckedOut
	 * @since   11.1
	 * @todo    This either needs to be static or not.
	 */
	public static function _isCheckedOut($with = 0, $against = null)
	{
		// Handle the non-static case.
		if (isset($this) && ($this instanceof JTable) && is_null($against))
		{
			$against = $this->get('checked_out');
		}
	
		// The item is not checked out or is checked out by the same user.
		if (!$against || ($against == $with))
		{
			return false;
		}
	
		$db = JFactory::getDbo();
		$db->setQuery('SELECT COUNT(userid)' . 
						' FROM ' . $db->quoteName('#__session') . 
						' WHERE ' . $db->quoteName('userid') . ' = ' . (int) $against);
		$checkedOut = (boolean) $db->loadResult();
	
		// If a session exists for the user then it is checked out.
		return $checkedOut;
	}
	
	/**'
	 * Override Store function
	 * @see JTable::store()
	 */
	public function store($updateNulls = false)
	{
		$k = $this->_tbl_keys;

		// Implement JObservableInterface: Pre-processing by observers
		$this->_observers->update('onBeforeStore', array($updateNulls, $k));

		$currentAssetId = 0;

		if (!empty($this->asset_id))
		{
			$currentAssetId = $this->asset_id;
		}

		// The asset id field is managed privately by this class.
		if ($this->_trackAssets)
		{
			unset($this->asset_id);
		}

		// If a primary key exists update the object, otherwise insert it.
		if ($this->hasPrimaryKey())
		{
			$result = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_keys, $updateNulls);
		}
		else
		{
			$result = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_keys[0]);
		}

		// If the table is not set to track assets return true.
		if ($this->_trackAssets)
		{
			if ($this->_locked)
			{
				$this->_unlock();
			}

			/*
			 * Asset Tracking
			 */
			$parentId = $this->_getAssetParentId();
			$name     = $this->_getAssetName();
			$title    = $this->_getAssetTitle();

			$asset = self::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
			$asset->loadByName($name);

			// remove added "alias" column (by joomla core files)
			if ($asset->alias == null) {
				unset ($asset->alias);
			}
			
			// Re-inject the asset id.
			$this->asset_id = $asset->id;

			// Check for an error.
			$error = $asset->getError();

			if ($error)
			{
				$this->setError($error);

				return false;
			}
			else
			{
				// Specify how a new or moved node asset is inserted into the tree.
				if (empty($this->asset_id) || $asset->parent_id != $parentId)
				{
					$asset->setLocation($parentId, 'last-child');
				}

				// Prepare the asset to be stored.
				$asset->parent_id = $parentId;
				$asset->name      = $name;
				$asset->title     = $title;

				if ($this->_rules instanceof JAccessRules)
				{
					$asset->rules = (string) $this->_rules;
				}

				if (!$asset->check() || !$asset->store($updateNulls))
				{
					$this->setError($asset->getError());

					return false;
				}
				else
				{
					// Create an asset_id or heal one that is corrupted.
					if (empty($this->asset_id) || ($currentAssetId != $this->asset_id && !empty($this->asset_id)))
					{
						// Update the asset_id field in this table.
						$this->asset_id = (int) $asset->id;

						$query = $this->_db->getQuery(true)
							->update($this->_db->quoteName($this->_tbl))
							->set('asset_id = ' . (int) $this->asset_id);
						$this->appendPrimaryKeys($query);
						$this->_db->setQuery($query)->execute();
					}
				}
			}
		}

		// Implement JObservableInterface: Post-processing by observers
		$this->_observers->update('onAfterStore', array(&$result));

		return $result;
	}
	
	/**
	 * We provide our global ACL as parent
	 * @see JTable::_getAssetParentId()
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_joomleague');
		return $asset->id;
	}
}
