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
 * Leagues Model
 */
class JoomleagueModelLeagues extends JoomleagueModelList
{
	var $_identifier = "leagues";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();

		$query='	SELECT	obj.*,
							u.name AS editor
					FROM #__joomleague_league AS obj
					LEFT JOIN #__users AS u ON u.id=obj.checked_out ' .
					$where .
					$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'l_filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'l_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		if ($filter_order == 'obj.ordering')
		{
			$orderby=' ORDER BY obj.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',obj.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'l_filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'l_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'l_search',				'search',			'',				'string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			$where[]='LOWER(obj.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		$where=(count($where) ? ' WHERE '.implode(' AND ',$where) : '');
		return $where;
	}

	/**
	 * Method to return a leagues array (id,name)
	 *
	 * @access	public
	 * @return	array seasons
	 */
	function getLeagues()
	{
		$db = JFactory::getDbo();
		$query='SELECT id, name FROM #__joomleague_league ORDER BY name ASC ';
		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return array();
		}
		foreach ($result as $league){
			$league->name = JText::_($league->name); 
		}
		return $result;
	}
}
