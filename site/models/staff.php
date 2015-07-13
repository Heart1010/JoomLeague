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
require_once('person.php');

class JoomleagueModelStaff extends JoomleagueModelPerson
{
	/**
	 * data array for staff history
	 * @var array
	 */
	var $_history=null;

	function __construct()
	{
		parent::__construct();
		$this->projectid=JRequest::getInt('p',0);
		$this->personid=JRequest::getInt('pid',0);
		$this->teamid=JRequest::getInt('tid',0);
	}

	/**
	 * return the injury,suspension,away data from a player
	 *
	 * @param int $round_id
	 * @param int $player_id
	 *
	 * @access public
	 * @since  1.5.0a
	 *
	 * @return object
	 */
	function getTeamStaffByRound($round_id=0, $player_id=0)
	{
		$query = '	SELECT	ts.*, ts.injury AS injury, pt.team_id,
							pt.id as project_team_id,
							ts.picture as picture,
							ts.suspension AS suspension,
							ts.away AS away,
							ppos.id As pposid,
							ppos.position_id,
							pos.id AS position_id,
							pos.name AS position_name,
							rinjuryfrom.round_date_first injury_date,
							rinjuryto.round_date_last injury_end,
							rinjuryfrom.name rinjury_from,
							rinjuryto.name rinjury_to,
				
							rsuspfrom.round_date_first suspension_date,
							rsuspto.round_date_last suspension_end,
							rsuspfrom.name rsusp_from,
							rsuspto.name rsusp_to,
				
							rawayfrom.round_date_first away_date,
							rawayto.round_date_last away_end,
							rawayfrom.name raway_from,
							rawayto.name raway_to
		
					FROM #__joomleague_team_staff AS ts
					INNER JOIN #__joomleague_person AS pr ON ts.person_id=pr.id
					INNER JOIN #__joomleague_project_team AS pt ON pt.id=ts.projectteam_id
					INNER JOIN #__joomleague_round AS r ON r.project_id=pt.project_id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=ts.project_position_id
					INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
					LEFT JOIN #__joomleague_round AS rinjuryfrom ON ts.injury_date=rinjuryfrom.id
					LEFT JOIN #__joomleague_round AS rinjuryto ON ts.injury_end=rinjuryto.id
					LEFT JOIN #__joomleague_round AS rsuspfrom ON ts.suspension_date=rsuspfrom.id
					LEFT JOIN #__joomleague_round AS rsuspto ON ts.suspension_end=rsuspto.id
					LEFT JOIN #__joomleague_round AS rawayfrom ON ts.away_date=rawayfrom.id
					LEFT JOIN #__joomleague_round AS rawayto ON ts.away_end=rawayto.id
					WHERE r.id='.$round_id.'
					  AND pr.id='.$player_id.'
					  AND pr.published = 1
					  AND ts.published = 1
					ORDER BY ts.id DESC
					  ';
		$this->_db->setQuery($query);
		$rows=$this->_db->loadObjectList();
		return isset($rows[0]) ? $rows[0] : null;
	}
	
	function &getTeamStaff_old()
	{
		if (is_null($this->_inproject))
		{
			$query='	SELECT	ts.*,
								ts.picture as picture,
								pos.name AS position_name,
								ppos.id AS pPosID, ppos.position_id
						FROM #__joomleague_team_staff AS ts
						INNER JOIN #__joomleague_project_team AS pt ON pt.id=ts.projectteam_id
						LEFT JOIN #__joomleague_project_position AS ppos ON ppos.id=ts.project_position_id
						LEFT JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
						WHERE pt.project_id='.$this->_db->Quote($this->projectid).' 
						  AND ts.person_id='.$this->_db->Quote($this->personid).'
						  AND ts.published=1';
			$this->_db->setQuery($query);
			$this->_inproject=$this->_db->loadObject();
		}
		return $this->_inproject;
	}

