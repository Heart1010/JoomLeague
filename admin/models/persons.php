<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author	Kurt Norgaz
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/list.php';

/**
 * Persons Model
 */
class JoomleagueModelPersons extends JoomleagueModelList
{
	var $_identifier = "persons";

	function _buildQuery()
	{
		$app	= JFactory::getApplication();
		$jinput	= $app->input;
		$option = $jinput->getCmd('option');
		
		$project_id			= $app->getUserState($option.'project');
		$team_id			= $app->getUserState($option.'team_id');
		$project_team_id	= $app->getUserState($option.'project_team_id');
		$exludePerson		= '';
		
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state', 'filter_state', '', 'word' );
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order', 'filter_order', 'pl.lastname', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir', '',	'word' );
		$search				= $app->getUserStateFromRequest($this->context.'.search', 'search',	'', 'string');
		$search_mode		= $app->getUserStateFromRequest($this->context.'.search_mode', 'search_mode', '', 'string');
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pl.*');
		$query->from('#__joomleague_person AS pl');
		
		// Users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = pl.checked_out');
		
		// Where
		if ($search)
		{
			if ($search_mode)
			{
				$query->where('(LOWER(pl.lastname) LIKE '.$db->Quote($search.'%') .
				'OR LOWER(pl.firstname) LIKE '.$db->Quote($search.'%').
				'OR LOWER(pl.nickname) LIKE '.$db->Quote($search.'%').')');
			}
			else
			{
				$query->where('(LOWER(pl.lastname) LIKE '.$db->Quote('%'.$search.'%').
				'OR LOWER(pl.firstname) LIKE '.$db->Quote('%'.$search.'%') .
				'OR LOWER(pl.nickname) LIKE '.$db->Quote('%'.$search.'%').')');
			}
		}
		
		// Filter-State
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$query->where('pl.published = 1');
			}
			elseif ($filter_state == 'U')
			{
				$query->where('pl.published = 0');
			}
		}
		
		// Order
		if ( $filter_order == 'pl.lastname' )
		{
			$query->order('pl.lastname ' . $filter_order_Dir);
		}
		else
		{
			$query->order($filter_order . ' ' . $filter_order_Dir,'pl.lastname ');
		}
		
		return $query;
	}



	/**
	 * get person history across all projects, with team, season, position,... info
	 *
	 * @param int $person_id
	 * @param int $order ordering for season and league
	 * @param string $filter e.g. "s.name = 2007/2008", default empty string
	 * @return array of objects
	 */
	function jl_getPersonHistory( $person_id, $order = 'ASC', $published = 1, $filter = "" )
	{
		if ( $published )
		{
			$filter .= " AND p.published = 1 ";
		}

		$query = "	SELECT	pt.id AS ptid,
							pt.person_id AS pid,
							pt.team_id, pt.project_id,
							t.name AS teamname,
							p.name AS pname,
							s.name AS sname,
							tt.id AS ttid,
							pos.name AS position
					FROM #__joomleague_team_player AS pt
					INNER JOIN #__joomleague_project AS p ON p.id = pt.project_id
					INNER JOIN #__joomleague_season AS s ON s.id = p.season_id
					INNER JOIN #__joomleague_league AS l ON l.id = p.league_id
					INNER JOIN #__joomleague_team AS t ON t.id = pt.team_id
					INNER JOIN #__joomleague_project_team AS tt ON pt.team_id = tt.team_id AND pt.project_id = tt.project_id
					INNER JOIN #__joomleague_position AS pos ON pos.id = pt.project_position_id
					WHERE person_id='" . $person_id . "' " . $filter . "
					GROUP BY pt.id	ORDER BY	s.ordering " . $order . ",
												l.ordering " . $order . ",
												p.name
									ASC";
		$this->_db->setQuery( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}

	/**
	 * get person history across all projects, with team, season, position,... info
	 *
	 * @param int $person_id , linked to person_id from person object
	 * @param int $order ordering for season and league
	 * @param string $filter e.g. "s.name = 2007/2008", default empty string
	 * @return array of objects
	 */
	function jl_getStaffHistory( $person_id, $order = 'ASC', $published = 1, $filter = "" )
	{
		if ( $published )
		{
			$filter .= " AND p.published = 1 ";
		}

		$query = "	SELECT	ts.teamstaff_id AS tsid,
							ts.person_id AS pid,
							p.id AS project_id,
							t.name AS teamname,
							p.name AS pname,
							s.name AS sname,
							tt.id AS ttid,
							pos.name AS position
					FROM	#__joomleague_team_staff AS ts
					INNER JOIN #__joomleague_project_team AS tt ON tt.id = ts.projectteam_id
					INNER JOIN #__joomleague_project AS p ON p.id = tt.project_id
					INNER JOIN #__joomleague_season AS s ON s.id = p.season_id
					INNER JOIN #__joomleague_league AS l ON l.id = p.league_id
					INNER JOIN #__joomleague_team AS t ON t.id = tt.team_id
					INNER JOIN #__joomleague_position AS pos ON pos.id = ts.project_position_id
					WHERE person_id= '" . $person_id . "' " . $filter . "
					GROUP BY ts.teamstaff_id	ORDER BY	s.ordering " . $order . ",
											l.ordering " . $order . ",
											p.name
											ASC";

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
	 * return persons list from ids contained in var cid
	 *
	 * @return array
	 */
	function getPersonsToAssign()
	{
		$cid = JRequest::getVar('cid');

		if (!count($cid))
		{
			return array();
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pl.id,pl.firstname,pl.nickname,pl.lastname');
		$query->from('#__joomleague_person AS pl');
		$query->where('pl.id IN ('.implode(', ',$cid).')','pl.published = 1');
		$db->setQuery($query);
		return $db->loadObjectList();
	}


	/**
	 * return list of project teams for select options
	 *
	 * @return array
	 */
	function getProjectTeamList()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('t.id AS value,t.name AS text');
		$query->from('#__joomleague_team AS t');
		$query->join('INNER', '#__joomleague_project_team AS tt ON tt.team_id = t.id');
		$query->where('tt.project_id = '.$this->_project_id);
		$query->order('text ASC');
		$db->setQuery( $query);
		return $db->loadObjectList();
	}

	/**
	 * get team name
	 *
	 * @return string
	 */
	function getTeamName($team_id)
	{
		if (!$team_id)
		{
			return '';
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('name');
		$query->from('#__joomleague_team');
		$query->where('id = '.$team_id);
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * get team name
	 *
	 * @return string
	 */
	function getProjectTeamName($project_team_id)
	{
		if (!$project_team_id)
		{
			return '';
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('t.name');
		$query->from('#__joomleague_team AS t');
		$query->join('INNER', '#__joomleague_project_team AS pt ON t.id = pt.team_id');
		$query->where('pt.id = '.$db->Quote($project_team_id));
		$db->setQuery($query);
		return $db->loadResult();
	}

}
