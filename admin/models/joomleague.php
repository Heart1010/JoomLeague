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
require_once JPATH_COMPONENT.'/models/project.php';

/**
 * Joomleague Model
 *
 * @author Marco Vaninetti <martizva@alice.it>
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
		if ( empty( $this->_data ) )
		{
			$pid	= JRequest::getVar( 'pid',	array(0), '', 'array' );

			$query = '	SELECT p.*
						FROM #__joomleague_project AS p
						WHERE p.id = ' . (int) $pid[0];

			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
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
		$query = '	SELECT	id,
							name
					FROM #__joomleague_project
					WHERE p.published=1
					ORDER BY ordering,name ASC';

		$this->_db->setQuery( $query );

		if ( !$result = $this->_db->loadObjectList() )
		{
			$this->setError($this->_db->getErrorMsg());
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
		$option = JRequest::getCmd('option');
		$app	= JFactory::getApplication();

		$project_id = $app->getUserState( $option . 'project' );

		$query = '	SELECT	pt.id AS value,
							t.name As text,
							t.notes
					FROM #__joomleague_team AS t
					LEFT JOIN #__joomleague_project_team AS pt ON pt.team_id = t.id
					WHERE pt.project_id = ' . $project_id . '
					ORDER BY name ASC ';

		$this->_db->setQuery( $query );
		if ( !$result = $this->_db->loadObjectList() )
		{
			$this->setError( $this->_db->getErrorMsg() );
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
		$query = '	SELECT	id,
							roundcode,
							name,
							round_date_first,
							round_date_last
					FROM #__joomleague_round
					WHERE project_id = ' . (int) $this->_id . '
					ORDER BY roundcode,round_date_first';

		$this->_db->setQuery( $query );
		if ( !$result = $this->_db->loadObjectList() )
		{
			$this->setError( $this->_db->getErrorMsg() );
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
		$query = 'SELECT id, name FROM #__joomleague_season ORDER BY name ASC ';
		$this->_db->setQuery($query);
		if (!$result = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
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
		$query = "SELECT id, name FROM #__joomleague_project as p
						WHERE sports_type_id=$sportstype_id 
						AND published=1 ";
		if ($season) {
			$query .= ' AND season_id = '.(int) $season;
		}
		$query .=	" ORDER BY p.ordering, p.name ASC ";
		$this->_db->setQuery($query);
		if (!$result = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}
	
	function getVersion()
	{
		$query = "SELECT CONCAT(major,'.',minor,'.',build,'.',revision) AS version
						FROM #__joomleague_version 
						ORDER BY date DESC LIMIT 1";
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}

}
