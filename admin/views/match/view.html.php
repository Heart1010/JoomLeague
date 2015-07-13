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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.filesystem.file' );
JHtml::_ ( 'behavior.framework' );
/**
 * HTML View class for the Joomleague component
 *
 * @author Marco Vaninetti <martizva@tiscali.it>
 * @package JoomLeague
 * @since 0.1
 */
class JoomleagueViewMatch extends JLGView {
	function display($tpl = null) {
		if ($this->getLayout () == 'form') {
			$this->_displayForm ( $tpl );
			return;
		} elseif ($this->getLayout () == 'editevents') {
			$this->_displayEditevents ( $tpl );
			return;
		} elseif ($this->getLayout () == 'editeventsbb') {
			$this->_displayEditeventsbb ( $tpl );
			return;
		} elseif ($this->getLayout () == 'editstats') {
			$this->_displayEditstats ( $tpl );
			return;
		} elseif ($this->getLayout () == 'editlineup') {
			$this->_displayEditlineup ( $tpl );
			return;
		} elseif ($this->getLayout () == 'editreferees') {
			$this->_displayEditReferees ( $tpl );
			return;
		}

		parent::display ( $tpl );
	}
	function _displayEditReferees($tpl) {
		$option = JRequest::getCmd ( 'option' );
		$mainframe = JFactory::getApplication ();
		$document = JFactory::getDocument ();
		$project_id = $mainframe->getUserState ( $option . 'project' );
		$params = JComponentHelper::getParams ( $option );
		$default_name_format = $params->get ( "name_format" );

		// add the js script
		$version = urlencode ( JoomleagueHelper::getVersion () );
		$document->addScript ( JUri::base () . 'components/com_joomleague/assets/js/startinglineup.js?v=' . $version );

		$model = $this->getModel ();
		$match = $this->get ( 'data' );

		$allreferees = array ();
		$allreferees = $model->getRefereeRoster ();
		$inroster = array ();
		$projectreferees = array ();
		$projectreferees2 = array ();

		if (isset ( $allreferees )) {
			foreach ( $allreferees as $referee ) {
				$inroster [] = $referee->value;
			}
		}
		$projectreferees = $model->getProjectReferees ( $inroster, $project_id );

		if (count ( $projectreferees ) > 0) {
			foreach ( $projectreferees as $referee ) {
				$projectreferees2 [] = JHtml::_ ( 'select.option', $referee->value, JoomleagueHelper::formatName ( null, $referee->firstname, $referee->nickname, $referee->lastname, $default_name_format ) . ' - (' . strtolower ( JText::_ ( $referee->positionname ) ) . ')' );
			}
		}
		$lists ['team_referees'] = JHtml::_ ( 'select.genericlist', $projectreferees2, 'roster[]', 'style="font-size:12px;height:auto;min-width:15em;" ' . 'class="inputbox" multiple="true" size="' . max ( 10, count ( $projectreferees2 ) ) . '"', 'value', 'text' );

		$selectpositions [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_GLOBAL_SELECT_REF_FUNCTION' ) );
		if ($projectpositions = $model->getProjectPositionsOptions ( 0, 3 )) {
			$selectpositions = array_merge ( $selectpositions, $projectpositions );
		}
		$lists ['projectpositions'] = JHtml::_ ( 'select.genericlist', $selectpositions, 'project_position_id', 'class="inputbox" size="1"', 'value', 'text' );

		$squad = array ();
		if (! $projectpositions) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_REF_POS' ) . '<br /><br />' );
			return;
		}

		// generate selection list for each position
		foreach ( $projectpositions as $key => $pos ) {
			// get referees assigned to this position
			$squad [$key] = $model->getRefereeRoster ( $pos->value );
		}
		if (count ( $squad ) > 0) {
			foreach ( $squad as $key => $referees ) {
				$temp [$key] = array ();
				if (isset ( $referees )) {
					foreach ( $referees as $referee ) {
						$temp [$key] [] = JHtml::_ ( 'select.option', $referee->value, JoomleagueHelper::formatName ( null, $referee->firstname, $referee->nickname, $referee->lastname, $default_name_format ) );
					}
				}
				$lists ['team_referees' . $key] = JHtml::_ ( 'select.genericlist', $temp [$key], 'position' . $key . '[]', 'id="testing" style="font-size:12px;height:auto;min-width:15em;" ' . 'class="inputbox position-starters" multiple="true" ', 'value', 'text' );
			}
		}
		$this->assignRef ( 'project_id', $project_id );
		$this->assignRef ( 'match', $match );
		$this->assignRef ( 'positions', $projectpositions );
		$this->assignRef ( 'lists', $lists );
		parent::display ( $tpl );
	}
	function _displayEditevents($tpl) {
		$option = JRequest::getCmd ( 'option' );
		$mainframe = JFactory::getApplication ();
		$project_id = $mainframe->getUserState ( $option . 'project' );
		$document = JFactory::getDocument ();
		$tid = JRequest::getVar ( 'team', '0' );
		$params = JComponentHelper::getParams ( $option );
		$default_name_format = $params->get ( "name_format", 14 );
		$default_name_dropdown_list_order = $params->get ( "cfg_be_name_dropdown_list_order", "lastname" );

		// add the js script
		$version = urlencode ( JoomleagueHelper::getVersion () );
		$document->addScript ( JUri::base () . 'components/com_joomleague/assets/js/editevents.js?v=' . $version );

		$model = $this->getModel ();
		$teams = $model->getMatchTeams ();

		if (is_null ( $teams )) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_TEAM_MATCH' ) . '<br /><br />' );
			return false;
		}
		$teamname = ($tid == $teams->projectteam1_id) ? $teams->team1 : $teams->team2;
		$this->_handlePreFillRoster ( $teams, $model, $params, $teams->projectteam1_id, $teamname );
		$this->_handlePreFillRoster ( $teams, $model, $params, $teams->projectteam2_id, $teamname );

