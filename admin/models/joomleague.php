<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author Marco Vaninetti <martizva@alice.it>
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/project.php';

/**
 * Joomleague Model
 */
class JoomleagueModelJoomleague extends JoomleagueModelItem
{
	/**
	 * Method to load content project data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$pid	= JRequest::getVar('pid',	array(0), '', 'array');
	
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('p.*');
			$query->from('#__joomleague_project AS p');
			$query->where('p.id = '.$pid[0]);
			$db->setQuery($query);
			$this->_data = $db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the project data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->_data ) )
		{
			$project					= new stdClass();
			$project->id				= 0;
			$project->league_id			= 0;
			$project->season_id			= 0;
			$project->name				= null;
			$project->published			= 0;
			$project->checked_out		= 0;
			$project->checked_out_time	= 0;
			$project->ordering			= 0;
			$project->params			= null;
			$this->_data				= $project;

			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	* Method to return a project array (id, name)
	*
	* @access  public
	* @return  array project
	*/
	function getProjects()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, name');
		$query->from('#__joomleague_project');
		$query->where('p.published = 1');
		$query->order('ordering, name ASC');
		$db->setQuery($query);

		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;

		}
		else
		{
			return $result;
		}
	}

	/**
	* Method to return the project teams array (id, name)
	*
	* @access  public
	* @return  array
	*/
	function getProjectteams()
	{
		$app		= JFactory::getApplication();
		$jinput 	= $app->input;
		$option 	= $jinput->getCmd('option');
		$project_id = $app->getUserState($option.'project');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('t.name As text,t.notes');
		$query->from('#__joomleague_team AS t');
		
		$query->select('pt.id AS value');
		$query->join('LEFT', '#__joomleague_project_team AS pt ON pt.team_id = t.id');
		
		$query->where('pt.project_id = '.$project_id);
		$query->order('t.name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}
	

	/**
	* Method to return the project rounds array
	*
	* @access  public
	* @return  array
	*/
	function getProjectRounds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,roundcode,name,round_date_first,round_date_last');
		$query->from('#__joomleague_round');
		$query->where('project_id = '.$this->_id);
		$query->order('roundcode,round_date_first');
		$db->setQuery($query);
		
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}
	
	/**
	* Method to return a season array (id, name)
	*
	* @access	public
	* @return	array seasons
	*/
	function getSeasons()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__joomleague_season');
		$query->order('name ASC');
		$db->setQuery($query);
		
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
	
	/**
	* Method to return a project array (id, name)
	*
	* @access	public
	* @return	array project
	*/
	function getProjectsBySportsType($sportstype_id, $season = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.id,p.name');
		$query->from('#__joomleague_project as p');
		$query->where('p.sports_type_id = '.$sportstype_id);
		$query->where('p.published = 1');
		if ($season) {
			$query->where('p.season_id = '.$season);
		}
		$query->order('p.ordering, p.name ASC');
		$db->setQuery($query);
		
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
	
	
	function getVersion()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('CONCAT(major,minor,build,revision) AS version');
		$query->from('#__joomleague_version');
		$query->order('date DESC');
		$db->setQuery($query,0,1);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

}
