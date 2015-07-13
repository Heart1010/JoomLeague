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
jimport('joomla.html.pane');
JHtml::_('behavior.tooltip');

require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

class JoomleagueModelResults extends JoomleagueModelProject
{
	var $projectid	= 0;
	var $divisionid	= 0;
	var $roundid = 0;
	var $rounds = array(0);
	var $mode = 0;
	var $order = 0;
	var $config = 0;
	var $project = null;
	var $matches = null;

	function __construct()
	{
		parent::__construct();
		$this->divisionid = JRequest::getInt('division',0);
		$this->mode = JRequest::getInt('mode',0);
		$this->order = JRequest::getInt('order',0);
		$round = JRequest::getInt('r', 0);
		$roundid = $round;
		if ($round > 0) {
			$roundid = $round;
		} else {
			$roundid = $this->getCurrentRound();
		}
		$this->roundid = $roundid;
		$this->config = $this->getTemplateConfig('results');
	}

	function getDivisionID()
	{
		return $this->divisionid;
	}

	function getDivision()
	{
		$division=null;
		if ($this->divisionid > 0)
		{
			$division =& $this->getTable('Division','Table');
			$division->load($this->divisionid);
		}

		return $division;
	}

	/**
	 * get games
	 * @return array
	 */
	function getMatches()
	{
		if (is_null($this->matches))
		{
			$this->matches = $this->getResultsRows($this->roundid,$this->divisionid,$this->config);
		}
		
		$allowed = $this->isAllowed();
		$user = JFactory::getUser();

		if (count($this->matches)>0)
		{
			foreach ($this->matches as $k => $match)
			{
				JoomleagueHelper::convertMatchDateToTimezone($match);
				if (($match->checked_out==0)||($match->checked_out==$user->id))
				{
					if (($allowed)||($this->isMatchAdmin($match->id)))
					{
						$this->matches[$k]->allowed=true;
					}
				}
			}
		}
		return $this->matches;
	}

	/**
	 * return array of games
	 * @param int round id,0 for current round
	 * @param int division id (0 for project)
	 * @param array config
	 * @param int teamId for this team games only
	 * @param string ordering
	 * @param boolean unpublished
	 * @return array
	 */
	function getResultsRows($round,$division,&$config)
	{
		$project=$this->getProject();

		if (!$round) {
			$round = $this->getCurrentRound();
		}

		$result=array();

		$query_SELECT='
		SELECT	m.*, p.timezone, 
			DATE_FORMAT(m.time_present,"%H:%i") time_present,
			playground.name AS playground_name,
			playground.short_name AS playground_short_name,
			pt1.project_id, d1.name as divhome, d2.name as divaway,
			CASE WHEN CHAR_LENGTH(t1.alias) AND CHAR_LENGTH(t2.alias) THEN CONCAT_WS(\':\',m.id,CONCAT_WS("_",t1.alias,t2.alias)) ELSE m.id END AS slug ';

		$query_FROM='
		FROM #__joomleague_match AS m
			INNER JOIN #__joomleague_round AS r ON m.round_id=r.id
			INNER JOIN #__joomleague_project AS p ON p.id=r.project_id
			LEFT JOIN #__joomleague_project_team AS pt1 ON m.projectteam1_id=pt1.id
			LEFT JOIN #__joomleague_project_team AS pt2 ON m.projectteam2_id=pt2.id
			LEFT JOIN #__joomleague_team AS t1 ON t1.id=pt1.team_id
			LEFT JOIN #__joomleague_team AS t2 ON t2.id=pt2.team_id
			LEFT JOIN #__joomleague_division AS d1 ON pt1.division_id=d1.id
			LEFT JOIN #__joomleague_division AS d2 ON pt2.division_id=d2.id
			LEFT JOIN #__joomleague_playground AS playground ON playground.id=m.playground_id';

		$query_WHERE	= ' WHERE m.published=1 
							  AND r.id='.$round.' 
							  AND r.project_id='.(int)$project->id;
		$query_END		= ' GROUP BY m.id ORDER BY m.match_date ASC,m.match_number';

		if ($division>0)
		{
			$query_WHERE .= '
 			AND	(
				d1.id='.$division.' OR
				d1.parent_id='.$division.' OR
				d2.id='.$division.' OR
				d2.parent_id='.$division.'
			)';
		}

		$query=$query_SELECT.$query_FROM.$query_WHERE.$query_END;
		if (!is_null($round)) {
			$this->_db->setQuery($query);
		}
		return $this->_db->loadObjectList();
	}

	/**
	 * returns match referees
	 * @param int match id
	 * @return array
	 */
	function getMatchReferees($match_id)
	{
		$query='	SELECT	pref.id AS person_id,
								p.firstname,
								p.lastname,
								pos.name AS position_name
						FROM #__joomleague_match_referee AS mr
							LEFT JOIN #__joomleague_project_referee AS pref ON mr.project_referee_id=pref.id
							INNER JOIN #__joomleague_person AS p ON pref.person_id=p.id
							LEFT JOIN #__joomleague_project_position AS ppos ON mr.project_position_id=ppos.id
							LEFT JOIN #__joomleague_position AS pos ON ppos.position_id=pos.id
						WHERE mr.match_id='.(int)$match_id.' AND p.published = 1 ORDER BY pos.name,mr.ordering ';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	/**
	 * returns referees (as teamname) who ruled in specific match
	 *
	 * @param int $team_id
	 * @param int $position_id
	 * @return array of players
	 */
	function getMatchRefereeTeams($match_id)
	{
		$query='	SELECT	mr.project_referee_id AS value,
								t.name AS teamname,
								pos.name AS position_name

						FROM #__joomleague_match_referee AS mr
							LEFT JOIN #__joomleague_project_team AS pt ON pt.id=mr.project_referee_id
							LEFT JOIN #__joomleague_team AS t ON t.id=pt.team_id
							LEFT JOIN #__joomleague_position AS pos ON mr.project_position_id=pos.id

						WHERE mr.match_id='.(int) $match_id.' ORDER BY pos.name,mr.ordering ASC ';

		$this->_db->setQuery($query);

		return $this->_db->loadObjectList('value');
	}

	function isMatchAdmin($matchid)
	{
		$result=false;
		$project_id = $this->getProject()->id; 
		$result = (	JFactory::getUser()->authorise('core.admin', 'com_joomleague.project.'.$project_id) || 
					JFactory::getUser()->authorise('core.manage', 'com_joomleague.project.'.$project_id) || 
					JFactory::getUser()->authorise('core.edit', 'com_joomleague.match.'.$matchid) ? true : false);			
		return $result;
	}

	function isAllowed()
	{
		$allowed = false;
		$user = JFactory::getUser();
		if ($user->id != 0)
		{
			$project = $this->getProject();
			// Check if user is project admin or editor
			if ( $this->isUserProjectAdminOrEditor($user->id, $project))
			{
				$allowed = true;
			}
			//If not, then check if user has ACL rights
			if (!$allowed)
			{
				if (!$user->authorise('match.saveshort', 'com_joomleague')) {
					$allowed = false;
				} else {
					$allowed = true;
				}
			}
		}
		return $allowed;
	}

	function getShowEditIcon()
	{
		$allowed = $this->isAllowed();
		$showediticon = false;

		$user = JFactory::getUser();
		if ($user->id != 0)
		{
			if ($allowed)
			{
				$showediticon=true;
			}
		}
		return $showediticon;
	}

	function getFavTeams(&$project)
	{
		$favteams=explode(',',$project->fav_team);
		return $favteams;
	}
}
?>