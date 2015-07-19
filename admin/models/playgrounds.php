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
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();
		$query='	SELECT v.*,c.name As club,u.name AS editor
					FROM #__joomleague_playground AS v
					LEFT JOIN #__joomleague_club AS c ON c.id=v.club_id
					LEFT JOIN #__users AS u ON u.id=v.checked_out '
					. $where
					. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'v_filter_order','filter_order','v.ordering','cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'v_filter_order_Dir','filter_order_Dir','','word');
		if ($filter_order == 'v.ordering')
		{
			$orderby=' ORDER BY v.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',v.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'v_filter_order',		'filter_order',		'v.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'v_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'v_search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'v_search_mode',		'search_mode',		'',				'string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			if($search_mode)
			{
				$where[]='LOWER(v.name) LIKE '.$this->_db->Quote($search.'%');
			}
			else
			{
				$where[]='LOWER(v.name) LIKE '.$this->_db->Quote('%'.$search.'%');
			}
		}
		$where=(count($where) ? ' WHERE '. implode(' AND ',$where) : '');
		return $where;
	}
}
