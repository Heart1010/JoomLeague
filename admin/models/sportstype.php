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
 * Sportstype Model
 *
 * @author	Julien Vonthron <julien.vonthron@gmail.com>
 */
class JoomleagueModelSportsType extends JoomleagueModelItem
{
	/**
	 * Method to remove a sportstype
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete(&$pks=array())
	{
		if (count($pks))
		{
			$cids=implode(',',$pks);
			$query="SELECT COUNT(id) FROM #__joomleague_sports_type";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult()==count($cid))
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_MODEL_ERROR_LAST_SPORTSTYPE'));
				return false;
			}
			$query="SELECT id FROM #__joomleague_eventtype WHERE sports_type_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_MODEL_ERROR_EVENT_EXISTS'));
				return false;
			}
			$query="SELECT id FROM #__joomleague_position WHERE sports_type_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_MODEL_ERROR_POSITION_EXISTS'));
				return false;
			}
			$query="SELECT id FROM #__joomleague_project WHERE sports_type_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_MODEL_ERROR_PROJECT_EXISTS'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}

	/**
	 * Method to load content sportstype data
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
	 * Method to initialise the sportstype data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$sportstype						= new stdClass();
			$sportstype->id					= 0;
			$sportstype->name				= null;
			$sportstype->icon				= '';
			$sportstype->ordering			= 0;
			$sportstype->checked_out		= 0;
			$sportstype->checked_out_time	= 0;
			$sportstype->modified			= null;
			$sportstype->modified_by		= null;
				
			$this->_data					= $sportstype;

			return (boolean) $this->_data;
		}

		return true;
	}
	
	/**
	 * Method to add a new sportstype if not already exists
	 *
	 * @access	private
	 * @return	boolean	True on success
	 **/
	function addSportsType($newSportsTypeName)
	{
		$path = JPATH::clean(JPATH_ROOT.'/images/com_joomleague/database/events/'.JFolder::makesafe($newSportsTypeName));
		if(!JFolder::exists($path)) {
			JFolder::create($path);
		}
		//SportsType does NOT exist and has to be created
		$tblSportsType = $this->getTable();
		$tblSportsType->load(array('name'=>$newSportsTypeName));
		$tblSportsType->name = $newSportsTypeName;
		$tblSportsType->store();
		return $tblSportsType->id;
	}
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'sportstype', $prefix = 'table', $config = array())
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
