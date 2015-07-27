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
 * Eventtypes Model
 */
class JoomleagueModelEventtypes extends JoomleagueModelList
{
	var $_identifier = "eventtypes";
	
	function _buildQuery()
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$filter_sports_type	= $app->getUserStateFromRequest($this->context.'.filter_sports_type',	'filter_sports_type','',	'int');
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state',		'filter_state',		'',				'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($this->context.'.search_mode',		'search_mode',		'',				'string');
		$search 			= JString::strtolower($search);
		
		// Query
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->from('#__joomleague_eventtype AS obj');
		
		// Sportstype
		$query->select('st.name AS sportstype');
		$query->join('LEFT', '#__joomleague_sports_type AS st ON st.id = obj.sports_type_id');
		
		// User
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = obj.checked_out');
		
		// Published
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$query->where('obj.published = 1');
			}
			elseif ($filter_state == 'U')
			{
				$query->where('obj.published = 0');
			}
		}
		
		// Where
		if ($filter_sports_type > 0)
		{
			$query->where('obj.sports_type_id = '.$filter_sports_type);
		}
		if ($search)
		{
			$query->where('LOWER(obj.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
	
		// Ordering
		if ($filter_order == 'obj.ordering')
		{
			$query->order('obj.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'obj.ordering');
		}
		
		return $query;
	}	
		
}
