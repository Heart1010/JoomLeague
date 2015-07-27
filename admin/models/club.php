<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Club Model
 */
class JoomleagueModelClub extends JoomleagueModelItem
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 * 
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			$user = JFactory::getUser();
			return $user->authorise('core.delete', $this->option.'.'.$this->name.'.'.(int) $record->id);
		}
	}
	
	/**
	 * Method to remove a club
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete(&$pks=array())
	{
		$result = false;
		if (count($pks))
		{
			$cids	= implode(',',$pks);
			$db 	= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('id');
			$query->from('#__joomleague_team');
			$query->where('club_id IN ('.$cids.')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_MODEL_ERROR_TEAM_EXISTS'));
				return false;
			}
			
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__joomleague_playground');
			$query->where('club_id IN ('.$cids.')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_MODEL_ERROR_VENUE_EXISTS'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}

	/**
	 * Method to load content club data
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
	 * Method to initialise the club data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$club=new stdClass();
			$club->id					= 0;
			$club->asset_id				= 0;
			$club->name					= null;
			$club->admin				= 0;
			$club->address				= null;
			$club->zipcode				= null;
			$club->location				= null;
			$club->state				= null;
			$club->country				= null;
			$club->founded				= null;
			$club->dissolved			= null;
			$club->phone				= null;
			$club->fax					= null;
			$club->email				= null;
			$club->website				= null;
			$club->president			= null;
			$club->manager				= null;
			$club->logo_big				= null;
			$club->logo_middle			= null;
			$club->logo_small			= null;
			$club->logo_icon			= null;
			$club->stadium_picture		= null;
			$club->standard_playground	= null;
			$club->notes 				= null;
			$club->extended				= null;
			$club->ordering				= 0;
			$club->checked_out			= 0;
			$club->checked_out_time		= 0;
			$club->ordering				= 0;
			$club->alias				= null;
			$club->modified				= null;
			$club->modified_by			= null;
			$this->_data				= $club;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	/**
	* Method to return a playgrounds array (id,name)
	*
	* @access  public
	* @return  array
	*/
	function getPlaygrounds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value, name AS text');
		$query->from('#__joomleague_playground');
		$query->order('text ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'club', $prefix = 'table', $config = array())
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