		$homeRoster = $model->getTeamPlayers ( $teams->projectteam1_id, false, $default_name_dropdown_list_order );
		if (count ( $homeRoster ) == 0) {
			$homeRoster = $model->getGhostPlayer ();
		}
		$awayRoster = $model->getTeamPlayers ( $teams->projectteam2_id, false, $default_name_dropdown_list_order );
		if (count ( $awayRoster ) == 0) {
			$awayRoster = $model->getGhostPlayer ();
		}
		$rosters = array (
				'home' => $homeRoster,
				'away' => $awayRoster
		);
		$matchevents = & $model->getMatchEvents ();
		$project_model = $this->getModel ( 'project' );

		$lists = array ();

		// teams
		$teamlist = array ();
		$teamlist [] = JHtml::_ ( 'select.option', $teams->projectteam1_id, $teams->team1 );
		$teamlist [] = JHtml::_ ( 'select.option', $teams->projectteam2_id, $teams->team2 );
		$lists ['teams'] = JHtml::_ ( 'select.genericlist', $teamlist, 'team_id', 'class="inputbox select-team"' );

		// events
		$events = $model->getEventsOptions ( $project_id );
		if (! $events) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_EVENTS_POS' ) . '<br /><br />' );
			return false;
		}
		$eventlist = array ();
		$eventlist = array_merge ( $eventlist, $events );

		$lists ['events'] = JHtml::_ ( 'select.genericlist', $eventlist, 'event_type_id', 'class="inputbox select-event"' );

		$this->assignRef ( 'overall_config', $project_model->getTemplateConfig ( 'overall' ) );
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'rosters', $rosters );
		$this->assignRef ( 'teams', $teams );
		$this->assignRef ( 'matchevents', $matchevents );
		$this->assignRef ( 'default_name_format', $default_name_format );
		$this->assignRef ( 'default_name_dropdown_list_order', $default_name_dropdown_list_order );

		parent::display ( $tpl );
	}
	function _displayEditeventsbb($tpl) {
		$option = JRequest::getCmd ( 'option' );
		$mainframe = JFactory::getApplication ();
		$project_id = $mainframe->getUserState ( $option . 'project' );
		$document = JFactory::getDocument ();
		$params = JComponentHelper::getParams ( $option );
		$default_name_format = $params->get ( "name_format", 14 );
		$default_name_dropdown_list_order = $params->get ( "cfg_be_name_dropdown_list_order", "lastname" );
		$tid = JRequest::getVar ( 'team', '0' );

		$model = $this->getModel ();
		$teams = $model->getMatchTeams ();

		if (is_null ( $teams )) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_TEAM_MATCH' ) . '<br /><br />' );
			return false;
		}
		// events
		$events = $model->getEventsOptions ( $project_id );
		if (! $events) {
			$msg = '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_EVENTS_POS' ) . '<br /><br />';
			$mainframe->enqueueMessage ( $msg, 'warning' );
			$this->addToolbar_Editeventsbb ( false );
			return false;
		}

		$homeRoster = $model->getTeamPlayers ( $teams->projectteam1_id, false, $default_name_dropdown_list_order );
		if (count ( $homeRoster ) == 0) {
			$homeRoster = $model->getGhostPlayerbb ( $teams->projectteam1_id );
		}
		$awayRoster = $model->getTeamPlayers ( $teams->projectteam2_id, false, $default_name_dropdown_list_order );
		if (count ( $awayRoster ) == 0) {
			$awayRoster = $model->getGhostPlayerbb ( $teams->projectteam2_id );
		}

		$this->assignRef ( 'homeRoster', $homeRoster );
		$this->assignRef ( 'awayRoster', $awayRoster );
		$this->assignRef ( 'teams', $teams );
		$this->assignRef ( 'events', $events );
		$this->assignRef ( 'default_name_format', $default_name_format );
		$this->assignRef ( 'default_name_dropdown_list_order', $default_name_dropdown_list_order );

		$this->addToolbar_Editeventsbb ();
		parent::display ( $tpl );
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.7
	 */
	protected function addToolbar_Editeventsbb($showSave = true) {
		// set toolbar items for the page
		JToolBarHelper::title ( JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_EEBB_TITLE' ), 'events' );
		if ($showSave) {
			JLToolBarHelper::apply ( 'match.saveeventbb' );
		}
		JToolBarHelper::divider ();
		JToolBarHelper::back ( 'back', 'index.php?option=com_joomleague&view=matches&task=match.display' );
		JToolBarHelper::help ( 'screen.joomleague', true );
	}
	function _displayEditstats($tpl) {
		$option = JRequest::getCmd ( 'option' );
		$mainframe = JFactory::getApplication ();
		$project_id = $mainframe->getUserState ( $option . 'project' );
		$document = JFactory::getDocument ();
		$params = JComponentHelper::getParams ( $option );
		$default_name_format = $params->get ( "name_format" );
		$tid = JRequest::getVar ( 'team', '0' );

		// add the js script
		$version = urlencode ( JoomleagueHelper::getVersion () );
		$document->addScript ( JUri::base () . 'components/com_joomleague/assets/js/editmatchstats.js?v=' . $version );

		$model = $this->getModel ();
		$match = $this->get ( 'data' );
		$teams = $model->getMatchTeams ();

		if (is_null ( $teams )) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_TEAM_MATCH' ) . '<br /><br />' );
			return false;
		}

		$positions = $this->get ( 'ProjectPositions' );
		$staffpositions = $this->get ( 'ProjectStaffPositions' );

		$homeRoster = $model->getMatchPlayers ( $teams->projectteam1_id );
		if (count ( $homeRoster ) == 0) {
			$homeRoster = $model->getGhostPlayerbb ( $teams->projectteam1_id );
		}
		$awayRoster = $model->getMatchPlayers ( $teams->projectteam2_id );
		if (count ( $awayRoster ) == 0) {
			$awayRoster = $model->getGhostPlayerbb ( $teams->projectteam2_id );
		}

		$homeStaff = $model->getMatchStaffs ( $teams->projectteam1_id );
		$awayStaff = $model->getMatchStaffs ( $teams->projectteam2_id );

		// stats
		$stats = $model->getInputStats ();
		if (! $stats) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_STATS_POS' ) . '<br /><br />' );
			return false;
		}
		$playerstats = $model->getMatchStatsInput ();
		$staffstats = $model->getMatchStaffStatsInput ();

		$this->assignRef ( 'homeRoster', $homeRoster );
		$this->assignRef ( 'awayRoster', $awayRoster );
		$this->assignRef ( 'homeStaff', $homeStaff );
		$this->assignRef ( 'awayStaff', $awayStaff );
		$this->assignRef ( 'teams', $teams );
		$this->assignRef ( 'stats', $stats );
		$this->assignRef ( 'playerstats', $playerstats );
		$this->assignRef ( 'staffstats', $staffstats );
		$this->assignRef ( 'match', $match );
		$this->assignRef ( 'positions', $positions );
		$this->assignRef ( 'staffpositions', $staffpositions );
		$this->assignRef ( 'default_name_format', $default_name_format );

		parent::display ( $tpl );
	}
	function _displayEditlineup($tpl) {
		$option = JRequest::getCmd ( 'option' );
		$mainframe = JFactory::getApplication ();
		$project_id = $mainframe->getUserState ( $option . 'project' );
		$document = JFactory::getDocument ();
		$tid = JRequest::getVar ( 'team', '0' );
		$params = JComponentHelper::getParams ( $option );
		$default_name_format = $params->get ( "name_format" );
		$default_name_dropdown_list_order = $params->get ( "cfg_be_name_dropdown_list_order", "lastname" );

		// add the js script
		$version = urlencode ( JoomleagueHelper::getVersion () );
		$document->addScript ( JUri::base () . 'components/com_joomleague/assets/js/startinglineup.js?v=' . $version );

		$model = $this->getModel ();
		$teams = $model->getMatchTeams ();

		if (is_null ( $teams )) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_TEAM_MATCH' ) . '<br /><br />' );
			return false;
		}
		$teamname = ($tid == $teams->projectteam1_id) ? $teams->team1 : $teams->team2;
		$this->_handlePreFillRoster ( $teams, $model, $params, $tid, $teamname );

		// get starters
		$starters = $model->getRoster ( $tid );
		$starters_id = array_keys ( $starters );

		// get players not already assigned to starter
		$not_assigned = $model->getTeamPlayers ( $tid, $starters_id, $default_name_dropdown_list_order );
		if (! $not_assigned && ! $starters_id) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_PLAYERS_MATCH' ) . '<br /><br />' );
			return false;
		}

		$projectpositions = & $model->getProjectPositions ();
		if (! $projectpositions) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_POS' ) . '<br /><br />' );
			return false;
		}

		// build select list for not assigned players
		$not_assigned_options = array ();
		foreach ( ( array ) $not_assigned as $p ) {
			if ($p->jerseynumber > 0) {
				$jerseynumber = '[' . $p->jerseynumber . '] ';
			} else {
				$jerseynumber = '';
			}
			switch ($default_name_dropdown_list_order) {
				case 'lastname' :
				case 'firstname' :
					$not_assigned_options [] = JHtml::_ ( 'select.option', $p->value, $jerseynumber . JoomleagueHelper::formatName ( null, $p->firstname, $p->nickname, $p->lastname, $default_name_format ) );
					break;

				case 'position' :
					$not_assigned_options [] = JHtml::_ ( 'select.option', $p->value, '(' . JText::_ ( $p->positionname ) . ') - ' . $jerseynumber . JoomleagueHelper::formatName ( null, $p->firstname, $p->nickname, $p->lastname, $default_name_format ) );
					break;
			}
		}
		$lists ['team_players'] = JHtml::_ ( 'select.genericlist', $not_assigned_options, 'roster[]', 'style="font-size:12px;height:auto;min-width:15em;" class="inputbox" multiple="true" size="18"', 'value', 'text' );

		// build position select
		$selectpositions [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_GLOBAL_SELECT_IN_POSITION' ) );
		$selectpositions = array_merge ( $selectpositions, $model->getProjectPositionsOptions ( 0, 1 ) );
		$lists ['projectpositions'] = JHtml::_ ( 'select.genericlist', $selectpositions, 'project_position_id', 'class="inputbox" size="1"', 'value', 'text', NULL, false, true );

		// build player select for substitutions

		// starters + came in (because of multiple substitutions possibility in amateur soccer clubs for example)
		$substitutions = $model->getSubstitutions ( $tid );
		$starters = array_merge ( $starters, $substitutions [$tid] );

		// not assigned players + went out (because of multiple substitutions possibility in amateur soccer clubs for example)
		$not_assigned = array_merge ( $not_assigned, $substitutions [$tid] );

		// filter out duplicates $starters
		$new_starters = array ();
		$exclude = array (
				""
		);
		for($i = 0; $i <= count ( $starters ) - 1; $i ++) {
			if (! in_array ( trim ( $starters [$i]->value ), $exclude )) {
				$new_starters [] = $starters [$i];
				$exclude [] = trim ( $starters [$i]->value );
			}
		}
		// filter out duplicates $not_assigned
		$new_not_assigned = array ();
		$exclude = array (
				""
		);
		for($i = 0; $i <= count ( $not_assigned ) - 1; $i ++) {
			if (array_key_exists ( 'came_in', $not_assigned [$i] ) && $not_assigned [$i]->came_in == 1) {
				if (! in_array ( trim ( $not_assigned [$i]->in_for ), $exclude )) {
					$new_not_assigned [] = $not_assigned [$i];
					$exclude [] = trim ( $not_assigned [$i]->in_for );
				}
			} elseif (! array_key_exists ( 'came_in', $not_assigned [$i] )) {
				if (! in_array ( trim ( $not_assigned [$i]->value ), $exclude )) {
					$new_not_assigned [] = $not_assigned [$i];
					$exclude [] = trim ( $not_assigned [$i]->value );
				}
			}
		}
		// echo "<pre>";
		// echo var_dump($new_not_assigned);
		// echo "</pre>";

		$playersoptions_subs_out = array ();
		$playersoptions_subs_out [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_GLOBAL_SELECT_PLAYER' ) );
		$i = 0;
		foreach ( ( array ) $new_starters as $player ) {
			switch ($default_name_dropdown_list_order) {
				case 'lastname' :
				case 'firstname' :
					if (array_key_exists ( 'came_in', $player )) {
						$i ++;
						if ($i == 1) {
							$playersoptions_subs_out[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_ELUSUBST_SELECT_PLAYER_ALREADY_IN'));
						}
					}
					$playersoptions_subs_out [] = JHtml::_ ( 'select.option', $player->value, JoomleagueHelper::formatName ( null, $player->firstname, $player->nickname, $player->lastname, $default_name_format ) );
					break;

				case 'position' :
					if (array_key_exists ( 'came_in', $player )) {
						$i ++;
						if ($i == 1) {
							$playersoptions_subs_out[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_ELUSUBST_SELECT_PLAYER_ALREADY_IN'));
						}
					}
					$playersoptions_subs_out [] = JHtml::_ ( 'select.option', $player->value, '(' . JText::_ ( $player->positionname ) . ') - ' . JoomleagueHelper::formatName ( null, $player->firstname, $player->nickname, $player->lastname, $default_name_format ) );
					break;
			}
		}

		$playersoptions_subs_in = array ();
		$playersoptions_subs_in [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_GLOBAL_SELECT_PLAYER' ) );
		$i = 0;
		foreach ( ( array ) $new_not_assigned as $player ) {
			switch ($default_name_dropdown_list_order) {
				case 'lastname' :
				case 'firstname' :
					if (array_key_exists ( 'came_in', $player ) && $player->came_in == 1 && $player->in_for > 0) {
						$i ++;
						if ($i == 1) {
							$playersoptions_subs_in[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_ELUSUBST_SELECT_PLAYER_ALREADY_OUT'));
						}
						$playersoptions_subs_in [] = JHtml::_ ( 'select.option', $player->in_for, JoomleagueHelper::formatName ( null, $player->out_firstname, $player->out_nickname, $player->out_lastname, $default_name_format ) );
					} elseif (! array_key_exists ( 'came_in', $player )) {
						$playersoptions_subs_in [] = JHtml::_ ( 'select.option', $player->value, JoomleagueHelper::formatName ( null, $player->firstname, $player->nickname, $player->lastname, $default_name_format ) );
					}
					break;

				case 'position' :
					if (array_key_exists ( 'came_in', $player ) && $player->came_in == 1 && $player->in_for > 0) {
						$i ++;
						if ($i == 1) {
							$playersoptions_subs_in[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_ELUSUBST_SELECT_PLAYER_ALREADY_OUT'));
						}
						$playersoptions_subs_in [] = JHtml::_ ( 'select.option', $player->in_for, '(' . JText::_ ( $player->positionname_out ) . ') - ' . JoomleagueHelper::formatName ( null, $player->out_firstname, $player->out_nickname, $player->out_lastname, $default_name_format ) );
					} elseif (! array_key_exists ( 'came_in', $player )) {
						$playersoptions_subs_in [] = JHtml::_ ( 'select.option', $player->value, '(' . JText::_ ( $player->positionname ) . ') - ' . JoomleagueHelper::formatName ( null, $player->firstname, $player->nickname, $player->lastname, $default_name_format ) );
					}
					break;
			}
		}
		// $lists['all_players']=JHtml::_( 'select.genericlist',$playersoptions,'roster[]',
		// 'id="roster" style="font-size:12px;height:auto;min-width:15em;" class="inputbox" size="4"',
		// 'value','text');

		// generate selection list for each position
		$starters = array ();
		foreach ( $projectpositions as $position_id => $pos ) {
			// get players assigned to this position
			$starters [$position_id] = $model->getRoster ( $tid, $pos->pposid );
		}

		foreach ( $starters as $position_id => $players ) {
			$options = array ();
			foreach ( ( array ) $players as $p ) {
				if ($p->jerseynumber > 0) {
					$jerseynumber = '[' . $p->jerseynumber . '] ';
				} else {
					$jerseynumber = '';
				}
				$options [] = JHtml::_ ( 'select.option', $p->value, $jerseynumber . JoomleagueHelper::formatName ( null, $p->firstname, $p->nickname, $p->lastname, $default_name_format ) );
			}

			$lists ['team_players' . $position_id] = JHtml::_ ( 'select.genericlist', $options, 'position' . $position_id . '[]', 'style="font-size:12px;height:auto;min-width:15em;" size="4" class="inputbox position-starters" multiple="true" ', 'value', 'text' );
		}

		/**
		 * staff positions
		 */
		$staffpositions = & $model->getProjectStaffPositions (); // get staff not already assigned to starter
		                                                       // echo '<pre>'.print_r($staffpositions,true).'</pre>';

		// assigned staff
		$assigned = $model->getMatchStaffs ( $tid );
		$assigned_id = array_keys ( $assigned );
		// not assigned staff
		$not_assigned = $model->getTeamStaffs ( $tid, $assigned_id, $default_name_dropdown_list_order );

		// build select list for not assigned
		$not_assigned_options = array ();
		foreach ( ( array ) $not_assigned as $p ) {

			switch ($default_name_dropdown_list_order) {
				case 'lastname' :
				case 'firstname' :
					$not_assigned_options [] = JHtml::_ ( 'select.option', $p->value, JoomleagueHelper::formatName ( null, $p->firstname, $p->nickname, $p->lastname, $default_name_format ) );
					break;

				case 'position' :
					$not_assigned_options [] = JHtml::_ ( 'select.option', $p->value, '(' . JText::_ ( $p->positionname ) . ') - ' . JoomleagueHelper::formatName ( null, $p->firstname, $p->nickname, $p->lastname, $default_name_format ) );
					break;
			}
		}
		$lists ['team_staffs'] = JHtml::_ ( 'select.genericlist', $not_assigned_options, 'staff[]', 'style="font-size:12px;height:auto;min-width:15em;" size="18" class="inputbox" multiple="true" size="18"', 'value', 'text' );

		// generate selection list for each position
		$options = array ();
		foreach ( $staffpositions as $position_id => $pos ) {
			// get players assigned to this position
			$options = array ();
			foreach ( $assigned as $staff ) {
				if ($staff->project_position_id == $pos->pposid) {
					$options [] = JHtml::_ ( 'select.option', $staff->team_staff_id, JoomleagueHelper::formatName ( null, $staff->firstname, $staff->nickname, $staff->lastname, $default_name_format ) );
				}
			}
			$lists ['team_staffs' . $position_id] = JHtml::_ ( 'select.genericlist', $options, 'staffposition' . $position_id . '[]', 'style="font-size:12px;height:auto;min-width:15em;" size="4" class="inputbox position-staff" multiple="true" ', 'value', 'text' );
		}

		$this->assignRef ( 'match', $teams );
		$this->assignRef ( 'tid', $tid );
		$this->assignRef ( 'teamname', $teamname );
		$this->assignRef ( 'positions', $projectpositions );
		$this->assignRef ( 'staffpositions', $staffpositions );
		$this->assignRef ( 'substitutions', $substitutions [$tid] );
		$this->assignRef ( 'playersoptions_subs_out',  $playersoptions_subs_out );
		$this->assignRef ( 'playersoptions_subs_in', $playersoptions_subs_in );
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'default_name_format', $default_name_format );
		$this->assignRef ( 'default_name_dropdown_list_order', $default_name_dropdown_list_order );

		parent::display ( $tpl );
	}
	function _displayForm($tpl) {
		$mainframe = JFactory::getApplication ();
		$option = JRequest::getCmd ( 'option' );
		$user = JFactory::getUser ();
		$model = $this->getModel ();
		$lists = array ();

		// get the match
		$match = $this->get ( 'data' );
		$isNew = ($match->id < 1);

		if ((! $match->projectteam1_id) and (! $match->projectteam2_id)) {
			JError::raiseWarning ( 440, '<br />' . JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NO_TEAMS' ) . '<br /><br />' );
			return false;
		}

		// fail if checked out not by 'me'
		if ($model->isCheckedOut ( $user->get ( 'id' ) )) {
			$msg = JText::sprintf ( 'DESCBEINGEDITTED', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_THE_MATCH' ), $match->name );
			$mainframe->redirect ( 'index.php?option=com_joomleague', $msg );
		}

		// Edit or Create?
		if (! $isNew) {
			$model->checkout ( $user->get ( 'id' ) );
		}

		// build the html select booleanlist for published
		$lists ['published'] = JHtml::_ ( 'select.booleanlist', 'published', 'class="inputbox"', $match->published );

		// get the home team standard playground
		$tblProjectHomeTeam = JTable::getInstance ( 'Projectteam', 'table' );
		$tblProjectHomeTeam->load ( $match->projectteam1_id );
		$standard_playground_id = (! empty ( $tblProjectHomeTeam->standard_playground ) && $tblProjectHomeTeam->standard_playground > 0) ? $tblProjectHomeTeam->standard_playground : null;
		$playground_id = (! empty ( $match->playground_id ) && ($match->playground_id > 0)) ? $match->playground_id : $standard_playground_id;

		// build the html select booleanlist for count match result
		$lists ['count_result'] = JHtml::_ ( 'select.booleanlist', 'count_result', 'class="inputbox"', $match->count_result );

		// build the html select booleanlist which team got the won
		$myoptions = array ();
		$myoptions [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCHES_NO_TEAM' ) );
		$myoptions [] = JHtml::_ ( 'select.option', '1', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCHES_HOME_TEAM' ) );
		$myoptions [] = JHtml::_ ( 'select.option', '2', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCHES_AWAY_TEAM' ) );
		$myoptions [] = JHtml::_ ( 'select.option', '3', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCHES_LOSS_BOTH_TEAMS' ) );
		$myoptions [] = JHtml::_ ( 'select.option', '4', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCHES_WON_BOTH_TEAMS' ) );
		$lists ['team_won'] = JHtml::_ ( 'select.genericlist', $myoptions, 'team_won', 'class="inputbox" size="1"', 'value', 'text', $match->team_won );

		$projectws = $this->get ( 'Data', 'project' );
		$model = $this->getModel ( 'project' );

		$overall_config = $model->getTemplateConfig ( 'overall' );
		$table_config = $model->getTemplateConfig ( 'ranking' );

		$extended = $this->getExtended ( $match->extended, 'match' );

		// match relation tab
		$mdlMatch = JModelLegacy::getInstance ( 'match', 'JoomleagueModel' );
		$oldmatches [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_OLD_MATCH' ) );
		$res = array ();
		$new_match_id = ($match->new_match_id) ? $match->new_match_id : 0;
		if ($res = & $mdlMatch->getMatchRelationsOptions ( $mainframe->getUserState ( $option . 'project', 0 ), $match->id . "," . $new_match_id )) {
			foreach ( $res as $m ) {
				$m->text = '(' . JoomleagueHelper::getMatchStartTimestamp ( $m ) . ') - ' . $m->t1_name . ' - ' . $m->t2_name;
			}
			$oldmatches = array_merge ( $oldmatches, $res );
		}
		$lists ['old_match'] = JHtml::_ ( 'select.genericlist', $oldmatches, 'old_match_id', 'class="inputbox" size="1"', 'value', 'text', $match->old_match_id );

		$newmatches [] = JHtml::_ ( 'select.option', '0', JText::_ ( 'COM_JOOMLEAGUE_ADMIN_MATCH_NEW_MATCH' ) );
		$res = array ();
		$old_match_id = ($match->old_match_id) ? $match->old_match_id : 0;
		if ($res = & $mdlMatch->getMatchRelationsOptions ( $mainframe->getUserState ( $option . 'project', 0 ), $match->id . "," . $old_match_id )) {
			foreach ( $res as $m ) {
				$m->text = '(' . JoomleagueHelper::getMatchStartTimestamp ( $m ) . ') - ' . $m->t1_name . ' - ' . $m->t2_name;
			}
			$newmatches = array_merge ( $newmatches, $res );
		}
		$lists ['new_match'] = JHtml::_ ( 'select.genericlist', $newmatches, 'new_match_id', 'class="inputbox" size="1"', 'value', 'text', $match->new_match_id );

		$this->assignRef ( 'overall_config', $overall_config );
		$this->assignRef ( 'table_config', $table_config );
		$this->assignRef ( 'projectws', $projectws );
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'match', $match );
		$this->assignRef ( 'extended', $extended );
		$form = $this->get ( 'form' );
		$form->setValue ( 'playground_id', null, $playground_id );
		$this->assignRef ( 'form', $form );

		parent::display ( $tpl );
	}
	protected function _handlePreFillRoster(&$teams, &$model, &$params, &$tid, &$teamname) {
		if ($params->get ( 'use_prefilled_match_roster' ) > 0) {
			$bDeleteCurrrentRoster = $params->get ( 'on_prefill_delete_current_match_roster', 0 );
			$prefillType = JRequest::getInt ( 'prefill', 0 );
			if ($prefillType == 0) {
				$prefillType = $params->get ( 'use_prefilled_match_roster' );
			}
			$projectteam_id = ($tid == $teams->projectteam1_id) ? $teams->projectteam1_id : $teams->projectteam2_id;

			if ($prefillType == 2) {
				$preFillSuccess = false;
				if (! $model->prefillMatchPlayersWithProjectteamPlayers ( $projectteam_id, $bDeleteCurrrentRoster )) {
					if ($model->getError () != '') {
						JError::raiseWarning ( 440, '<br />' . $model->getError () . '<br /><br />' );
						return false;
					} else {
						$preFillSuccess = false;
					}
				} else {
					$preFillSuccess = true;
				}
			} elseif ($prefillType == 1) {
				if (! $model->prefillMatchPlayersWithLastMatch ( $projectteam_id, $bDeleteCurrrentRoster )) {
					if ($model->getError () != '') {
						JError::raiseWarning ( 440, '<br />' . $model->getError () . '<br /><br />' );
						return false;
					} else {
						$preFillSuccess = false;
					}
				} else {
					$preFillSuccess = true;
				}
			}
		}
		$this->assignRef ( 'preFillSuccess', $preFillSuccess );
	}
}
?>