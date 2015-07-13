<?php defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'helpers'.DS.'pagination.php');

jimport('joomla.application.component.view');

class JoomleagueViewRoster extends JLGView
{

	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();
		$model = $this->getModel();
		$config=$model->getTemplateConfig($this->getName());
		
		$this->assignRef('project',$model->getProject());
		$this->assignRef('overallconfig',$model->getOverallConfig());
		//$this->assignRef('staffconfig',$model->getTemplateConfig('teamstaff'));
		$this->assignRef('config',$config);

		$playerlayout =  JRequest::getVar( 'playerlayout', '' );
		$stafflayout =  JRequest::getVar( 'stafflayout', '' );
		
		if(!empty($playerlayout) && $playerlayout != $this->config['show_players_layout']) {
			$this->config['show_players_layout'] = $playerlayout;
		}
		if(!empty($stafflayout) && $stafflayout != $this->config['show_staff_layout']) {
			$this->config['show_staff_layout'] = $stafflayout;
		}
		
		$this->assignRef('projectteam',$model->getProjectTeam());
		
		if ($this->projectteam)
		{
			$this->assignRef('showediticon',$model->hasEditPermission('teamplayer.select'));
			$team = $model->getTeam();
			$this->assignRef('team', $team);
			$players = $model->getTeamPlayers();
			$this->assignRef('rows', $players);
			// events
			if ($this->config['show_events_stats'])
			{
				$this->assignRef('positioneventtypes',$model->getPositionEventTypes());
				$this->assignRef('playereventstats',$model->getPlayerEventStats());
			}
			//stats
			if ($this->config['show_stats'])
			{
				$this->assignRef('stats',$model->getProjectStats());
				$this->assignRef('playerstats',$model->getRosterStats());
			}
			$this->assignRef('stafflist',$model->getStaffList());
		}
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_ROSTER_PAGE_TITLE'));
		if (!empty($this->team))
		{
			if ( $this->config['show_team_shortform'] == 1 && !empty($this->team->short_name))
			{
				$titleInfo->team1Name = $this->team->name ." [". $this->team->short_name . "]";
			}
			else
			{
				$titleInfo->team1Name = $this->team->name;
			}
		}
		else
		{
			$titleInfo->team1Name = "Project team does not exist";
		}
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
		$this->assignRef('pagetitle', JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]));
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}

}
?>