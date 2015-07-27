<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/models/list.php';

/**
 * Seasons Model
 */
class JoomleagueModelSeasons extends JoomleagueModelList
{
	var $_identifier = "seasons";
	
	function _buildQuery()
	{
		$app 	= JFactory::getApplication();
		$jinput	= $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',			's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',		'ASC',				'word');
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state',		'filter_state',			'P',	'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',				'',				'string');
		$search				= JString::strtolower($search);
		
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('s.*');
		$query->from('#__joomleague_season AS s');
		
		// join users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id=s.checked_out');
		
		// Search
		if ($search)
		{
			$query->where('LOWER(s.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
		
		// Filter-State
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$query->where('s.published = 1');
			}
			elseif ($filter_state == 'U')
			{
				$query->where('s.published = 0');
			}
			elseif ($filter_state == 'A')
			{
				$query->where('s.published = 2');
			}
			elseif ($filter_state == 'T')
			{
				$query->where('s.published = -2');
			}
		}
		
		// Order
		if ($filter_order=='s.ordering')
		{
			$query->order('s.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'s.ordering ');
		}
	
		
		return $query;
	}


	/**
	* Method to return a season array (id, name)
	*
	* @access	public
	* @return	array seasons
	*/
	function getSeasons()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('s.id,s.name');
		$query->from('#__joomleague_season AS s');
		$query->where('s.published = 1');
		$query->order('s.name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
}
