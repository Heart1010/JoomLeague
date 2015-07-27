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
 * League Model
 *
 * @author	Julien Vonthron <julien.vonthron@gmail.com>
 */
class JoomleagueModelLeague extends JoomleagueModelItem
{
	
	/**
	 * Method to remove a league
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete(&$pks=array())
	{
		if (count($pks))
		{
			$cids = implode(',',$pks);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__joomleague_project');
			$query->where('league_id IN ('.$cids.')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_LEAGUE_MODEL_ERROR_PROJECT_EXISTS'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}

	/**
	 * Method to load content league data
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
	 * Method to initialise the league data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$league						= new stdClass();
			$league->id					= 0;
			$league->name				= null;
			$league->middle_name		= null;
			$league->short_name			= null;			
			$league->alias				= null;
			$league->country			= null;
			$league->checked_out		= 0;
			$league->checked_out_time	= 0;
			$league->extended			= null;
			$league->ordering			= 0;
			$league->modified			= null;
			$league->modified_by		= null;
			$this->_data				= $league;

			return (boolean) $this->_data;
		}
		return true;
	}
	
	/**
	 * Method to add a league if not already exists
	 *
	 * @access	private
	 * @return	boolean	True on success
	 **/
	function addLeague($newLeagueName)
	{
		//league does NOT exist and has to be created
		$tblLeague = $this->getTable();
		$tblLeague->load(array('name'=>$newLeagueName));
		$tblLeague->name = $newLeagueName;
		$tblLeague->store();
		return $tblLeague->id;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'league', $prefix = 'table', $config = array())
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
