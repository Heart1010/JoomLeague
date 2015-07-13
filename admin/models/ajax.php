<?php
/**
 * @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Joomleague Component Ajax Model
 *
 * @package		Joomleague
 * @since 0.1
 */
class JoomleagueModelAjax extends JModelLegacy
{
	public function addGlobalSelectElement($elements, $required=false) {
		if(!$required)  {
			$mitems = array(JHtml::_('select.option', '', JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT')));
			return array_merge($mitems, $elements);
		}
		return $elements;
	}
	
	function getProjectsBySportsTypesOptions($sports_type_id, $required = false)
	{
		$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(':', p.id, p.alias) ELSE p.id END AS value,
									concat(p.name, ' (".JText::_('COM_JOOMLEAGUE_GLOBAL_LEAGUE').": ', l.name, ')', ' (".JText::_('COM_JOOMLEAGUE_GLOBAL_SEASON').": ', s.name, ' )' ) as text
									FROM #__joomleague_project AS p 
									JOIN #__joomleague_sports_type AS st ON st.id = p.sports_type_id
									JOIN #__joomleague_season AS s ON s.id = p.season_id
									JOIN #__joomleague_league AS l ON l.id = p.league_id
									WHERE p.sports_type_id = " . $this->_db->Quote($sports_type_id) . "
									ORDER BY p.name" );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getProjectDivisionsOptions($project_id, $required = false)
	{
		$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(d.alias) THEN CONCAT_WS(':', d.id, d.alias) ELSE d.id END AS value,
									d.name AS text
									FROM #__joomleague_project_team pt
									JOIN #__joomleague_division d ON d.id = pt.division_id
									JOIN #__joomleague_project p ON p.id = pt.project_id
									WHERE pt.project_id = " . $this->_db->Quote($project_id) . "
				                    GROUP BY d.id
									ORDER BY d.name" );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getProjectTeamsByDivisionOptions($project_id, $division_id=0, $required=false)
	{
		$query = "	SELECT CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(':', t.id, t.alias) ELSE t.id END AS value,
									t.name AS text
					FROM #__joomleague_project_team pt
					JOIN #__joomleague_team t ON t.id = pt.team_id
					JOIN #__joomleague_project p ON p.id = pt.project_id
					WHERE pt.project_id = " . $this->_db->Quote($project_id);
		if($division_id>0) {
			$query .= " AND pt.division_id = " . $this->_db->Quote($division_id);
		}
		$query .= " ORDER BY t.name";
		$this->_db->setQuery($query);
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}
	
	function getProjectsByClubOptions($club_id, $required=false)
	{
		if($club_id == 0) {
			$projects = (array) $this->getProjects();
			return $this->addGlobalSelectElement($projects, $required);
		} else {
			$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(':', p.id, p.alias) ELSE p.id END AS value,
										p.name AS text
										FROM #__joomleague_project_team pt
										JOIN #__joomleague_team t ON t.id = pt.team_id
										JOIN #__joomleague_project p ON p.id = pt.project_id
										WHERE t.club_id = " . $this->_db->Quote($club_id) . "
										ORDER BY p.name" );
			return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
		}
	}
	
	function getProjects()
	{
		$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(':', p.id, p.alias) ELSE p.id END AS value,
									p.name AS text
									FROM #__joomleague_project p 
									ORDER BY p.name" );
		return $this->_db->loadObjectList();
	}
	
	function getProjectTeamOptions($project_id, $division_id=0, $required = false)
	{
		$query = "	SELECT CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(':', t.id, t.alias) ELSE t.id END AS value,
					t.name AS text
					FROM #__joomleague_project_team AS pt
					JOIN #__joomleague_team t ON t.id = pt.team_id
					JOIN #__joomleague_project p ON p.id = pt.project_id
					WHERE pt.project_id = " . $this->_db->Quote($project_id);
		if($division_id>0) {
			$query .= " AND pt.division_id = " . $this->_db->Quote($division_id);
		}
		$query .= " ORDER BY t.name";
		$this->_db->setQuery($query);
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getProjectPlayerOptions($project_id, $division_id=0, $required = false)
	{
		$option = JRequest::getCmd('option');
		$params = JComponentHelper::getParams( $option );
		$default_name_format = $params->get("name_format");
		$query = "	SELECT CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(':', p.id, p.alias) ELSE p.id END AS value,
					p.firstname, p.nickname, p.lastname, p.birthday, p.id
					FROM #__joomleague_person p
					JOIN #__joomleague_team_player tp ON tp.person_id = p.id
					JOIN #__joomleague_project_team pt ON pt.id = tp.projectteam_id
					WHERE pt.project_id = " . $this->_db->Quote($project_id);
		if($division_id > 0)
		{
			$query .= " AND pt.division_id = " . $this->_db->Quote($division_id);
		}
		$query .= " AND p.published = '1'
					GROUP BY p.id
					ORDER BY p.lastname, p.firstname, p.nickname"; 
		$this->_db->setQuery($query);
		$persons = $this->_db->loadObjectList();
		if ($persons)
		{
			foreach ($persons as $person)
			{
				$name = JoomleagueHelper::formatName(	null, 
														$person->firstname, 
														$person->nickname, 
														$person->lastname, 
														$default_name_format);
				$person->text = $name . " (" . $person->birthday . ")";
			}
		}
		return $this->addGlobalSelectElement($persons, $required);
	}

	function getProjectStaffOptions($project_id, $division_id=0, $required = false)
	{
		$option = JRequest::getCmd('option');
		$params = JComponentHelper::getParams( $option );
		$default_name_format = $params->get("name_format");
		$query = "	SELECT CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(':', p.id, p.alias) ELSE p.id END AS value,
					p.firstname, p.nickname, p.lastname, p.birthday, p.id
					FROM #__joomleague_person p
					JOIN #__joomleague_team_staff ts ON ts.person_id = p.id
					JOIN #__joomleague_project_team pt ON pt.id = ts.projectteam_id
					WHERE pt.project_id = " . $this->_db->Quote($project_id);
		if($division_id > 0)
		{
			$query .= " AND pt.division_id = " . $this->_db->Quote($division_id);
		}
		$query .= " AND p.published = '1'
					GROUP BY p.id
					ORDER BY p.lastname, p.firstname, p.nickname";
		$this->_db->setQuery($query);
		$persons = $this->_db->loadObjectList();
		if ($persons)
		{
			foreach ($persons as $person)
			{
				$name = JoomleagueHelper::formatName(	null, 
														$person->firstname, 
														$person->nickname, 
														$person->lastname, 
														$default_name_format);
				$person->text = $name . " (" . $person->birthday . ")";
			}
		}
		return $this->addGlobalSelectElement($persons, $required);
	}

	function getProjectClubOptions($project_id, $required = false)
	{
		$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END AS value,
									c.name AS text
									FROM #__joomleague_project_team pt
									INNER JOIN #__joomleague_team t ON t.id = pt.team_id
									INNER JOIN #__joomleague_club AS c ON c.id = t.club_id
									INNER JOIN #__joomleague_project p ON p.id = pt.project_id
									WHERE pt.project_id = " . $this->_db->Quote($project_id) . "
									GROUP BY c.id
									ORDER BY c.name" );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getProjectEventsOptions($project_id, $required = false)
	{
		$query = " 	SELECT	CASE WHEN CHAR_LENGTH(et.alias) THEN CONCAT_WS(':', et.id, et.alias) ELSE et.id END AS value,
							et.name AS text
					FROM #__joomleague_eventtype as et 
					INNER JOIN #__joomleague_match_event as me ON et.id = me.event_type_id 
					INNER JOIN #__joomleague_match as m ON m.id = me.match_id 
					INNER JOIN #__joomleague_round as r ON m.round_id = r.id
					WHERE r.project_id=" . $project_id . "
					GROUP BY et.id
					ORDER BY et.ordering";

		$this->_db->setQuery( $query );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}


	function getProjectStatOptions($project_id, $required=false)
	{
		$this->_db->setQuery(	"	SELECT CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(':', s.id, s.alias) ELSE s.id END AS value,
									s.name AS text
									FROM #__joomleague_project_position AS ppos
									INNER JOIN #__joomleague_position_statistic AS ps ON ps.position_id = ppos.position_id
									INNER JOIN #__joomleague_statistic AS s ON s.id = ps.statistic_id
									INNER JOIN #__joomleague_project p ON p.id = ppos.project_id
									WHERE ppos.project_id = " . $this->_db->Quote($project_id) . "
									GROUP BY s.id
									ORDER BY s.name" );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getMatchesOptions($project_id, $division=0, $required=false)
	{
		$lang		= JFactory::getLanguage();
		$extension 	= "com_joomleague";
		$source 	= JPath::clean(JPATH_SITE. '/components/' . $extension);
		$lang->load($extension, JPATH_SITE, null, false, false)
		||	$lang->load($extension, $source, null, false, false)
		||	$lang->load($extension, JPATH_SITE, $lang->getDefault(), false, false)
		||	$lang->load($extension, $source, $lang->getDefault(), false, false);
		
		$query = "	SELECT	m.id AS value, m.match_date,
							CONCAT(t1.middle_name, ' - ', t2.middle_name) AS text,
							p.timezone
					FROM #__joomleague_match AS m
					INNER JOIN #__joomleague_project_team AS pt1 ON m.projectteam1_id = pt1.id
					INNER JOIN #__joomleague_project_team AS pt2 ON m.projectteam2_id = pt2.id
					INNER JOIN #__joomleague_team AS t1 ON pt1.team_id=t1.id
					INNER JOIN #__joomleague_team AS t2 ON pt2.team_id=t2.id
					INNER JOIN #__joomleague_project p ON p.id = pt1.project_id
					WHERE pt1.project_id = ".$this->_db->Quote($project_id);
		if($division>0)
		{
			$query .= " AND (pt1.division_id = " . $this->_db->Quote($division);
			$query .= " OR pt2.division_id = " . $this->_db->Quote($division) . ") ";
		}
		$query .= " ORDER BY m.match_date, t1.short_name";
		$this->_db->setQuery($query);
		$matches = $this->_db->loadObjectList();
		foreach ($matches as $match)
		{
 			JoomleagueHelper::convertMatchDateToTimezone($match);
 			$match->text = JoomleagueHelper::getMatchDate($match, JText::_('COM_JOOMLEAGUE_MATCHDAYDATE') . ' H:i').' - '.$match->text;
		}
		return $this->addGlobalSelectElement($matches, $required);
	}

	function getRefereesOptions($project_id, $required = false)
	{
		$query = "	SELECT	p.id AS value,
									CONCAT(p.firstname, ' ', p.lastname) AS text
					FROM #__joomleague_person AS p
					INNER JOIN #__joomleague_project_referee AS pr ON pr.person_id = p.id
					WHERE pr.project_id = ".$this->_db->Quote($project_id)."
					  AND p.published = '1'
					ORDER BY text"
					;
		$this->_db->setQuery($query);
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}

	function getProjectTreenodeOptions($project_id, $required = false)
	{
		$this->_db->setQuery(	"	SELECT tt.id AS value,
									tt.id AS text
									FROM #__joomleague_treeto AS tt
									JOIN #__joomleague_project p ON p.id = tt.project_id
									WHERE tt.project_id = " . $this->_db->Quote($project_id) . "
									ORDER BY tt.id" );
		return $this->addGlobalSelectElement($this->_db->loadObjectList(), $required);
	}


}
?>
