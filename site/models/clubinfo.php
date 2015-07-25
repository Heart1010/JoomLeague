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

class JoomleagueModelClubInfo extends JoomleagueModelProject
{
	var $projectid	= 0;
	var $clubid 	= 0;
	var $club 		= null;

	public function __construct( )
	{
		parent::__construct( );

		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$this->projectid = JLHelperFront::stringToInt($jinput->getString('p', 0));
		$this->clubid 	 = JLHelperFront::stringToInt($jinput->getString('cid', 0));
	}

	/**
	 * Club
	 */
	function getClub( )
	{
		if (is_null($this->club))
		{
			if ($this->clubid > 0)
			{
				$db 	= JFactory::getDbo();
				$query	= $db->getQuery(true);
				$query->select('c.*');
				$query->from('#__joomleague_club AS c');
				$query->where('c.id = '.$db->Quote($this->clubid));
				$db->setQuery($query);
				$this->club = $db->loadObject();
			}
		}
		return $this->club;
	}

	function getTeamsByClubId()
	{
		$teams = array(0);
		if ($this->clubid > 0)
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

	
	/**
	 * getStadiums
	 */
	function getStadiums()
	{
		$stadiums = array();

		$club = $this->getClub();
		if (!isset($club))
		{
			return null;
		}
		if ($club->standard_playground > 0)
		{
			$stadiums[] = $club->standard_playground;
		}
		$teams = $this->getTeamsByClubId();

		if (count($teams > 0))
		{
			foreach ($teams AS $team)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('DISTINCT standard_playground');
				$query->from('#__joomleague_project_team');
				$query->where('team_id = '.$team->id);
				$query->where('standard_playground > 0');
				
				if ($club->standard_playground > 0)
				{
					$query->where('standard_playground <> '.$club->standard_playground);
				}
				$db->setQuery($query);
				$result = $db->loadResult();
				
				if ($result)
				{
					$stadiums[] = $res;
				}
			}
		}
		return $stadiums;
	}

	
	/**
	 * Playgrounds
	 */
	function getPlaygrounds( )
	{
		$playgrounds = array();

		$stadiums = $this->getStadiums();
		if (!isset($stadiums))
		{
			return null;
		}

		foreach ($stadiums AS $stadium)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('pl.id AS value, pl.name AS text, pl.*, '
					. ' CASE WHEN CHAR_LENGTH( pl.alias ) THEN CONCAT_WS( \':\', pl.id, pl.alias ) ELSE pl.id END AS slug');
			$query->from('#__joomleague_playground AS pl');
			$query->where('pl.id = '.$db->Quote($stadium));
			$db->setQuery($query, 0, 1);
			$playgrounds[] = $db->loadObject();
		}
		return $playgrounds;
	}

	
	/**
	 * AddressString
	 */
	function getAddressString()
	{
		$club = $this->getClub();
		if (!isset($club)) { 
			return null; 
		}

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
	
	
	/**
	 * HasEditPermission
	 */
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
