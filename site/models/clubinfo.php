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

class JoomleagueModelClubInfo extends JoomleagueModelProject
{
	var $projectid = 0;
	var $clubid = 0;
	var $club = null;

	function __construct( )
	{
		parent::__construct( );

		$this->projectid = JRequest::getInt( "p", 0 );
		$this->clubid = JRequest::getInt( "cid", 0 );
	}

	function getClub( )
	{
		if ( is_null( $this->club ) )
		{
			if ( $this->clubid > 0 )
			{
				$query = ' SELECT c.* '
				       . ' FROM #__joomleague_club AS c '
				       . ' WHERE c.id = '. $this->_db->Quote($this->clubid)
				            ;
				$this->_db->setQuery($query);
				$this->club = $this->_db->loadObject();
			}
		}
		return $this->club;
	}

	function getTeamsByClubId()
	{
		$teams = array( 0 );
		if ( $this->clubid > 0 )
		{
			$query = ' SELECT id, '
				     	. ' CASE WHEN CHAR_LENGTH( alias ) THEN CONCAT_WS( \':\', id, alias ) ELSE id END AS slug, '
				       . ' name as team_name, '
				       . ' short_name as team_shortcut, '
				       . ' info as team_description, '
				       . ' (SELECT MAX(project_id) 
				       		FROM #__joomleague_project_team AS pt
				       		RIGHT JOIN #__joomleague_project p on project_id=p.id 
				       		WHERE team_id=t.id and p.published = 1) as pid'
				       . ' FROM #__joomleague_team t'
				       . ' WHERE club_id = '.(int) $this->clubid
				       . ' ORDER BY t.ordering';

			$this->_db->setQuery( $query );
			$teams = $this->_db->loadObjectList();
		}
		return $teams;
	}

	function getStadiums()
	{
		$stadiums = array();

		$club = $this->getClub();
		if ( !isset( $club ) )
		{
			return null;
		}
		if ( $club->standard_playground > 0 )
		{
			$stadiums[] = $club->standard_playground;
		}
		$teams = $this->getTeamsByClubId();

		if ( count( $teams > 0 ) )
		{
			foreach ($teams AS $team )
			{
				$query = ' SELECT distinct(standard_playground) '
				       . ' FROM #__joomleague_project_team '
				       . ' WHERE team_id = '.(int)$team->id
				       . ' AND standard_playground > 0';
				if ( $club->standard_playground > 0 )
				{
					$query .= ' AND standard_playground <> '.$club->standard_playground;
				}
				$this->_db->setQuery($query);
				if ( $res = $this->_db->loadResult() )
				{
					$stadiums[] = $res;
				}
			}
		}
		return $stadiums;
	}

	function getPlaygrounds( )
	{
		$playgrounds = array();

		$stadiums = $this->getStadiums();
		if ( !isset ( $stadiums ) )
		{
			return null;
		}

		foreach ( $stadiums AS $stadium )
		{
			$query = '	SELECT id AS value, name AS text, pl.*, '
    			     . ' CASE WHEN CHAR_LENGTH( pl.alias ) THEN CONCAT_WS( \':\', pl.id, pl.alias ) ELSE pl.id END AS slug '
				     . ' FROM #__joomleague_playground AS pl '
				     . ' WHERE id = '. $this->_db->Quote($stadium)
			            ;
			$this->_db->setQuery($query, 0, 1);
			$playgrounds[] = $this->_db->loadObject();
		}
		return $playgrounds;
	}

	function getAddressString( )
	{
		$club = $this->getClub();
		if ( !isset ( $club ) ) { return null; }

		$address_parts = array();
		if (!empty($club->address))
		{
			$address_parts[] = $club->address;
		}
		if (!empty($club->state))
		{
			$address_parts[] = $club->state;
		}
		if (!empty($club->location))
		{
			if (!empty($club->zipcode))
			{
				$address_parts[] = $club->zipcode. ' ' .$club->location;
			}
			else
			{
				$address_parts[] = $club->location;
			}
		}
		if (!empty($club->country))
		{
			$address_parts[] = Countries::getShortCountryName($club->country);
		}
		$address = implode(', ', $address_parts);
		return $address;
	}
	
	function hasEditPermission($task=null)
	{
		//check for ACL permsission and project admin/editor
		$allowed = parent::hasEditPermission($task);
		$user = JFactory::getUser();
		if ( $user->id > 0 && !$allowed)
		{
			// Check if user is the club admin
			$club = $this->getClub();
			if ( $user->id == $club->admin )
			{
				$allowed = true;
			}
		}
		return $allowed;
	}
}
?>