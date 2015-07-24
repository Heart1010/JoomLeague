<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewTeamInfo extends JLGView
{
	public function display( $tpl = null )
	{
		// Get a reference of the page instance in joomla
		$document	= JFactory::getDocument();
		$model		= $this->getModel();
		$config		= $model->getTemplateConfig( $this->getName() );
		
		$project	= $model->getProject();
		$this->project = $project;
		$isEditor = $model->hasEditPermission('projectteam.edit');

		if ( isset($this->project->id) )
		{
			$overallconfig = $model->getOverallConfig();
			$this->overallconfig = $overallconfig;
			$this->config = $config;
			$team = $model->getTeamByProject();
			$this->team = $team;

			$club = $model->getClub() ;
			$this->club = $club;
			$seasons = $model->getSeasons( $config );
			$this->seasons = $seasons;
			$this->showediticon = $isEditor;

			$trainingData = $model->getTrainigData($this->project->id);
			$this->trainingData = $trainingData;

			$daysOfWeek=array(
				1 => JText::_('COM_JOOMLEAGUE_GLOBAL_MONDAY'),
				2 => JText::_('COM_JOOMLEAGUE_GLOBAL_TUESDAY'),
				3 => JText::_('COM_JOOMLEAGUE_GLOBAL_WEDNESDAY'),
				4 => JText::_('COM_JOOMLEAGUE_GLOBAL_THURSDAY'),
				5 => JText::_('COM_JOOMLEAGUE_GLOBAL_FRIDAY'),
				6 => JText::_('COM_JOOMLEAGUE_GLOBAL_SATURDAY'),
				7 => JText::_('COM_JOOMLEAGUE_GLOBAL_SUNDAY')
			);
			$this->daysOfWeek = $daysOfWeek;
			
			
			// @todo check!
			// moved to this if statement as $team is not defined if there is no project available
			$extended = $this->getExtended($team->teamextended, 'team');
			$this->extended = $extended;
		}

		
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_TEAMINFO_PAGE_TITLE'));
		
		if (!empty($this->team))
		{
			$titleInfo->team1Name = $this->team->tname;
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
		
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
