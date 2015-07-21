<?php
/**
 * Joomleague
 * @subpackage	Module-Randomplayer
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * Randomplayer Module helper
 */
abstract class modJLGRandomplayerHelper
{

	/**
	 * Method to get the list
	 *
	 * @access public
	 * @return array
	 */
	public static function getData(&$params)
	{
		$usedp = $params->get('projects');
		$usedtid = $params->get('teams', '0');
		$projectstring = (is_array($usedp)) ? implode(",", $usedp) : $usedp;
		$teamstring = (is_array($usedtid)) ? implode(",", $usedtid) : $usedtid;

		$db  = JFactory::getDbo();

		$query = "SELECT id
					FROM #__joomleague_project_team tt WHERE tt.project_id > 0 ";
		if($projectstring!="" && $projectstring > 0) {
			$query .=	" AND tt.project_id IN (". $projectstring .") ";
		}
		if($teamstring!="" && $teamstring > 0) {
			$query .= " AND tt.team_id IN (". $teamstring .") ";
		}
		$query .= " ORDER BY rand() ";
		$query .= " LIMIT 1";
		$db->setQuery( $query );
		$projectteamid = $db->loadResult();
		$query = '	SELECT	 pt.person_id, tt.project_id '
		. ' FROM #__joomleague_team_player pt '
		. ' INNER JOIN #__joomleague_project_team AS tt ON tt.id = pt.projectteam_id '
		. ' WHERE pt.projectteam_id = ' . $projectteamid
		. ' ORDER BY rand() '
		. ' LIMIT 1';

		$db->setQuery( $query );
		$res=$db->loadRow();

		JRequest::setVar('p', $res[1]);
		JRequest::setVar('pid', $res[0]);
		JRequest::setVar('pt', $projectteamid);

		if (!class_exists('JoomleagueModelPlayer')) {
			require_once JLG_PATH_SITE.'/models/player.php';
		}

		$mdlPerson 	= JLGModel::getInstance('Player', 'JoomleagueModel');

		$person 	= $mdlPerson->getPerson();
		$project	= $mdlPerson->getProject();
		$current_round = isset($project->current_round) ? $project->current_round : 0;
		$person_id	= isset($person->id) ? $person->id : 0;
		$player		= $mdlPerson->getTeamPlayerByRound($current_round, $person_id);
		$infoteam	= $mdlPerson->getTeaminfo($projectteamid);

		return array(	'project' 		=> $project,
						'player' 		=> $person, 
						'inprojectinfo'	=> !empty($player) ? $player[0] : array(),
						'infoteam'		=> $infoteam);
	}
}