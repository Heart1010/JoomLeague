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

jimport( 'joomla.application.component.model' );

require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

class JoomleagueModelTeams extends JoomleagueModelProject
{
	var $projectid = 0;
	var $divisionid = 0;
	var $teamid = 0;
	var $team = null;
	var $club = null;

	function __construct( )
	{
		$this->projectid = JRequest::getInt( "p", 0 );
		$this->divisionid = JRequest::getInt( "division", 0 );

		parent::__construct( );
	}

	function getDivision()
	{
		$division = null;
		if ($this->divisionid != 0)
		{
			$division = parent::getDivision($this->divisionid);
		}
		return $division;
	}

	function getTeams()
	{
		$teams = array();

		$query = "SELECT
                    pt.id AS projectteamid,
                    pt.team_id,
                    pt.picture projectteam_picture,
                    pt.project_id,
                    t.id,
                    t.name as team_name,
                    t.short_name,
                    t.middle_name,
                    t.club_id,
                    t.website AS team_www,
                    t.picture team_picture,
                    c.name as club_name,
                    c.address as club_address,
                    c.zipcode as club_zipcode,
                    c.state as club_state,
                    c.location as club_location,
                    c.email as club_email,
                    c.logo_big,
                    c.logo_small,
                    c.logo_middle,
                    c.country as club_country,
                    c.website AS club_www,
				    CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( ':', t.id, t.alias ) ELSE t.id END AS team_slug,
				    CASE WHEN CHAR_LENGTH( c.alias ) THEN CONCAT_WS( ':', c.id, c.alias ) ELSE c.id END AS club_slug
                  FROM #__joomleague_project_team pt
                  LEFT JOIN #__joomleague_team t ON pt.team_id = t.id
                  LEFT JOIN #__joomleague_club c ON t.club_id = c.id
                  LEFT JOIN #__joomleague_division d ON d.id = pt.division_id
                  LEFT JOIN #__joomleague_playground plg ON plg.id = pt.standard_playground
                  WHERE pt.project_id = " . (int)$this->projectid;

		if ( $this->divisionid > 0 )
		{
			$query .= " AND pt.division_id = " . $this->divisionid;
		}
		$query .= " ORDER BY t.name";

		$this->_db->setQuery($query);
		if ( ! $teams = $this->_db->loadObjectList() )
		{
			echo $this->_db->getErrorMsg();
		}

		return $teams;
	}

}
?>