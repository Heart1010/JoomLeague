<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewStaff extends JLGView
{

	public function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$person = $model->getPerson();
		$project = $model->getProject();

		$current_round = $project->current_round;
		$personid = $model->personid;
		
		$this->project = $model->getProject();
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;
		$this->person = $person;
		
		$staff = $model->getTeamStaffByRound($current_round, $personid);
		
		$this->teamStaff = $staff;
		$this->history = $model->getStaffHistory('ASC');
		
		$this->stats = $model->getStats($current_round, $personid);
		$this->staffstats = $model->getStaffStats($current_round, $personid);
		$this->historystats = $model->getHistoryStaffStats($current_round, $personid);
		$this->showediticon = $model->getAllowed($config['edit_own_player']);
		$extended = $this->getExtended($person->extended, 'staff');
		$this->extended = $extended;
		
		if (isset($person))
		{
			$name = JoomleagueHelper::formatName(null, $person->firstname, $person->nickname, $person->lastname, $this->config["name_format"]);
		}
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_STAFF_PAGE_TITLE'));
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
