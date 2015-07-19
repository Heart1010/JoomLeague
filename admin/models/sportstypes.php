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
 * Sportstypes Model
 */
class JoomleagueModelSportsTypes extends JoomleagueModelList
{
	var $_identifier = "sportstypes";

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();

		$query='	SELECT	s.*,
							u.name AS editor
					FROM #__joomleague_sports_type AS s
					LEFT JOIN #__users AS u ON u.id=s.checked_out ' .
		$where .
		$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{	
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'s_filter_order',		'filter_order',		's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'s_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		if ($filter_order == 's.ordering')
		{
			$orderby=' ORDER BY s.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',s.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'s_filter_order',		'filter_order',		's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'s_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'s_search',				'search',			'',				'string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			$where[]='LOWER(s.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		$where=(count($where) ? ' WHERE '.implode(' AND ',$where) : '');
		return $where;
	}

	/**
	 * Method to return a sportsTypes array (id,name)
	 *
	 * @access	public
	 * @return	array
	 */
	public static function getSportsTypes()
	{
		$db = JFactory::getDbo();
		$query='SELECT id, name FROM #__joomleague_sports_type ORDER BY name ASC ';
		$db->setQuery($query);
		// Check for database errors.
		if ($db->getErrorNum())
		{
			return array();
		}
		if (!$result=$db->loadObjectList())
		{
			return array();
		}
		foreach ($result as $sportstype){
			$sportstype->name=JText::_($sportstype->name);
		}
		return $result;
	}
}
