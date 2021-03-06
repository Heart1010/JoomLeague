<?php defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class JoomleagueViewPlayer extends JLGView
{

	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();
		$model = $this->getModel();
		$config=$model->getTemplateConfig($this->getName());

		$person=$model->getPerson();
		$nickname = isset($person->nickname) ? $person->nickname : "";
		if(!empty($nickname)){$nickname="'".$nickname."'";}
		$this->isContactDataVisible = $model->isContactDataVisible($config['show_contact_team_member_only']);
		$project = $model->getProject();
		$this->project = $project;
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;
		$this->person = $person;
		$this->nickname = $nickname;
		/*
		$this->teamPlayers = $model->getTeamPlayers();

		// Select the teamplayer that is currently published (in case the player played in multiple teams in the project)
		$teamPlayer = null;
		if (count($this->teamPlayers))
		{
			$currentProjectTeamId=0;
			foreach ($this->teamPlayers as $teamPlayer)
			{
				if ($teamPlayer->published == 1)
				{
					$currentProjectTeamId=$teamPlayer->projectteam_id;
					break;
				}
			}
			if ($currentProjectTeamId)
			{
				$teamPlayer = $this->teamPlayers[$currentProjectTeamId];
			}
		}
		*/
		$sportstype = $config['show_plcareer_sportstype'] ? $model->getSportsType() : 0;
		$current_round = $project->current_round;
		$personid = $model->personid;
		$teamPlayer = $model->getTeamPlayerByRound($current_round, $personid);
		$this->teamPlayer = $teamPlayer[0];
		$this->historyPlayer = $model->getPlayerHistory($sportstype, 'ASC');
		$this->historyPlayerStaff = $model->getPlayerHistoryStaff($sportstype, 'ASC');
		$this->AllEvents = $model->getAllEvents($sportstype);
		$this->showediticon = $model->getAllowed($config['edit_own_player']);
		$this->stats = $model->getProjectStats();

		// Get events and stats for current project
		if ($config['show_gameshistory'])
		{
			$this->games = $model->getGames();
			$this->teams = $model->getTeamsIndexedByPtid();
			$this->gamesevents = $model->getGamesEvents();
			$this->gamesstats = $model->getPlayerStatsByGame();
		}

		// Get events and stats for all projects where player played in (possibly restricted to sports type of current project)
		if ($config['show_career_stats'])
		{
			$this->stats = $model->getStats($current_round, $personid);
			$this->projectstats = $model->getPlayerStatsByProject($sportstype,$current_round, $personid);
		}

		$extended = $this->getExtended($person->extended, 'person');
		$this->extended = $extended;
		
		$name = !empty($person) ? JoomleagueHelper::formatName(null, $person->firstname, $person->nickname,  $person->lastname,  $this->config["name_format"]) : "";
		$this->playername = $name;
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_PLAYER_PAGE_TITLE'));
		$titleInfo->personName = $name;
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		$division = $model->getDivision(JRequest::getInt('division',0));
		if (!empty( $division ) && $division->id != 0)
		{
			$titleInfo->divisionName = $division->name;
		}
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}
}
