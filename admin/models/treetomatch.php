<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Treetomatch Model
 *
 * @author	comraden
 */
class JoomleagueModelTreetomatch extends JoomleagueModelItem
{
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->_data ) )
		{
			$query ='	SELECT ttm.*
					FROM #__joomleague_treeto_match AS ttm
					WHERE ttm.id = ' . (int) $this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->_data ) )
		{
			$treetomatch = new stdClass();
			$treetomatch->node_id			= 0;
			$treetomatch->match_id			= 0;
			$treetomatch->checked_out		= 0;
			$treetomatch->checked_out_time	= 0;
			$treetomatch->modified			= null;
			$treetomatch->modified_by		= null;
			
			$this->_data			= $treetomatch;
			return (boolean) $this->_data;
		}
		return true;
	}
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'treetomatch', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_joomleague.'.$this->name, $this->name,
				array('load_data' => $loadData) );
		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_joomleague.edit.'.$this->name.'.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
}
