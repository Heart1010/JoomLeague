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
require_once JLG_PATH_SITE.'/models/project.php';

class JoomleagueModelIcal extends JoomleagueModelProject
{
	var $projectid	= 0;
	var $teamid	= 0;
	var $divisionid	= 0;
	var $playgroundid = 0;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$this->projectid	= $jinput->getInt('p',0);
		$this->teamid		= $jinput->getInt('teamid',0);
		$this->divisionid	= $jinput->getInt('division',0);
		$this->playgroundid	= $jinput->getInt('pgid',0);
	}

	function getMatches()
	{
		$ordering = 'ASC';

		return $this->_getResultsPlan($this->projectid,
			$this->teamid,
			$this->divisionid,
			$this->playgroundid,
			$ordering
		);
	}

	function _getResultsPlan($projectid = 0, $teamid = 0, $divisionid = 0, $playgroundid = 0, $ordering = 'ASC')
	{
		$matches = array();
		$query  = ' SELECT m.projectteam1_id, m.projectteam2_id, m.match_date,'
				. ' 	p.name AS project_name, p.timezone,'
				. ' 	t1.id AS team1, t2.id AS team2,'
				. ' 	r.roundcode, r.name, r.project_id,'
				. ' 	plcd.id AS club_playground_id, plcd.name AS club_playground_name,'
		    	. ' pltd.id AS team_playground_id, pltd.name AS team_playground_name,'
		    	. ' pl.id AS playground_id, pl.name AS playground_name,'
				. ' plcd.address AS club_playground_address,'
		    	. ' plcd.zipcode AS club_playground_zipcode,'
		    	. ' plcd.city AS club_playground_city,'
		    	. ' pltd.address AS team_playground_address,'
		    	. ' pltd.zipcode AS team_playground_zipcode,'
		    	. ' pltd.city AS team_playground_city,'
		    	. ' pl.address AS playground_address,'
		    	. ' pl.zipcode AS playground_zipcode,'
		    	. ' pl.city AS playground_city'
				. ' FROM #__joomleague_match AS m'
				. ' INNER JOIN #__joomleague_round r ON m.round_id = r.id'
				. ' INNER JOIN #__joomleague_project_team AS pt1 ON m.projectteam1_id = pt1.id'
				. ' INNER JOIN #__joomleague_team AS t1 ON t1.id = pt1.team_id'
				. ' INNER JOIN #__joomleague_project_team AS pt2 ON m.projectteam2_id = pt2.id'
				. ' INNER JOIN #__joomleague_team AS t2 ON t2.id = pt2.team_id'
				. ' INNER JOIN #__joomleague_project AS p ON p.id = r.project_id'
				. ' INNER JOIN #__joomleague_club c ON c.id = t1.club_id'
				. ' LEFT JOIN #__joomleague_playground AS pl ON pl.id = m.playground_id'
				. ' LEFT JOIN #__joomleague_playground AS plcd ON c.standard_playground = plcd.id'
				. ' LEFT JOIN #__joomleague_playground AS pltd ON pt1.standard_playground = pltd.id'
				. ' WHERE m.published = 1 AND p.published = 1';

		if ($projectid !=0)
		{
			$query .= " AND r.project_id = '".$projectid."'";
		}
		if ($teamid != 0)
		{
			$query .= " AND (t1.id = ".$teamid." OR t2.id = ".$teamid.")";
		}
		if ($playgroundid !=0)
		{
			$query .= ' AND ( m.playground_id = "'. $playgroundid .'"'
					. ' OR (pt1.standard_playground = "'. $playgroundid .'" AND m.playground_id IS NULL)'
					. ' OR (c.standard_playground = "'. $playgroundid .'" AND (m.playground_id IS NULL AND pt1.standard_playground IS NULL  )))'
					. ' AND m.match_date > NOW()';
		}

		$query .= ' GROUP BY m.id'
				. " ORDER BY r.roundcode ".$ordering.", m.match_date, m.match_number";

		$this->_db->setQuery( $query );
		$matches = $this->_db->loadObjectList();
		if ($matches)
		{
			foreach ($matches as $match)
			{
				JoomleagueHelper::convertMatchDateToTimezone($match);
			}
		}

		return $matches;
	}

	function getTeamsFromMatches( & $games )
	{
		$teams = Array();

		if ( !count( $games ) )
		{
			return $teams;
		}

		foreach ( $games as $m )
		{
			$teamsId[] = $m->team1;
			$teamsId[] = $m->team2;
		}
		$listTeamId = implode( ",", array_unique( $teamsId ) );

		$query = "SELECT t.id, t.name
		  FROM #__joomleague_team t
		  WHERE t.id IN (".$listTeamId.")";
		$this->_db->setQuery( $query );
		$result = $this->_db->loadObjectList();

		foreach ( $result as $r )
		{
			$teams[$r->id] = $r;
		}

		return $teams;
	}


}
?>