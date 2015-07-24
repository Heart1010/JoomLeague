<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Quickadd Controller
 */
class JoomleagueControllerQuickadd extends JoomleagueController
{

	public function __construct()
	{		
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'searchplayer',	'searchPlayer' );
		$this->registerTask( 'searchstaff',		'searchStaff' );
		$this->registerTask( 'searchreferee',	'searchReferee' );
		$this->registerTask( 'searchteam',		'searchTeam' );
		$this->registerTask( 'addplayer', 		'addPlayer' );
		$this->registerTask( 'addstaff', 		'addstaff' );
		$this->registerTask( 'addreferee', 		'addReferee' );
		$this->registerTask( 'addteam', 		'addTeam' );
	}

	public function searchPlayer()
	{
		$model 			= JLGModel::getInstance('Quickadd', 'JoomleagueModel');
		$query 			= JRequest::getVar("query", "", "", "string");
		$projectteam_id = JRequest::getInt("projectteam_id");
		$results 		= $model->getNotAssignedPlayers($query, $projectteam_id);
		$response = array(
			"totalCount" => count($results),
			"rows" => array()
		);

		foreach ($results as $row) {
			$name = JoomleagueHelper::formatName(null,$row->firstname, $row->nickname, $row->lastname, 0) . " (" . $row->id . ")";
			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $name
			);
		}
		echo json_encode($response);
		exit;
	}

	public function searchStaff()
	{
		$model 			= JLGModel::getInstance('Quickadd', 'JoomleagueModel');
		$query 			= JRequest::getVar("query", "", "", "string");
		$projectteam_id = JRequest::getInt("projectteam_id");
		$results 		= $model->getNotAssignedStaff($query, $projectteam_id);
		$response = array(
			"totalCount" => count($results),
			"rows" => array()
		);

		foreach ($results as $row) {
			$name = JoomleagueHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, 0) . " (" . $row->id . ")";
			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $name
			);
		}

		echo json_encode($response);
		exit;
	}

	public function searchReferee()
	{
		$option 	= JRequest::getCmd('option');
		$app		= JFactory::getApplication();
		$model 		= JLGModel::getInstance('Quickadd', 'JoomleagueModel');
		$query 		= JRequest::getVar("query", "", "", "string");
		$projectid 	= $app->getUserState($option."project");
		$results 	= $model->getNotAssignedReferees($query, $projectid);
		$response = array(
			"totalCount" => count($results),
			"rows" => array()
		);

		foreach ($results as $row) {
			$name = JoomleagueHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, 0) . " (" . $row->id . ")";
			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $name
			);
		}

		echo json_encode($response);
		exit;
	}

	public function searchTeam()
	{
		// Use the correct json mime-type
		header('Content-Type: application/json');
		
		$option 	= JRequest::getCmd('option');
		$app		= JFactory::getApplication();
		$model 		= JLGModel::getInstance('Quickadd', 'JoomleagueModel');
		$query 		= JRequest::getVar("query", "", "", "string");
		$projectid 	= $app->getUserState($option."project");
		$results 	= $model->getNotAssignedTeams($query, $projectid);
		
		
		$response = array(
			"totalCount" => count($results),
			"rows" => array()
		);

		$names = array();
		foreach ($results as $row) {
			$names[] = $row->name;
			$name = $row->name;
			$name .= " (" . $row->info . ")";
			$name .= " (" . $row->id . ")";

			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $name
			);
		}
		
		
		$suggestions = $names;
		
		
		// Send the response.
		echo '{ "suggestions": ' . json_encode($suggestions) . ' }';
		JFactory::getApplication()->close();
	}

	public function addPlayer()
	{
		$personid = JRequest::getInt("cpersonid", 0);
		$name = JRequest::getVar("quickadd", '', 'request', 'string');
		$projectteam_id = JRequest::getInt("projectteam_id");

		$model = $this->getModel('quickadd');
		$res = $model->addPlayer($projectteam_id, $personid, $name);
		
		if ($res) {
			$msgtype = 'message';
			$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_PERSON_ASSIGNED');
		} else {
			$msgtype = 'error';
			$msg = $model->getError();
		}

		$this->setRedirect("index.php?option=com_joomleague&view=teamplayers&task=teamplayer.display&project_team_id=".$projectteam_id, $msg);
	}

	public function addStaff()
	{
		$db = JFactory::getDbo();
		$personid = JRequest::getInt("cpersonid", 0);
		$name = JRequest::getVar("quickadd", '', 'request', 'string');
		$projectteam_id = JRequest::getInt("projectteam_id", 0);

		// add the new individual as their name was sent through.
		if (!$personid)
		{
			$model = JLGModel::getInstance('Person', 'JoomleagueModel');
			$name = explode(" ", $name);
			$firstname = ucfirst(array_shift($name));
			$lastname = ucfirst(implode(" ", $name));
			$data = array(
				"firstname" => $firstname,
				"lastname" => $lastname,
			);
			$personid = $model->store($data);
		}

		if (!$personid) {
			$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_PERSON_ASSIGNED');
			$this->setRedirect("index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display&project_team_id=".$projectteam_id, $msg, 'error');
		}

		// check if indivual belongs to project
		$query = ' SELECT person_id FROM #__joomleague_team_staff '
		. ' WHERE projectteam_id = '. $db->Quote($projectteam_id)
		. '   AND person_id = '. $db->Quote($personid)
		;
		$db->setQuery($query);
		$res = $db->loadResult();
		if (!$res)
		{
			$tblTeamstaff = JTable::getInstance('Teamstaff','Table');
			$tblTeamstaff->person_id = $personid;
			$tblTeamstaff->projectteam_id = $projectteam_id;
			
			$tblProjectTeam = JTable::getInstance( 'Projectteam', 'Table' );
			$tblProjectTeam->load($projectteam_id);
			
			if (!$tblTeamstaff->check())
			{
				$this->setError($tblTeamstaff->getError());
			}
			//Get data from person
			$query = "	SELECT picture, position_id
						FROM #__joomleague_person AS pl
						WHERE pl.id=". $db->Quote($personid)."
						AND pl.published = 1";
			$db->setQuery( $query );
			$person = $db->loadObject();
			if ( $person )
			{
				$query = "SELECT id FROM #__joomleague_project_position "; 
				$query.= " WHERE position_id = " . $db->Quote($person->position_id);
				$query.= " AND project_id = " . $db->Quote($tblProjectTeam->project_id);
				$db->setQuery($query);
				if ($resPrjPosition = $db->loadObject())
				{
					$tblTeamstaff->project_position_id = $resPrjPosition->id;	
				}
				
				$tblTeamstaff->picture			= $person->picture;
				$tblTeamstaff->projectteam_id	= $projectteam_id;
				
			}
			$query = "	SELECT max(ordering) count
						FROM #__joomleague_team_staff";
			$db->setQuery( $query );
			$ts = $db->loadObject();
			$tblTeamstaff->ordering = (int) $ts->count + 1;
			if (!$tblTeamstaff->store())
			{
				$this->setError($tblTeamstaff->getError());
			}
		}
		$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_PERSON_ASSIGNED');
		$this->setRedirect("index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display&project_team_id=".$projectteam_id, $msg);
	}

	public function addReferee()
	{
		$option = JRequest::getCmd('option');
		$app	= JFactory::getApplication();

		$db = JFactory::getDbo();
		$personid = JRequest::getInt("cpersonid", 0);
		$name = JRequest::getVar("quickadd", '', 'request', 'string');
		$project_id = $app->getUserState($option."project");
		
		// add the new individual as their name was sent through.
		if (!$personid)
		{
			$model = JLGModel::getInstance('Person', 'JoomleagueModel');
			$name = explode(" ", $name);
			$firstname = ucfirst(array_shift($name));
			$lastname = ucfirst(implode(" ", $name));
			$data = array(
				"firstname" => $firstname,
				"lastname" => $lastname,
			);
			$personid = $model->store($data);
		}

		if (!$personid) {
			$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_PERSON_ASSIGNED');
			$this->setRedirect("index.php?option=com_joomleague&view=projectreferees&task=projectreferee.display&projectid=".$project_id, $msg, 'error');
		}

		// check if indivual belongs to project
		$query = ' SELECT person_id FROM #__joomleague_project_referee '
		. ' WHERE project_id = '. $db->Quote($project_id)
		. '   AND person_id = '. $db->Quote($personid)
		;
		$db->setQuery($query);
		$res = $db->loadResult();
		if (!$res)
		{
				$tblProjectReferee = JTable::getInstance('Projectreferee','Table');
				$tblProjectReferee->person_id=$personid;
				$tblProjectReferee->projectteam_id=$projectteam_id;
				
				if (!$tblProjectReferee->check())
				{
					$this->setError($tblProjectReferee->getError());
				}
				//Get data from person
				$query = "	SELECT picture, position_id
							FROM #__joomleague_person AS pl
							WHERE pl.id=". $db->Quote($personid)."
							AND pl.published = 1";
				$db->setQuery( $query );
				$person = $db->loadObject();
				if ( $person )
				{
					$query = "SELECT id FROM #__joomleague_project_position "; 
					$query.= " WHERE position_id = " . $db->Quote($person->position_id);
					$query.= " AND project_id = " . $db->Quote($project_id);
					$db->setQuery($query);
					if ($resPrjPosition = $db->loadObject())
					{
						$tblProjectReferee->project_position_id = $resPrjPosition->id;	
					}
					
					$tblProjectReferee->picture			= $person->picture;
					$tblProjectReferee->project_id		= $project_id;
					
				}
				$query = "	SELECT max(ordering) count
							FROM #__joomleague_project_referee";
				$db->setQuery( $query );
				$pref = $db->loadObject();
				$tblProjectReferee->ordering = (int) $pref->count + 1;
					
				if (!$tblProjectReferee->store())
				{
					$this->setError($tblProjectReferee->getError());
				}
		}
		$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_PERSON_ASSIGNED');
		$this->setRedirect("index.php?option=com_joomleague&view=projectreferees&task=projectreferee.display&projectid=".$project_id, $msg);
	}

	public function addTeam()
	{
		// we are coming from projectteams
		$option = JRequest::getCmd('option');
		$app	= JFactory::getApplication();
		$jinput = $app->input;
		$db 	= JFactory::getDbo();
		
		// catch variables	
		$cteamid = $jinput->getInt("cteamid", 0); // @todo check!, is a cteamid ever passed?
		$name	= $jinput->getString("quickadd", ''); // @todo check!
		$prId	= $jinput->getInt("project_id",0);
		$searchText = $jinput->getString("p","");
			
		// get current projectid
		// @todo check!
		// we can catch the id also from the post
		$project_id = $app->getUserState($option."project");
		
		
		// Return if $searchText is empty
		// As input we do want to have a team-name
		if (empty($searchText)) {
			$app->enqueueMessage(Jtext::_('Fill in a teamname'),'warning');
			$this->setRedirect("index.php?option=com_joomleague&view=projectteams&task=projectteam.display&projectid=".$project_id);
			return;
		}
		
		// Retrieve team-id
		// @todo add check for unique name
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_team');
		$query->where('name = '.$db->quote($searchText));
		$db->setQuery($query);
		$teamid = $db->loadResult();
		
		if (empty($teamid) || $teamid == null) {
			$app->enqueueMessage(Jtext::_('Team does not exist.<br>No team was added'),'warning');
			$this->setRedirect("index.php?option=com_joomleague&view=projectteams&task=projectteam.display&projectid=".$project_id);
			return;
		}
			
		// At this point we do have a project+teamid
		// -> check if team already belongs to project
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_project_team');
		$query->where('project_id = '.$project_id);
		$query->where('team_id = '.$teamid);
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if ($result) {
			# the team was already added so don't add it twice
			$app->enqueueMessage(Jtext::_('Team already exists<br>No team was added'),'warning');
			$this->setRedirect("index.php?option=com_joomleague&view=projectteams&task=projectteam.display&projectid=".$project_id);
			return;
		}
		
		// Add team to projectteam 
		$new = JTable::getInstance('Projectteam','Table');
		$new->team_id		= $teamid;
		$new->project_id	= $project_id;

		// Set ordering to the last item if not set
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('MAX(ordering)');
		$query->from('#__joomleague_project_team');
		$query->where('project_id = '.$project_id);
		$db->setQuery($query);
		$max = $db->loadResult();
		$new->ordering 		= $max+1;

		if (!$new->check())
		{
			$this->setError($new->getError());
		}
		
		// Get data from player
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('picture');
		$query->from('#__joomleague_team AS t');
		$query->where('t.id = '.$teamid);
		$db->setQuery($query);
		$team = $db->loadObject();
		
		if ($team)
		{
			$new->picture	= $team->picture;
		}
		if (!$new->store())
		{
	  		$this->setError($new->getError());
		}
		
		// @todo fix!
		$errors = $this->getErrors();
		if ($errors) {
			/* $app->enqueueMessage($errors,'error'); */
		}
		
		
		$msg = Jtext::_('COM_JOOMLEAGUE_ADMIN_QUICKADD_CTRL_TEAM_ASSIGNED');
		$this->setRedirect("index.php?option=com_joomleague&view=projectteams&task=projectteam.display&projectid=".$project_id, $msg);
	}
}
