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
 * Clubs Model
 */
class JoomleagueModelClubs extends JoomleagueModelList
{
	
	var $_identifier = "clubs";
	
	function _buildQuery()
	{
		$app	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		'a.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($this->context.'.search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__joomleague_club AS a');
		
		// Users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = a.checked_out');
		
		
		// Where
		if ($search)
		{
			if($search_mode)
			{
				$query->where('LOWER(a.name) LIKE '.$db->Quote($search.'%'));
			}
			else
			{
				$query->where('LOWER(a.name) LIKE '.$db->Quote('%'.$search.'%'));
			}
		}
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$query->where('a.published=1');
			}
			elseif ($filter_state == 'U')
			{
				$query->where('a.published = 0');
			}
		}
		
		// Orderby
		if ($filter_order == 'a.ordering')
		{
			$query->order('a.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'a.ordering ');
		}
		
		
		return $query;
	}
}
