<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewMatrix extends JLGView
{
	public function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document= JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$project = $model->getProject();
		
		$this->model = $model;
		$this->project = $project;
		$this->overallconfig = $model->getOverallConfig();

		$this->config = $config;

		$this->divisionid = $model->getDivisionID();
		$this->roundid = $model->getRoundID();
		$this->division = $model->getDivision();
		$this->round = $model->getRound();
		$this->teams = $model->getTeamsIndexedByPtid($model->getDivisionID());
		$this->results = $model->getMatrixResults($model->projectid);
		
		if ($project->project_type == 'DIVISIONS_LEAGUE' && !$this->divisionid )
		{
			$divisions = $model->getDivisions();
			$this->divisions = $divisions;
		}
		
		if(!is_null($project)) {
			$this->favteams = $model->getFavTeams();
		}
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_MATRIX_PAGE_TITLE'));
		if (!empty($this->round))
		{
			$titleInfo->roundName = $this->round->name;
		}
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		if (!empty( $this->division ) && $this->division->id != 0)
		{
			$titleInfo->divisionName = $this->division->name;
		}
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
