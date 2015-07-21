<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JLG_PATH_SITE.'/models/project.php';

class JoomleagueModelMatrix extends JoomleagueModelProject
{
	public function __construct( )
	{
		parent::__construct( );

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$this->divisionid	= $jinput->getInt('division',0);
		$this->roundid		= $jinput->getInt('r',0);
	}

	function getDivisionID( )
	{
		return $this->divisionid;
	}

	function getRoundID( )
	{
		return $this->roundid;
	}

	function getDivision( )
	{
		$division = null;
		if ( $this->divisionid > 0 )
		{
			$division = $this->getTable("Division", "Table");
			$division->load( $this->divisionid );
		}
		return $division;
	}

	function getRound( )
	{
		$round = null;
		if ( $this->roundid > 0 )
		{
			$round = $this->getTable("Round","Table");
			$round->load( $this->roundid );
		}
		return $round;
	}

	/**
	 * Returns rows of games info for matrix display
	 *
	 * @param Joomleague $project
	 * @param int $division
	 * @param string $unpublished
	 * @return array rows objects
	 */
	function getMatrixResults( $project_id, $unpublished = 0 )
	{
		$query_WHERE = "";
		$query_END	 = " ORDER BY r.ordering, r.roundcode";
		$query_SELECT = "SELECT DISTINCT(m.id), r.name AS roundname,
												r.id AS roundid,
												r.roundcode,
												m.show_report,
												m.cancel,
												m.cancel_reason,
												m.projectteam1_id,
												m.projectteam2_id,
												m.team1_result as e1,
												m.team2_result as e2,
												m.match_result_type as rtype,
												m.alt_decision as decision,
												m.team1_result_decision AS v1,
												m.team2_result_decision AS v2,
												m.new_match_id, m.old_match_id,
												tt1.division_id AS division_id";
		$query_FROM	= " FROM #__joomleague_match AS m
						INNER JOIN #__joomleague_round AS r ON r.id=m.round_id
						LEFT JOIN #__joomleague_project_team AS tt1 ON m.projectteam1_id = tt1.team_id
						LEFT JOIN #__joomleague_project_team AS tt2 ON m.projectteam2_id = tt2.team_id ";
		if ( $this->divisionid > 0 )
		{
			$query_FROM.= "	LEFT JOIN #__joomleague_division AS d1 ON tt1.division_id = d1.id
							LEFT JOIN #__joomleague_division AS d2 ON tt2.division_id = d2.id";
			if ( $this->divisionid > 0 )
			{
				$query_FROM .= " AND (	d1.id = ".$this->_db->Quote($this->divisionid)." OR d1.parent_id = " . $this->_db->Quote($this->divisionid) . "
									OR d2.id = " . $this->_db->Quote($this->divisionid) . " OR d2.parent_id = " . $this->_db->Quote($this->divisionid) . " )";
			}
		}
		$query_WHERE = " WHERE r.project_id = ".$project_id;
		if ( $this->roundid > 0 )
		{
			$query_WHERE .= " AND m.round_id = " . $this->_db->Quote($this->roundid);
		}
		if ( $unpublished != 1 )
		{
			$query_WHERE .=" AND m.published = 1";
		}
		$query = $query_SELECT . $query_FROM . $query_WHERE . $query_END ;
		$this->_db->setQuery( $query );
		if ( !$result = $this->_db->loadObjectList() )
		{
			echo $this->_db->getErrorMsg();
		}
		return $result;
	}
}
