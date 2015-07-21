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

class JoomleagueViewClubInfo extends JLGView
{

	public function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document		= JFactory::getDocument();
		$model			= $this->getModel();
		$club			= $model->getClub() ;
		$config			= $model->getTemplateConfig( $this->getName() );	
		$project 		= $model->getProject();
		$overallconfig	= $model->getOverallConfig();
		$teams			= $model->getTeamsByClubId();
		$stadiums	 	= $model->getStadiums();
		$playgrounds	= $model->getPlaygrounds();
		$isEditor		= $model->hasEditPermission('club.edit');
		$address_string = $model->getAddressString();
		$map_config		= $model->getMapConfig();
		
		$this->project = $project;
		$this->overallconfig = $overallconfig;
		$this->config = $config;

		/* $this->showclubconfig = $showclubconfig; */
		$this->club = $club;

		$extended = $this->getExtended($club->extended, 'club');
		$this->extended = $extended;

		$this->teams = $teams;
		$this->stadiums = $stadiums;
		$this->playgrounds = $playgrounds;
		$this->showediticon = $isEditor;

		$this->address_string = $address_string;
		$this->mapconfig = $map_config; // Loads the project-template -settings for the GoogleMap

		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_CLUBINFO_PAGE_TITLE'));
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
		if (!empty( $this->division ) && $this->division->id != 0)
		{
			$titleInfo->divisionName = $this->division->name;
		}
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
