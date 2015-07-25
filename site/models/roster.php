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

class JoomleagueModelRoster extends JoomleagueModelProject
{
	var $projectid=0;
	var $projectteamid=0;
	var $projectteam=null;
	var $team=null;

	/**
	 * caching for team in out stats
	 * @var array
	 */
	var $_teaminout=null;

	/**
	 * caching players
	 * @var array
	 */
	var $_players=null;

	public function __construct()
	{
		parent::__construct();
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;

		$this->projectid	 = JLHelperFront::stringToInt($jinput->getString('p',0));
		$this->teamid 		 = JLHelperFront::stringToInt($jinput->getString('tid',0));		
		$this->projectteamid = JLHelperFront::stringToInt($jinput->getString('ttid',0));
		
		$this->getProjectTeam();
	}

	/**
	 * returns project team info
	 * @return object
	 */
	function getProjectTeam()
	{
		if (is_null($this->projectteam))
		{
			if ($this->projectteamid)
			{
				$this->projectteam = $this->getTeaminfo($this->projectteamid);
			}
			else
			{
				if (!$this->teamid)
				{
					$this->setError(JText::_('COM_JOOMLEAGUE_GLOBAL_MISSING_TEAMID'));
					return false;
				}
				if (!$this->projectid)
				{
					$this->setError(JText::_('COM_JOOMLEAGUE_GLOBAL_MISSING_PROJECTID'));
					return false;
				}
				
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('pt.id');
				$query->from('#__joomleague_project_team AS pt');
				$query->where('pt.team_id = '.$db->Quote($this->teamid));
				$query->where('pt.project_id = '.$db->Quote($this->projectid));
				$db->setQuery($query);
				$this->projectteamid = $db->loadObject()->id;
				$this->projectteam = $this->getTeaminfo($this->projectteamid);
			}
			if ($this->projectteam)
			{
				$this->projectid=$this->projectteam->project_id; // if only ttid was set
				$this->teamid=$this->projectteam->team_id; // if only ttid was set
			}
		}
		return $this->projectteam;
	}

