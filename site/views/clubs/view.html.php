<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewClubs extends JLGView
{
	function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document= JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$project = $model->getProject();
		$division = $model->getDivision() ;
		$overallconfig = $model->getOverallConfig();
		$clubs = $model->getClubs();
		$this->project = $project;
		$this->division = $division;
		$this->overallconfig = $overallconfig;
		$this->config = $config;

		$this->clubs = $clubs;

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_CLUBS_PAGE_TITLE'));
		if (!empty( $this->club ) )
		{
			$titleInfo->clubName = $this->club->name;
		}
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		if (!empty($this->division))
		{
			$titleInfo->divisionName = $this->division->name;
		}
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
