<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/pagination.php';

class JoomleagueViewReferees extends JLGView
{

	public function display($tpl = null)
	{
		// Get a refrence of the page instance in joomla
		$document	= JFactory::getDocument();

		$model	= $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		
		if (!$config)
		{
			$config	= $model->getTemplateConfig('players');
		}

		$this->project = $model->getProject();
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;

		$this->rows = $model->getReferees();
//		$this->positioneventtypes = $model->getPositionEventTypes( ) );

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_REFEREES_PAGE_TITLE'));
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
