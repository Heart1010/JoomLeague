<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model');
require_once JLG_PATH_SITE.'/models/project.php';

class JoomleagueModelStatsRanking extends JoomleagueModelProject
{
	/**
	 * players total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;
	
	var $order = null;
	var $divisionid = 0;
	var $teamid = 0;
	
	public function __construct( )
	{
		parent::__construct( );

		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$this->projectid	= JLHelperFront::stringToInt($jinput->getInt('p',0));
		$this->divisionid	= JLHelperFront::stringToInt($jinput->getInt('division',0));
		$this->teamid		= JLHelperFront::stringToInt($jinput->getInt('tid',0));
		
		$this->setStatid(JRequest::getVar('sid',0));
		
		$config = $this->getTemplateConfig($this->getName());
		// TODO: the default value for limit should be updated when we know if there is more than 1 statistics type to be shown
		if ($this->stat_id != 0)
		{
			$this->limit = JRequest::getInt('limit', $config["max_stats"]);
		}
		else
		{
			$this->limit = JRequest::getInt('limit', $config["count_stats"]);
		}
		$this->limitstart = JRequest::getInt('limitstart',0);
		$this->setOrder(JRequest::getVar('order'));
	}
	
	
	/**
	 * Should be inline with ModelProject
	 * (added $id = false)
	 * @see JoomleagueModelProject::getDivision()
	 */
	function getDivision($id = false)
	{
		$division = null;
		if ($this->divisionid != 0)
		{
			$division = parent::getDivision($this->divisionid);
		}
		return $division;
	}

	function getTeamId()
	{
		return $this->teamid;
	}

	
	/**
	 * set order (asc or desc)
	 * @param string $order
	 * @return string order
	 */
	function setOrder($order)
	{
		if (strcasecmp($order, 'asc') === 0 || strcasecmp($order, 'desc') === 0) {
			$this->order = strtolower($order);
		}
		return $this->order;
	}

	function getLimit( )
	{
		return $this->limit;
	}

	function getLimitStart( )
	{
		return $this->limitstart;
	}

	function setStatid($statid)
	{
		// Allow for multiple statistics IDs, arranged in a single parameters (sid) as string
		// with "|" as separator
		$sidarr = explode("|", $statid);
		$this->stat_id = array();
		foreach ($sidarr as $sid)
		{
			$this->stat_id[] = (int)$sid;	// The cast gets rid of the slug
		}
		// In case 0 was (part of) the sid string, make sure all stat types are loaded)
		if (in_array(0, $this->stat_id))
		{
			$this->stat_id = 0;
		}
	}

	
	/**
	 * return unique stats assigned to project
	 * @see Project#getProjectStats($statid, $positionid)
	 */
	function getProjectUniqueStats()
	{
		$pos_stats = $this->getProjectStats($this->stat_id);
		
		$allstats = array();
		foreach ($pos_stats as $pos => $stats) 
		{
			foreach ($stats as $stat) {
				$allstats[$stat->id] = $stat;
			} 
		}
		return $allstats;
	}
	
	
	function getPlayersStats($order=null)
	{
		$stats = $this->getProjectUniqueStats();
		$order = ($order ? $order : $this->order);
		
		$results = array();
		foreach ($stats as $stat) 
		{
			$results[$stat->id] = $stat->getPlayersRanking($this->projectid, $this->divisionid, $this->teamid, $this->getLimit(), $this->getLimitStart(), $order);
		}
		
		return $results;
	}

}
