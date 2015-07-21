<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewTeams extends JLGView
{
	public function display( $tpl = null )
	{
		// Get a reference of the page instance in joomla
		$document= JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());

		$this->project = $model->getProject();
		$this->division = $model->getDivision();
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;

		$this->teams = $model->getTeams();

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_TEAMS_TITLE'));
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
