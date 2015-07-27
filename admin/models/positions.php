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
 * Positions Model
 */
class JoomleagueModelPositions extends JoomleagueModelList
{
	var $_identifier = "positions";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();
		$query='	SELECT	po.*,
							pop.name AS parent_name,
							st.name AS sportstype,
							u.name AS editor,

							(select count(*) FROM #__joomleague_position_eventtype
							WHERE position_id=po.id) countEvents,
							(select count(*) FROM #__joomleague_position_statistic
							WHERE position_id=po.id) countStats

					FROM	#__joomleague_position AS po
					LEFT JOIN #__joomleague_sports_type AS st ON st.id=po.sports_type_id
					LEFT JOIN #__joomleague_position AS pop ON pop.id=po.parent_id
					LEFT JOIN #__users AS u ON u.id=po.checked_out ' .
		$where.$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$app	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		'po.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		if ($filter_order == 'po.ordering')
		{
			$orderby=' ORDER BY po.parent_id ASC,po.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY po.parent_id ASC,'.$filter_order.' '.$filter_order_Dir.',po.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_sports_type	= $app->getUserStateFromRequest($this->context.'.filter_sports_type',	'filter_sports_type','',			'int');
		$filter_state		= $app->getUserStateFromRequest($this->context.'.filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		'po.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($this->context.'.search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);
		
		$where=array();
		if ($filter_sports_type> 0)
		{
			$where[]='po.sports_type_id='.$this->_db->Quote($filter_sports_type);
		}
		if ($search)
		{
			if ($search_mode)
			{
				$where[]='LOWER(po.name) LIKE '.$this->_db->Quote($search.'%');
			}
			else
			{
				$where[]='LOWER(po.name) LIKE '.$this->_db->Quote('%'.$search.'%');
			}
		}
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$where[]='po.published=1';
			}
			elseif ($filter_state == 'U')
			{
				$where[]='po.published=0';
			}
		}
		$where=(count($where) ? ' WHERE '.implode(' AND ',$where) : '');
		return $where;
	}

	/**
	 * Method to return the positions array (id,name) 
	 *
	 * @access	public
	 * @return	array
	 */
	function getParentsPositions()
	{
		$app 		= JFactory::getApplication();
		$jinput 	= $app->input;
		$option 	= $jinput->getCmd('option');
		$project_id = $app->getUserState($option.'project');
		
		// get positions already in project for parents list
		// support only 2 sublevel, so parent must not have parents themselves
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pos.id AS value,pos.name AS text');
		$query->from('#__joomleague_position AS pos');
		$query->where('pos.parent_id = 0');
		$query->order('pos.ordering ASC');
		
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
}
