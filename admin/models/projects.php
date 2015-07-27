<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/models/list.php';

/**
 * Projects Model
 */
class JoomleagueModelProjects extends JoomleagueModelList
{
	var $_identifier = "projects";
	
	function _buildQuery()
	{
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order',		'filter_order',		'p.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$filter_league		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_league',		'filter_league','',			'int');
		$filter_sports_type	= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_sports_type',	'filter_sports_type','',	'int');
		$filter_season		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_season',		'filter_season','',			'int');
		$filter_state		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_state',		'filter_state',		'P',	'word');
		$search				= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search',				'search',			'',		'string');
		$search_mode		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search_mode',		'search_mode',		'',		'string');
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.*');
		$query->from('#__joomleague_project AS p');
		
		// League
		$query->select('l.name AS league');
		$query->join('LEFT', '#__joomleague_league AS l ON l.id = p.league_id');
		
		// Season
		$query->select('s.name AS season');
		$query->join('LEFT', '#__joomleague_season AS s ON s.id = p.season_id');
		
		// SportsType
		$query->select('st.name AS sportstype');
		$query->join('LEFT', '#__joomleague_sports_type AS st ON st.id = p.sports_type_id');
		
		// User
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = p.checked_out');
		
		// Where
		if($filter_league > 0) {
			$query->where('p.league_id = ' . $filter_league);
		}
		if($filter_season > 0) {
			$query->where('p.season_id = ' . $filter_season);
		}
		if ($filter_sports_type > 0)
		{
			$query->where('p.sports_type_id = '.$db->Quote($filter_sports_type));
		}
		if ($search)
		{
			$query->where('LOWER(p.name) LIKE ' . $db->Quote('%'.$search.'%'));
		}
		
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$query->where('p.published = 1');
			}
			elseif ($filter_state == 'U')
			{
				$query->where('p.published = 0');
			}
			elseif ($filter_state == 'A')
			{
				$query->where('p.published = 2');
			}
			elseif ($filter_state == 'T')
			{
				$query->where('p.published = -2');
			}
		}
		
		// Orderby
		if ( $filter_order == 'p.ordering' )
		{
			$query->order('p.ordering ' . $filter_order_Dir);
		}
		else
		{
			$query->order($filter_order . ' ' . $filter_order_Dir,'p.ordering ');
		}
		
		return $query;
	}


	/**
	* Method to check if the project to be copied already exists
	*
	* @access  public
	* @return  array
	*/
	function cpCheckPExists($post)
	{
		$name = 		$post['name'];
		$league_id = 	$post['league_id'];
		$season_id = 	$post['season_id'];
		$old_id = 		$post['old_id'];

		//check project unicity if season and league are not both new
		if ($league_id && $season_id)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__joomleague_project');
			$query->where('name = ' . $db->Quote($name));
			$query->where('league_id = ' . $league_id);
			$query->where('season_id = ' . $season_id);
			
			$db->setQuery($query);
			$db->execute();
			$num = $db->getAffectedRows();

			if ($num > 0)
			{
				return false;
			}
		}

		return true;
	}

	/**
	* Method to assign teams of an existing project to a copied project
	*
	* @access	public
	* @return	array
	*/
	function cpCopyStaff( $post )
	{
		$old_id = (int)$post['old_id'];
		$project_id = (int)$post['id'];

		$query = '	SELECT	ts.projectteam_id,
							ts.person_id,
							ts.project_position_id,
							jt.id,
							jt.team_id
					FROM #__joomleague_team_staff ts
					LEFT JOIN #__joomleague_project_team as jt ON jt.id = ts.projectteam_id
					WHERE jt.project_id = ' . $old_id . '
					ORDER BY jt.id ';

		$this->_db->setQuery( $query );

		if ( $results = $this->_db->loadAssocList() )
		{
			foreach( $results as $result )
			{
				$query = '	SELECT	jt.id,
									jt.team_id
							FROM #__joomleague_project_team jt
							WHERE jt.project_id = ' . $project_id . ' AND jt.team_id = ' . $result['team_id'] . '
							ORDER BY jt.id ';

				$this->_db->setQuery( $query );
				$newprojectteam_id = $this->_db->loadResult();

				$p_staff = $this->getTable();
				$p_staff->bind( $result );
				$p_staff->set( 'teamstaff_id', NULL );
				$p_staff->set( 'projectteam_id', $newprojectteam_id );

				if ( !$p_staff->store() )
				{
					echo $this->_db->getErrorMsg();
					return false;
				}
			}
		}
		return true;
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

}
