<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JoomleagueControllerAjax extends JoomleagueController
{
	public function __construct()
	{
		// Get the document object.
		$document = JFactory::getDocument();
		// Set the MIME type for JSON output.
		$document->setMimeEncoding('application/json');
		parent::__construct();
	}
	
	public function getprojectsoptions()
	{
		$app = JFactory::getApplication();
		
		$season = Jrequest::getInt('s');
		$league = Jrequest::getInt('l');
		$ordering = Jrequest::getInt('o');
		
		$model = $this->getModel('ajax');
		
		$res = $model->getProjectsOptions($season, $league, $ordering);
		
		echo json_encode($res);
		
		$app->close();
	}
	
	public function getroute()
	{
		$view = Jrequest::getCmd('view');
	
		switch ($view)
		{
			case "matrix":
				$link = JoomleagueHelperRoute::getMatrixRoute( JRequest::getVar('p'), JRequest::getVar('division'), JRequest::getVar('r') );
				break;
				
			case "teaminfo":
				$link = JoomleagueHelperRoute::getTeamInfoRoute( JRequest::getVar('p'), JRequest::getVar('tid') );
				break;
				
			case "referees":
				$link = JoomleagueHelperRoute::getRefereesRoute( JRequest::getVar('p') );
				break;
				
			case "results":
				$link = JoomleagueHelperRoute::getResultsRoute( JRequest::getVar('p'), JRequest::getVar('r'), JRequest::getVar('division') );
				break;
				
			case "resultsranking":
				$link = JoomleagueHelperRoute::getResultsRankingRoute( JRequest::getVar('p') );
				break;
				
			case "rankingmatrix":
				$link = JoomleagueHelperRoute::getRankingMatrixRoute( JRequest::getVar('p'), JRequest::getVar('r'), JRequest::getVar('division') );
				break;
				
			case "resultsrankingmatrix":
				$link = JoomleagueHelperRoute::getResultsRankingMatrixRoute( JRequest::getVar('p'), JRequest::getVar('r'), JRequest::getVar('division') );
				break;
				
			case "teamplan":
				$link = JoomleagueHelperRoute::getTeamPlanRoute( JRequest::getVar('p'), JRequest::getVar('tid'), JRequest::getVar('division') );
				break;
				
			case "roster":
				$link = JoomleagueHelperRoute::getPlayersRoute( JRequest::getVar('p'), JRequest::getVar('tid'), null, JRequest::getVar('division') );
				break;
				
			case "eventsranking":				
				$link = JoomleagueHelperRoute::getEventsRankingRoute( JRequest::getVar('p'), JRequest::getVar('division'),JRequest::getVar('tid') );
				break;
				
			case "curve":
				$link = JoomleagueHelperRoute::getCurveRoute( JRequest::getVar('p'),JRequest::getVar('tid'),0, JRequest::getVar('division') );
				break;
				
			case "statsranking":
				$link = JoomleagueHelperRoute::getStatsRankingRoute( JRequest::getVar('p'), JRequest::getVar('division') );
				break;
								
			default:
			case "ranking":
				$link = JoomleagueHelperRoute::getRankingRoute( JRequest::getVar('p'),JRequest::getVar('r'),null,null,0,JRequest::getVar('division') );
		}
		
		echo json_encode($link);
	}
}