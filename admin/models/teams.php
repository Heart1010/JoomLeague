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
 * Teams Model
 */
class JoomleagueModelTeams extends JoomleagueModelList
{
	var $_identifier = "teams";
	
	function _buildQuery()
	{
		$app	= JFactory::getApplication();
		$jinput	= $app->input;
		$option = $jinput->getCmd('option');
		$cid    = JRequest::getvar('cid', 0, 'GET', 'INT');
		
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		't.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',			'',				'string' );
		$search_mode		= $app->getUserStateFromRequest($this->context.'.search_mode',		'search_mode',			'',				'string' );
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('t.*');
		$query->from('#__joomleague_team AS t');
		
		$query->select('c.name as clubname, c.id AS club_id');
		$query->join('LEFT', '#__joomleague_club AS c ON t.club_id = c.id');
		
		$query->select('u.name AS editor');
		$query->join('LEFT','#__users AS u ON u.id = t.checked_out');
		
		// Where
		if ($search) {
			if($search_mode)
				$query->where('LOWER(t.name) LIKE '.$db->Quote($search.'%'));
			else
				$query->where('LOWER(t.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
		
		if ($cid) {
			$query->where('club_id ='. $cid);
		}
		
		
		// Order
		if ($filter_order == 't.ordering'){
			$query->order('t.ordering '.$filter_order_Dir);
		} else {
			$query->order($filter_order.' '.$filter_order_Dir,'t.ordering ');
		}
	
		return $query;
	}
}
