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
 * Playground Model
 */
class JoomleagueModelPlayground extends JoomleagueModelItem
{
	/**
	 * Method to remove venues
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete(&$pks=array())
	{
		if (count($pks))
		{
			$cids=implode(',',$pks);
			$query="SELECT id FROM #__joomleague_project_team WHERE standard_playground IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_VENUE_MODEL_ERROR_P_TEAM_EXISTS'));
				return false;
			}
			$query="SELECT id FROM #__joomleague_match WHERE playground_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_VENUE_MODEL_ERROR_MATCH_EXISTS'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}

	/**
	 * Method to load content venue data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$this->_data = parent::getItem($this->_id);
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the venue data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$venue=new stdClass();
			$venue->id					= 0;
			$venue->name				= null;
			$venue->short_name			= null;
			$venue->address				= null;
			$venue->zipcode				= null;
		  	$venue->city				= null;
			$venue->country				= null;
			$venue->picture				= null;
			$venue->notes				= null;
			$venue->max_visitors		= null;
			$venue->club_id				= null;
			$venue->website				= null;
			$venue->checked_out			= 0;
			$venue->checked_out_time	= 0;
			$venue->extended			= null;
			$venue->ordering			= 0;
			$venue->alias				= null;
			$venue->modified			= null;
			$venue->modified_by			= null;
			$this->_data				= $venue;
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
	public function getTable($type = 'playground', $prefix = 'table', $config = array())
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
