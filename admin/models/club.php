<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once(JPATH_COMPONENT.DS.'models'.DS.'item.php');

/**
 * Joomleague Component Club Model
 *
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueModelClub extends JoomleagueModelItem
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
	 *
	 * @since   11.1
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
	 * @since	0.1
	 */
	function delete(&$pks=array())
	{
		$result=false;
		if (count($pks))
		{
			$cids=implode(',',$pks);
			$query="SELECT id FROM #__joomleague_team WHERE club_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_MODEL_ERROR_TEAM_EXISTS'));
				return false;
			}
			$query="SELECT id FROM #__joomleague_playground WHERE club_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
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
	 * @since	1.5
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
	 * @since	1.5
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
	* @since 0.1
	*/
	function getPlaygrounds()
	{
		$query='SELECT id AS value, name AS text 
				FROM #__joomleague_playground ORDER BY text ASC';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
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
	 * @since	1.6
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
	 * @since	1.7
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
	 * @since	1.7
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
?>