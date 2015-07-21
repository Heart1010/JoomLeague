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
require_once JLG_PATH_SITE.'/models/ranking.php';
require_once JLG_PATH_SITE.'/models/results.php';
require_once JLG_PATH_SITE.'/views/results/view.html.php';

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.html.pane');

class JoomleagueViewResultsranking extends JoomleagueViewResults {

	public function display($tpl = null)
	{
		JHtml::_('behavior.framework');
		$mainframe = JFactory::getApplication();
		$params = $mainframe->getParams();
		// get a reference of the page instance in joomla
		$document = JFactory :: getDocument();
		$uri = JFactory :: getURI();
		// add the css files
		$version = urlencode(JoomleagueHelper::getVersion());
		$css		= 'components/com_joomleague/assets/css/tabs.css?v='.$version;
		$document->addStyleSheet($css);
		// add some javascript
		$version = urlencode(JoomleagueHelper::getVersion());
		$document->addScript( JUri::base(true).'/components/com_joomleague/assets/js/results.js?v='.$version);
		// add the ranking model
		$rankingmodel = new JoomleagueModelRanking();
		$project = $rankingmodel->getProject();
		// add the ranking config file
		$rankingconfig = $rankingmodel->getTemplateConfig('ranking');
		$rankingmodel->computeRanking();
		// add the results model
		$resultsmodel	= new JoomleagueModelResults();
		// add the results config file

		$mdlRound = JModelLegacy::getInstance("Round", "JoomleagueModel");
		$roundcode = $mdlRound->getRoundcode($rankingmodel->round);
		$rounds = JoomleagueHelper::getRoundsOptions($project->id, 'ASC', true);

		$resultsconfig = $resultsmodel->getTemplateConfig('results');
		if (!isset($resultsconfig['switch_home_guest'])){$resultsconfig['switch_home_guest']=0;}
		if (!isset($resultsconfig['show_dnp_teams_icons'])){$resultsconfig['show_dnp_teams_icons']=0;}
		if (!isset($resultsconfig['show_results_ranking'])){$resultsconfig['show_results_ranking']=0;}

		// merge the 2 config files
		$config = array_merge($rankingconfig, $resultsconfig);

		$this->model = $rankingmodel;
		$this->project = $resultsmodel->getProject();
		$this->overallconfig = $resultsmodel->getOverallConfig();
		$this->config = array_merge($this->overallconfig, $config);
		$this->tableconfig = $rankingconfig;
		$this->params = $params;
		$this->showediticon = $resultsmodel->getShowEditIcon();
		$this->division = $resultsmodel->getDivision();
		$this->divisions = $rankingmodel->getDivisions();
		$this->divLevel = $rankingmodel->divLevel;
		$this->matches = $resultsmodel->getMatches();
		$this->round = $resultsmodel->roundid;
		$this->roundid = $resultsmodel->roundid;
		$this->roundcode = $roundcode;

		$rounds = $resultsmodel->getRoundOptions();
		$options = $this->getRoundSelectNavigation($rounds);

		$this->matchdaysoptions = $options;
		$this->currenturl = JoomleagueHelperRoute::getResultsRankingRoute($resultsmodel->getProject()->slug, $this->round);
		$this->rounds = $resultsmodel->getRounds();
		$this->favteams = $resultsmodel->getFavTeams($this->project);
		$this->projectevents = $resultsmodel->getProjectEvents();
		$this->model = $resultsmodel;
		$this->isAllowed = $resultsmodel->isAllowed();

		$this->type = $rankingmodel->type;
		$this->from = $rankingmodel->from;
		$this->to = $rankingmodel->to;

		$this->currentRanking = $rankingmodel->currentRanking;
		$this->previousRanking = $rankingmodel->previousRanking;
		$this->homeRanking = $rankingmodel->homeRank;
		$this->awayRanking = $rankingmodel->awayRank;
		$this->current_round = $rankingmodel->current_round;
		$this->teams = $rankingmodel->getTeamsIndexedByPtid($resultsmodel->getDivisionID());
		$this->previousgames = $rankingmodel->getPreviousGames();

		$this->action = $uri->toString();
		//rankingcolors
		if (!isset ($this->config['colors'])) {
			$this->config['colors'] = "";
		}
		$this->colors = $rankingmodel->getColors($this->config['colors']);

		// Set page title
		if ($this->params->get('what_to_show_first', 0) == 0) {
			$prefix = JText::_('COM_JOOMLEAGUE_RESULTS_PAGE_TITLE').' & ' . JText :: _('COM_JOOMLEAGUE_RANKING_PAGE_TITLE');
			$pageTitleFormat = $resultsconfig["page_title_format"];
		}
		else
		{
			$prefix = JText::_('COM_JOOMLEAGUE_RANKING_PAGE_TITLE').' & ' . JText :: _('COM_JOOMLEAGUE_RESULTS_PAGE_TITLE');
			$pageTitleFormat = $rankingconfig["page_title_format"];
		}
		$titleInfo = JoomleagueHelper::createTitleInfo($prefix);
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
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $pageTitleFormat);
		$document->setTitle($this->pagetitle);
		
		/*
		//build feed links
		$feed = 'index.php?option=com_joomleague&view=results&p='.$this->project->id.'&format=feed';
		$rss = array('type' => 'application/rss+xml', 'title' => JText::_('COM_JOOMLEAGUE_RESULTS_RSSFEED'));

		// add the links
		$document->addHeadLink(JRoute::_($feed.'&type=rss'), 'alternate', 'rel', $rss);
		*/
		JLGView::display($tpl);
	}

	function getRoundSelectNavigation(&$rounds)
	{
		$options = array();
		foreach ($rounds as $r)
		{
			$link = JoomleagueHelperRoute::getResultsRankingRoute($this->project->slug, $r->value);
			$options[] = JHtml::_('select.option', $link, $r->text);
		}
		return $options;
	}

}