	/**
	 * required: $this->teamid + $this->projectid
	 */
	function getTeam()
	{
		if (is_null($this->team))
		{
			if (!$this->teamid)
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_GLOBAL_MISSING_TEAMID'));
				return false;
			}
			if (!$this->projectid)
			{
				$this->setError(JText::_('COM_JOOMLEAGUE_GLOBAL_MISSING_PROJECTID'));
				return false;
			}
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('t.*,CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\',t.id,t.alias) ELSE t.id END AS slug');
			$query->from('#__joomleague_team AS t');
			$query->where('t.id = '.$db->Quote($this->teamid));
			$db->setQuery($query);
			$this->team = $db->loadObject();
		}
		return $this->team;
	}

	/**
	 * return team players (by positions)
	 * 
	 * @todo check! // 24-07-2015
	 * Changed "INNER" to "LEFT" join for positions as it can happen
	 * that no positions were attached.
	 */
	function getTeamPlayers()
	{
		$projectteam = $this->getprojectteam();	
		
		// @todo check 24-07-2015
		// changed retrieval of projectteamid
		$projectteamid = $projectteam->ptid;
	
		if (empty($this->_players))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('tp.id AS playerid,tp.jerseynumber AS position_number,tp.notes AS description,tp.injury AS injury,tp.suspension AS suspension,tp.away AS away,tp.picture');
			$query->from('#__joomleague_team_player AS tp');
			
			// Join Person
			$query->select('pr.id AS pid, pr.picture AS ppic, pr.firstname,pr.nickname,pr.lastname,pr.country,pr.birthday,pr.deathday,CASE WHEN CHAR_LENGTH(pr.alias) THEN CONCAT_WS(\':\',pr.id,pr.alias) ELSE pr.id END AS slug')
			->join('INNER', $db->quoteName('#__joomleague_person') . ' AS pr ON tp.person_id = pr.id');
			
			// Join Project-Team
			$query->select('pt.team_id')
			->join('INNER', $db->quoteName('#__joomleague_project_team') . ' AS pt ON pt.id = tp.projectteam_id');
							
			// Join Project-Position
			$query->select('ppos.position_id,ppos.id AS pposid')
			->join('LEFT', $db->quoteName('#__joomleague_project_position') . ' AS ppos ON ppos.id = tp.project_position_id');
				
			// Join Position
			$query->select('pos.name AS position')
			->join('LEFT', $db->quoteName('#__joomleague_position') . ' AS pos ON pos.id = ppos.position_id');
				
		
			$query->where('tp.projectteam_id = '.$db->Quote($projectteamid));
			$query->where('pr.published = 1');
			$query->where('tp.published = 1');
			$query->order('pos.ordering, ppos.position_id, tp.jerseynumber, pr.lastname, pr.firstname');
			$db->setQuery($query);
			$this->_players = $db->loadObjectList();
		}
		
		$bypos=array();
		foreach ($this->_players as $player)
		{
			if (isset($bypos[$player->position_id]))
			{
				$bypos[$player->position_id][]=$player;
			}
			else
			{
				$bypos[$player->position_id]= array($player);
			}
		}
		return $bypos;
	}

	
	function getStaffList()
	{
		$projectteam = $this->getprojectteam();
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		// Select the required fields from the table.
		$query->select('ts.id AS ptid,ts.picture,ts.notes AS description,ts.injury AS injury,ts.suspension AS suspension,ts.away AS away');
		$query->from('#__joomleague_team_staff AS ts');
				
		// Join Person
		$query->select('pr.id AS pid, pr.picture AS ppic, pr.firstname,pr.nickname,pr.lastname,pr.country,pr.birthday,pr.deathday,CASE WHEN CHAR_LENGTH(pr.alias) THEN CONCAT_WS(\':\',pr.id,pr.alias) ELSE pr.id END AS slug')
		->join('INNER', $db->quoteName('#__joomleague_person') . ' AS pr ON ts.person_id = pr.id');
			
		// Join Project-Position
		$query->select('ppos.position_id,ppos.id AS pposid')
		->join('INNER', $db->quoteName('#__joomleague_project_position') . ' AS ppos ON ppos.id = ts.project_position_id');
		
		// Join Position
		$query->select('pos.name AS position,pos.parent_id')
		->join('INNER', $db->quoteName('#__joomleague_position') . ' AS pos ON pos.id = ppos.position_id');
		
		// Join Position (parent)
		$query->select('posparent.name AS parentname')
		->join('LEFT', $db->quoteName('#__joomleague_position') . ' AS posparent ON pos.parent_id = posparent.id');
		
		$query->where('ts.projectteam_id = '.$db->Quote($this->projectteamid));
		$query->where('pr.published = 1');
		$query->where('ts.published = 1');
		$query->order('pos.parent_id,pos.ordering');
		$db->setQuery($query);
		$stafflist = $db->loadObjectList();
		return $stafflist;
	}

	function getPositionEventTypes($positionId=0)
	{
		$result=array();
		$query='	SELECT	pet.*,
							ppos.id AS pposid,
							ppos.position_id,
							et.name AS name,
							et.icon AS icon
					FROM #__joomleague_position_eventtype AS pet
					INNER JOIN #__joomleague_eventtype AS et ON et.id=pet.eventtype_id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.position_id=pet.position_id
					WHERE ppos.project_id='.$this->projectid.' AND et.published=1 ';
		if ($positionId > 0)
		{
			$query .= ' AND pet.position_id='.(int)$positionId;
		}
		$query .= ' ORDER BY pet.ordering, et.ordering';
		$this->_db->setQuery($query);
		$result=$this->_db->loadObjectList();
		if ($result)
		{
			if ($positionId)
			{
				return $result;
			}
			else
			{
				$posEvents=array();
				foreach ($result as $r)
				{
					$posEvents[$r->position_id][]=$r;
				}
				return ($posEvents);
			}
		}
		return array();
	}

	function getPlayerEventStats()
	{
		$playerstats=array();
		$projectPositionEventTypes = $this->getProjectPositionEventTypes();
		foreach ($projectPositionEventTypes as $projectPositionEventType)
		{
			$projectPositionId = $projectPositionEventType->project_position_id;
			$eventTypeId = $projectPositionEventType->event_type_id;
			if (!array_key_exists($projectPositionId, $playerstats))
			{
				$playerstats[$projectPositionId] = array();
			}
			$playerstats[$projectPositionId][$eventTypeId] = $this->getPositionEventStat($this->projectteamid, $projectPositionId, $eventTypeId);
		}
		return $playerstats;
	}

	function getProjectPositionEventTypes()
	{
		$query	= 'SELECT ppos.id AS project_position_id, pet.eventtype_id AS event_type_id'
				. ' FROM       #__joomleague_project_position AS ppos'
				. ' INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id'
				. ' INNER JOIN #__joomleague_position_eventtype AS pet ON pet.position_id=pos.id'
				. ' WHERE ppos.project_id='.$this->_db->Quote($this->projectid);
		$this->_db->setQuery($query);
		$result=$this->_db->loadObjectList();
		return $result;
	}

	function getPositionEventStat($projectTeamId, $projectPositionId, $eventTypeId)
	{
		$query	= ' FROM       #__joomleague_team_player        AS tp'
				. ' INNER JOIN #__joomleague_project_position   AS ppos ON ppos.id=tp.project_position_id'
				. ' INNER JOIN #__joomleague_position           AS pos ON pos.id=ppos.position_id'
				. ' INNER JOIN #__joomleague_person             AS per ON tp.person_id=per.id'
				. ' INNER JOIN #__joomleague_position_eventtype AS pet ON pet.position_id=pos.id'
				. ' INNER JOIN #__joomleague_eventtype          AS et ON et.id=pet.eventtype_id'
				. ' LEFT  JOIN #__joomleague_match_event        AS me ON me.event_type_id=et.id'
				. '                                             AND me.projectteam_id=tp.projectteam_id AND me.teamplayer_id=tp.id'
				. ' WHERE tp.projectteam_id = '.$this->_db->Quote($projectTeamId)
				. '   AND tp.project_position_id = '.$this->_db->Quote($projectPositionId)
				. '   AND et.id = '.$this->_db->Quote($eventTypeId)
				;
		$this->_db->setQuery('SELECT tp.person_id, COALESCE(sum(me.event_sum),0) AS value'.$query.' GROUP BY tp.person_id');
		$result=$this->_db->loadObjectList('person_id');

		$this->_db->setQuery('SELECT COALESCE(sum(me.event_sum),0) AS value'.$query);
		$result["totals"] = $this->_db->loadObject();
		$result["totals"]->person_id = 0;

		return $result;
	}

	function getInOutStats($player_id)
	{
		$teaminout=$this->_getTeamInOutStats();
		if (isset($teaminout[$player_id])) {
			return $teaminout[$player_id];
		}
		else {
			return null;
		}
	}

	function _getTeamInOutStats()
	{
		$projectteam=$this->getprojectteam();
		if (empty($this->_teaminout))
		{
			$projectteam_id = $this->_db->Quote($this->projectteamid);
			$query = '	SELECT	tp1.id AS tp_id1, tp1.person_id AS person_id1,
						tp2.id AS tp_id2, tp2.person_id AS person_id2,
						m.id AS mid, mp.came_in, mp.out, mp.in_for
					FROM #__joomleague_match AS m
					INNER JOIN #__joomleague_round r ON m.round_id=r.id
					INNER JOIN #__joomleague_project AS p ON p.id=r.project_id
					INNER JOIN #__joomleague_match_player AS mp ON mp.match_id=m.id
					INNER JOIN #__joomleague_team_player AS tp1 ON tp1.id=mp.teamplayer_id
					LEFT JOIN #__joomleague_team_player AS tp2 ON tp2.id = mp.in_for
					WHERE tp1.projectteam_id = '.$projectteam_id.'
					AND m.published = 1
					AND p.published = 1
					';
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			$played = array();
			$this->_teaminout = array();
			foreach ($rows AS $row)
			{
				// Handle the first player tp1 (is always there, either as the one that is in the starting line-up,
				// is coming in, or is going out (while no-one comes in))
				if (!array_key_exists($row->person_id1, $this->_teaminout))
				{
					$this->_teaminout[$row->person_id1] = new stdclass;
					$this->_teaminout[$row->person_id1]->played = 0;
					$this->_teaminout[$row->person_id1]->started = 0;
					$this->_teaminout[$row->person_id1]->sub_in = 0;
					$this->_teaminout[$row->person_id1]->sub_out = 0;
				}
				if (!array_key_exists($row->person_id1, $played))
				{
					$played[$row->person_id1] = array();
				}
				if (!array_key_exists($row->mid, $played[$row->person_id1]))
				{
					$played[$row->person_id1][$row->mid] = 0;
				}
				$played[$row->person_id1][$row->mid] |= ($row->came_in == 0) || ($row->came_in == 1);
				$this->_teaminout[$row->person_id1]->started += ($row->came_in == 0);
				$this->_teaminout[$row->person_id1]->sub_in  += ($row->came_in == 1);
				$this->_teaminout[$row->person_id1]->sub_out += ($row->out == 1);

				// Handle the second player tp2 (only applicable when one goes out AND another comes in; tp2 is the player that goes out)
				if (isset($row->person_id2))
				{
					// As the rows can appear in any order it can happen that the player goes out
					// before the row where the player came in is processed. Therefore we need to
					// check if the entry already exists in the array, and create it when necessary.
					if (!array_key_exists($row->person_id2, $this->_teaminout))
					{
						$this->_teaminout[$row->person_id2] = new stdclass;
						$this->_teaminout[$row->person_id2]->played = 0;
						$this->_teaminout[$row->person_id2]->started = 0;
						$this->_teaminout[$row->person_id2]->sub_in = 0;
						$this->_teaminout[$row->person_id2]->sub_out = 0;
					}
					if (!array_key_exists($row->person_id2, $played))
					{
						$played[$row->person_id2] = array();
					}
					$played[$row->person_id2][$row->mid] = 1;
					$this->_teaminout[$row->person_id2]->sub_out++;
				}
			}
			foreach ($played AS $k => $v)
			{
				foreach ($v as $m)
				{
					$this->_teaminout[$k]->played += $m;
				}
			}
		}

		return $this->_teaminout;
	}

	function getTimePlayed($player_id,$game_regular_time,$match_id = NULL)
	{
		$result = 0;
		$db = JFactory::getDbo();
		
		// starting line up without subs in/out
		$query = $db->getQuery(true);
		$query->select('count(match_id) as totalmatch');
		$query->from('#__joomleague_match_player');
		$query->where('teamplayer_id = '.$player_id);
		$query->where('came_in = 0');
		if ($match_id)
		{
			$query->where('match_id = '.$match_id);
		}
		$db->setQuery($query);
		$totalresult = $db->loadObject();

		if ($totalresult)
		{
			$result += $totalresult->totalmatch * $game_regular_time;
		}

		// subs in
		$query = $db->getQuery(true);
		$query->select('count(match_id) as totalmatch, SUM(in_out_time) as totalin');
		$query->from('#__joomleague_match_player');
		$query->where('teamplayer_id = '.$player_id);
		$query->where('came_in = 1');
		$query->where('in_for IS NOT NULL');
		if ($match_id)
		{
			$query->where('match_id = '.$match_id);
		}
		$db->setQuery($query);
		$cameinresult = $db->loadObject();

		if ($cameinresult)
		{
			$result += ($cameinresult->totalmatch * $game_regular_time) - ($cameinresult->totalin);
		}

		// subs out
		$query = $db->getQuery(true);
		$query->select('count(match_id) as totalmatch, SUM(in_out_time) as totalout');
		$query->from('#__joomleague_match_player');
		$query->where('in_for = '.$player_id);
		$query->where('came_in = 1');
		
		if ($match_id)
		{
			$query->where('match_id = '.$match_id);
		}
		$db->setQuery($query);
		$cameoutresult = $db->loadObject();

		if ($cameoutresult)
		{
			$result += ($cameoutresult->totalout) - ($cameoutresult->totalmatch * $game_regular_time);
		}

		// get all events which leads to a suspension (e.g. red card)
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_eventtype');
		$query->where('suspension = 1');
		$db->setQuery($query);
		
		$suspension_events = $db->loadColumn();
		$suspension_events = implode(',',$suspension_events);

		// find matches where the player was suspended because of e.g. a red card
		if (!empty($suspension_events))
		{
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__joomleague_match_event');
			$query->where('teamplayer_id = '.$player_id);
			$query->where('event_type_id IN ('.$suspension_events.')');
		
			if ($match_id)
			{
				$query->where('match_id = '.$match_id);
			}
			$db->setQuery($query);
			$cardsresult = $db->loadObjectList();
			
			foreach ($cardsresult as $row)
			{
			    $result -= ($game_regular_time - $row->event_time);
			}
		}

		return $result;
	}

	/**
	 * return the injury,suspension,away data from a player
	 *
	 * @param int $round_id
	 * @param int $player_id
	 * @return object
	 */
	function getTeamPlayer($round_id,$player_id)
	{
		$query="	SELECT	tp.*,
							pt.picture,
							ppos.id As pposid,
							pos.id AS position_id,

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
					FROM #__joomleague_team_player AS tp
					INNER JOIN #__joomleague_person AS pr ON tp.person_id=pr.id
					INNER JOIN #__joomleague_project_team AS pt ON pt.id=tp.projectteam_id
					INNER JOIN #__joomleague_round AS r ON r.project_id=pt.project_id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=tp.project_position_id
					INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
					LEFT JOIN #__joomleague_round AS rinjuryfrom ON tp.injury_date=rinjuryfrom.id
					LEFT JOIN #__joomleague_round AS rinjuryto ON tp.injury_end=rinjuryto.id
					LEFT JOIN #__joomleague_round AS rsuspfrom ON tp.suspension_date=rsuspfrom.id
					LEFT JOIN #__joomleague_round AS rsuspto ON tp.suspension_end=rsuspto.id
					LEFT JOIN #__joomleague_round AS rawayfrom ON tp.away_date=rawayfrom.id
					LEFT JOIN #__joomleague_round AS rawayto ON tp.away_end=rawayto.id
					WHERE r.id=".$round_id."
					  AND tp.id=".$player_id."
					  AND pr.published = '1'
					  AND tp.published = '1'
					  ";
		$this->_db->setQuery($query);
		$rows=$this->_db->loadObjectList();
		return $rows;
	}

	function getRosterStats()
	{
		$stats = $this->getProjectStats();
		$projectteam = $this->getprojectteam();
		$result=array();
		foreach ($stats as $pos => $pos_stats)
		{
			foreach ($pos_stats as $k => $stat)
			{
				$result[$pos][$stat->id]=$stat->getRosterStats($projectteam->team_id,$projectteam->project_id, $pos);
			}
		}
		return $result;
	}

}
