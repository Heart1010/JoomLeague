<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

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
		
		// Use the correct json mime-type
		header('Content-Type: application/json');
		
		// Send the response.
		echo json_encode($res);
		JFactory::getApplication()->close();
		
		// echo json_encode($res);
		// $app->close();
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
		
		// echo json_encode($link);
		
		// Use the correct json mime-type
		header('Content-Type: application/json');
		
		// Send the response.
		echo json_encode($link);
		JFactory::getApplication()->close();
		
	}
}