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
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();
		$query='	SELECT a.*,u.name AS editor
					FROM #__joomleague_club AS a
					LEFT JOIN #__users AS u ON u.id=a.checked_out '
					. $where
					. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'a_filter_order','filter_order','a.ordering','cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'a_filter_order_Dir','filter_order_Dir','','word');
		if ($filter_order == 'a.ordering')
		{
			$orderby=' ORDER BY a.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_state		= $app->getUserStateFromRequest($option.'a_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'a_filter_order',		'filter_order',		'a.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'a_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'a_search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'a_search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);
		$where=array();
		if ($search)
		{
			if($search_mode)
			{
				$where[]='LOWER(a.name) LIKE '.$this->_db->Quote($search.'%');
			}
			else
			{
				$where[]='LOWER(a.name) LIKE '.$this->_db->Quote('%'.$search.'%');
			}
		}
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$where[]='a.published=1';
			}
			elseif ($filter_state == 'U')
			{
				$where[]='a.published=0';
			}
		}
		$where=(count($where) ? ' WHERE '. implode(' AND ',$where) : '');
		return $where;
	}
}