	/**
	 * get person history across all projects,with team,season,position,... info
	 *
	 * @param int $person_id,linked to player_id from Person object
	 * @param int $order ordering for season and league,default is ASC ordering
	 * @param string $filter e.g. "s.name=2007/2008",default empty string
	 * @return array of objects
	 */
	function &getStaffHistory($order='ASC')
	{
		if (empty($this->_history))
		{
			$personid=$this->personid;
			$query='	SELECT	pr.id AS pid,
								ts.person_id,
								pt.project_id,
								pr.firstname,
								pr.lastname,
								p.name AS project_name,
								s.name AS season_name,
								t.name AS team_name,
								pos.name AS position_name,
								ppos.position_id,
								t.id AS team_id,
								pt.id AS ptid,
								pos.id AS posID,
								CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\',t.id,t.alias) ELSE t.id END AS team_slug,
								CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(\':\',p.id,p.alias) ELSE p.id END AS project_slug
						FROM #__joomleague_person AS pr
						INNER JOIN #__joomleague_team_staff AS ts ON ts.person_id=pr.id
						INNER JOIN #__joomleague_project_team AS pt ON pt.id=ts.projectteam_id
						INNER JOIN #__joomleague_team AS t ON t.id=pt.team_id
						INNER JOIN #__joomleague_project AS p ON p.id=pt.project_id
						INNER JOIN #__joomleague_season AS s ON s.id=p.season_id
						INNER JOIN #__joomleague_league AS l ON l.id=p.league_id
						INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=ts.project_position_id
						LEFT JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
						WHERE pr.id='.$this->_db->Quote($personid).'
						  AND pr.published = 1
						  AND p.published = 1
						  ORDER BY s.ordering '.$order.', l.ordering ASC, p.name ASC ';
			$this->_db->setQuery($query);
			$this->_history=$this->_db->loadObjectList();
		}
		return $this->_history;
	}

	function getPresenceStats($project_id, $projectteam_id, $person_id)
	{
		$query='	SELECT	count(ms.id) AS present
					FROM #__joomleague_match_staff AS ms
					INNER JOIN #__joomleague_match AS m ON m.id=ms.match_id
					INNER JOIN #__joomleague_team_staff AS ts ON ts.id=ms.team_staff_id
					INNER JOIN #__joomleague_project_team AS pt ON pt.id=ts.projectteam_id
					WHERE ts.person_id='.$this->_db->Quote((int)$person_id).'
					  AND ts.projectteam_id='.$this->_db->Quote((int)$projectteam_id).'
					  AND pt.project_id='.$this->_db->Quote((int)$project_id);
		$this->_db->setQuery($query,0,1);
		$inoutstat=$this->_db->loadResult();
		return $inoutstat;
	}

	/**
	 * get stats for the player position
	 * @return array
	 */
	function getStats($current_round=0, $personid=0)
	{
		$staff =& $this->getTeamStaffByRound($current_round, $personid);
		if(!isset($staff->position_id)){$staff->position_id=0;}
		$result=$this->getProjectStats(0,$staff->position_id);
		return $result;
	}

	/**
	 * get player stats
	 * @return array
	 */
	function getStaffStats($current_round=0, $personid=0)
	{
		$staff =& $this->getTeamStaffByRound($current_round, $personid);
		if (!isset($staff->position_id)){$staff->position_id=0;}
		$stats =& $this->getProjectStats(0,$staff->position_id);
		$history =& $this->getStaffHistory();
		$result=array();
		if(count($history) > 0 && count($stats) > 0)
		{
			foreach ($history as $player)
			{
				foreach ($stats as $stat)
				{
					if(!isset($stat) && $stat->position_id != null)
					{
						$result[$stat->id][$player->project_id]=$stat->getStaffStats($player->person_id,$player->team_id,$player->project_id);
					}
				}
			}
		}
		return $result;
	}

	function getHistoryStaffStats($current_round=0, $personid=0)
	{
		$staff =& $this->getTeamStaffByRound($current_round, $personid);
		$stats =& $this->getProjectStats(0,$staff->position_id);
		$result=array();
		if (count($stats) > 0)
		{
			foreach ($stats as $stat)
			{
				if (!isset($stat))
				{
					$result[$stat->id]=$stat->getHistoryStaffStats($staff->person_id);
				}
			}
		}
		return $result;
	}

}
?>