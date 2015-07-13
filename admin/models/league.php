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
require_once (JPATH_COMPONENT.DS.'models'.DS.'item.php');

/**
 * Joomleague Component league Model
 *
 * @author	Julien Vonthron <julien.vonthron@gmail.com>
 * @package	Joomleague
 * @since	0.1
 */
class JoomleagueModelLeague extends JoomleagueModelItem
{
	
	/**
	 * Method to remove a league
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function delete(&$pks=array())
	{
		if (count($pks))
		{
			$cids=implode(',',$pks);
			$query="SELECT id FROM #__joomleague_project WHERE league_id IN ($cids)";
			$this->_db->setQuery($query);
			if ($this->_db->loadResult())
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
	 * @since	0.1
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
	 * @since	1.5
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
	 * @since	1.5
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
	 * @since	1.6
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