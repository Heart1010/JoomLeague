<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewEventsRanking extends JLGView
{
	public function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		// read the config-data from template file
		$model = $this->getModel();
		$config=$model->getTemplateConfig($this->getName());

		$this->project = $model->getProject();
		$this->division = $model->getDivision();
		$this->matchid = $model->matchid;
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;
		$this->teamid = $model->getTeamId();
		$this->teams = $model->getTeamsIndexedById();
		$this->favteams = $model->getFavTeams();
		$this->eventtypes = $model->getEventTypes();
		$this->limit = $model->getLimit();
		$this->limitstart = $model->getLimitStart();
		$this->pagination = $this->get('Pagination');
		$this->eventranking = $model->getEventRankings($this->limit);
		// @todo: check!
		$this->multiple_events = count($this->eventtypes) > 1;

		$prefix = JText::_('COM_JOOMLEAGUE_EVENTSRANKING_PAGE_TITLE');
		if ( $this->multiple_events )
		{
			$prefix .= " - " . JText::_( 'COM_JOOMLEAGUE_EVENTSRANKING_TITLE' );
		}
		else
		{
			// Next query will result in an array with exactly 1 statistic id
			$evid = array_keys($this->eventtypes);

			
			// @todo check! // 23-07-2015
			// Added if statement as it can happen that no eventtype was selected
			if ($evid) {
				// Selected one valid eventtype, so show its name
				$prefix .= " - " . JText::_($this->eventtypes[$evid[0]]->name);
			}
		}

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo($prefix);
		if (!empty($this->teamid) && array_key_exists($this->teamid, $this->teams))
		{
			$titleInfo->team1Name = $this->teams[$this->teamid]->name;
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

		parent::display($tpl);
	}
}
