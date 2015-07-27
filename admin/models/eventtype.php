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
 * Eventtype Model
 */
class JoomleagueModelEventtype extends JoomleagueModelItem
{
	
	/**
	 * Method to export one or more events
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function export($cid=array(),$table, $record_name)
	{
		$result = false;
		
		if (count($cid))
		{
			$mdlJLXExports = JModelLegacy::getInstance("jlxmlexport", 'JoomleagueModel');
			$cids = implode(',',$cid);
			
			// EventType
			$db 	= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('*');
			$query->from('#__joomleague_eventtype');
			$query->where('id IN ('.$cids.')');
			$db->setQuery($query);
			$exportData = $db->loadObjectList();
			
			// SportsType
			$SportsTypeArray = array();
			$x = 0;
			foreach ($exportData as $event) { 
				$SportsTypeArray[$x]=$event->sports_type_id;
			}
			$st_cids = implode(',',$SportsTypeArray);
			$query	= $db->getQuery(true);
			$query->select('*');
			$query->from('#__joomleague_sports_type');
			$query->where('id IN ('.$st_cids.')');
			$db->setQuery($query);
			$exportDataSportsType = $db->loadObjectList();
			
			$output="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			// Events
			$output .= "<events>\n";
			$output .= $mdlJLXExports->_addToXml($mdlJLXExports->_getJoomLeagueVersion());
				
			$record_name = 'SportsType';
			$tabVar='  ';
			foreach ($exportDataSportsType as $name=>$value)
			{
				$output .= "<record object=\"".JoomleagueHelper::stripInvalidXml($record_name)."\">\n";
				foreach ($value as $name2=>$value2)
				{
					if (($name2!='checked_out') && ($name2!='checked_out_time'))
					{
						$output .= $tabVar.'<'.$name2.'><![CDATA['.JoomleagueHelper::stripInvalidXml(trim($value2)).']]></'.$name2.">\n";
					}
				}
				$output .= "</record>\n";
			}
			unset($name,$value);
			$record_name='EventType';
			foreach ($exportData as $name=>$value)
			{
				$output .= "<record object=\"".JoomleagueHelper::stripInvalidXml($record_name)."\">\n";
				foreach ($value as $name2=>$value2)
				{
					if (($name2!='checked_out') && ($name2!='checked_out_time'))
					{
						$output .= $tabVar.'<'.$name2.'><![CDATA['.JoomleagueHelper::stripInvalidXml(trim($value2)).']]></'.$name2.">\n";
					}
				}
				$output .= "</record>\n";
			}
			unset($name,$value);
			// close events
			$output .= '</events>';
			
			$mdlJLXExports = JModelLegacy::getInstance("jlxmlexport", 'JoomleagueModel');
			$mdlJLXExports->downloadXml($output, $table,true);
			
			// close the application
			$app = JFactory::getApplication();
			$app->close();
		}
		return true;
	}

	/**
	 * Method to remove match_events of only one project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function deleteOne($project_id)
	{
		if ($project_id > 0)
		{
			$query='	DELETE
						FROM #__joomleague_match_event
						WHERE match_id in (
						SELECT DISTINCT
						  #__joomleague_match_1.id AS match_id
						FROM
						  #__joomleague_project_team
						  INNER JOIN #__joomleague_match #__joomleague_match_1 ON #__joomleague_project_team.id=#__joomleague_match_1.projectteam2_id
						  INNER JOIN #__joomleague_project_team #__joomleague_project_team_1 ON #__joomleague_project_team_1.id=#__joomleague_match_1.projectteam1_id
						WHERE
						  #__joomleague_project_team.project_id='.(int) $project_id.'
						)';
			$this->_db->setQuery($query);
			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	/**
	 * Method to remove an event
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
			// first check that they are not used in any match events
			$query = $db->getQuery(true);
			$query->select('event_type_id');
			$query->from('#__joomleague_match_event');
			$query->where('event_type_id IN ('.$cids.')');
			$db->setQuery($query);
			$db->execute();
			if ($db->getAffectedRows())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_EVENT_MODEL_ERROR_MATCHES_EXISTS'));
				return false;
			}
			// then check that they are not assigned to any positions
			$query = $db->getQuery(true);
			$query->select('eventtype_id');
			$query->from('#__joomleague_position_eventtype');
			$query->where('eventtype_id IN ('.$cids.')');
			$db->setQuery($query);
			$db->execute();
			if ($db->getAffectedRows())
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_EVENT_MODEL_ERROR_POSITION_EXISTS'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}

	/**
	 * Method to load content event data
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
	 * Method to initialise the event data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$event						= new stdClass();
			$event->id					= 0;
			$event->name				= null;
			$event->icon				= "";
			$event->direction			= "DESC";
			$event->splitt				= 0;
			$event->double				= 0;
			$event->suspension			= 0;
			$event->sports_type_id		= 1;
			$event->published			= 0;
			$event->ordering			= 0;
			$event->checked_out			= 0;
			$event->checked_out_time	= 0;
			$event->modified			= null;
			$event->modified_by			= null;
			$event->alias				= null;
			$this->_data				= $event;
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
	public function getTable($type = 'eventtype', $prefix = 'table', $config = array())
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
