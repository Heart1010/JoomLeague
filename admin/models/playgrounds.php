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
require_once JPATH_COMPONENT.'/models/list.php';

/**
 * Playgrounds Model
 */
class JoomleagueModelPlaygrounds extends JoomleagueModelList
{
	var $_identifier = "playgrounds";
	
	function _buildQuery()
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $this->input->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order',		'filter_order',		'v.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('v.*');
		$query->from('#__joomleague_playground AS v');
		
		// Club
		$query->select('c.name As club');
		$query->join('LEFT', '#__joomleague_club AS c ON c.id=v.club_id');
		
		// Users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id=v.checked_out');
		
		// Where
		if ($search)
		{
			if($search_mode)
			{
				$query->where('LOWER(v.name) LIKE '.$db->Quote($search.'%'));
			}
			else
			{
				$query->where('LOWER(v.name) LIKE '.$db->Quote('%'.$search.'%'));
			}
		}
		
		// Orderby
		if ($filter_order == 'v.ordering')
		{
			$query->order('v.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'v.ordering ');
		}
		
		return $query;
	}
}
