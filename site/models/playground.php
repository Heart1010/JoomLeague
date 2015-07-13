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

class JoomleagueModelPlayground extends JoomleagueModelProject
{
    var $playgroundid = 0;
    var $playground = null;

    function __construct( )
    {
        parent::__construct( );

        $this->projectid = JRequest::getInt( "p", 0 );
        $this->playgroundid = JRequest::getInt( "pgid", 0 );
    }

    function getPlayground( )
    {
        if ( is_null( $this->playground ) )
        {
            $pgid = JRequest::getInt( "pgid", 0 );
            if ( $pgid > 0 )
            {
                $this->playground = & $this->getTable( 'Playground', 'Table' );
                $this->playground->load( $pgid );
            }
        }
        return $this->playground;
    }

    function getAddressString( )
    {
        $playground = $this->getPlayground();
        $address_string = $playground->address.", ".$playground->zipcode ." ".$playground->city;
        return $address_string;
    }

    function getTeams( )
    {
        $teams = array();

        $playground = $this->getPlayground( );
        if ( $playground->id > 0 )
        {
            $query = "SELECT id, team_id, project_id
                      FROM #__joomleague_project_team
                      WHERE standard_playground = ".(int)$playground->id;
            $this->_db->setQuery( $query );
            $rows = $this->_db->loadObjectList();
			
            foreach ( $rows as $row )
            {
                $teams[$row->id]->project_team[] = $row;

                $query = "SELECT name, short_name, notes
                          FROM #__joomleague_team
                          WHERE id=".(int)$row->team_id;
                $this->_db->setQuery( $query );
                $teams[ $row->id ]->teaminfo[] = $this->_db->loadObjectList();

                $query= "SELECT name
                         FROM #__joomleague_project
                         WHERE id=".(int)$row->project_id;
                $this->_db->setQuery( $query );
            	$teams[ $row->id ]->project = $this->_db->loadResult();
            }
        }
        return $teams;
    }

    function getNextGames( $project_id = 0, $bShowReferees = 0 )
    {
        $result = array();

        $playground = $this->getPlayground( );
        if ( $playground->id > 0 )
        {
            $query = "SELECT m.*, DATE_FORMAT(m.time_present, '%H:%i') time_present,
                             p.name AS project_name, p.timezone, tj.team_id team1, tj2.team_id team2
                      FROM #__joomleague_match AS m
                      INNER JOIN #__joomleague_project_team tj ON tj.id = m.projectteam1_id 
                      INNER JOIN #__joomleague_project_team tj2 ON tj2.id = m.projectteam2_id 
                      INNER JOIN #__joomleague_project AS p ON p.id=tj.project_id
                      INNER JOIN #__joomleague_team t ON t.id = tj.team_id
                      INNER JOIN #__joomleague_club c ON c.id = t.club_id
                      WHERE (m.playground_id= " . (int)$playground->id . "
                          OR (tj.standard_playground = " . (int)$playground->id . " AND m.playground_id IS NULL)
                          OR (c.standard_playground = " . (int)$playground->id . " AND m.playground_id IS NULL))
                      AND m.match_date > NOW()
                      AND m.published = 1
                      AND p.published = 1";
            if ( $project_id > 0 )
            {
                $query .= " AND project_id= " . (int) $project_id;
            }
            $query .= " GROUP BY m.id ORDER BY match_date ASC";
            $this->_db->setQuery( $query );
            $matches = $this->_db->loadObjectList();
            if ($matches)
            {
            	foreach ($matches as $match)
            	{
            		JoomleagueHelper::convertMatchDateToTimezone($match);
            	}
            }
            if ($bShowReferees > 0)
            {
            	$project = $this->getProject();
            	$this->_getRefereesByMatch($matches, $project);
            }
        }
        return $matches;
    }

    function getTeamLogo($team_id)
    {
        $query = "
            SELECT c.logo_small,
                   c.country
            FROM #__joomleague_team t
            LEFT JOIN #__joomleague_club c ON c.id = t.club_id
            WHERE t.id = ".$team_id."
        ";
        $this->_db->setQuery( $query );
        $result = $this->_db->loadObjectList();

        return $result;
    }

    function getTeamsFromMatches( & $games )
    {
        $teams = Array();

        if ( !count( $games ) )
        {
            return $teams;
        }

        foreach ( $games as $m )
        {
            $teamsId[] = $m->team1;
            $teamsId[] = $m->team2;
        }
        $listTeamId = implode( ",", array_unique( $teamsId ) );

        $query = "SELECT t.id, t.name
                 FROM #__joomleague_team t
                 WHERE t.id IN (".$listTeamId.")";
        $this->_db->setQuery( $query );
        $result = $this->_db->loadObjectList();

        foreach ( $result as $r )
        {
            $teams[$r->id] = $r;
        }

        return $teams;
    }
    
    function _getRefereesByMatch($matches, $joomleague)
    {
    	for ($index=0; $index < count($matches); $index++) {
    		$referees=array();
    		if ($joomleague->teams_as_referees)
    		{
    			$query="SELECT ref.name AS referee_name
							  FROM #__joomleague_team ref
							  LEFT JOIN #__joomleague_match_referee link ON link.project_referee_id=ref.id
								  WHERE link.match_id=".$matches[$index]->id."
								  ORDER BY link.ordering";
    		}
    		else
    		{
    			$query="SELECT	ref.firstname AS referee_firstname,
											ref.lastname AS referee_lastname,
											ref.id as referee_id,
											ppos.position_id,
											pos.name AS referee_position_name
								FROM #__joomleague_person ref
								LEFT JOIN #__joomleague_project_referee AS pref ON pref.person_id=ref.id
								LEFT JOIN #__joomleague_match_referee link ON link.project_referee_id=pref.id
								INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=link.project_position_id
								INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
								WHERE link.match_id=".$matches[$index]->id."
								  AND ref.published = 1
								  ORDER BY link.ordering";
    		}
    
    		$this->_db->setQuery($query);
    		if (! $referees=$this->_db->loadObjectList())
    		{
    			$this->setError($this->_db->getErrorMsg());
				return false;
    		}
    		$matches[$index]->referees=$referees;
    	}
    	return $matches;
    }
    
}
?>