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
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();

		$query='	SELECT	s.*,
							u.name AS editor
					FROM #__joomleague_season AS s
					LEFT JOIN #__users AS u ON u.id=s.checked_out ' .
					$where .
					$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'s_filter_order',		'filter_order',			's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'s_filter_order_Dir',	'filter_order_Dir',		'',				'word');
		
		if ($filter_order=='s.ordering')
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

		$filter_order		= $app->getUserStateFromRequest($option.'s_filter_order',		'filter_order',			's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'s_filter_order_Dir',	'filter_order_Dir',		'',				'word');
		$filter_state		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_state',		'filter_state',	'P',	'word');
		$search				= $app->getUserStateFromRequest($option.'s_search',				'search',				'',				'string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			$where[]='LOWER(s.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 's.published = 1';
			}
			elseif ($filter_state == 'U' )
			{
				$where[] = 's.published = 0';
			}
			elseif ($filter_state == 'A' )
			{
				$where[] = 's.published = 2';
			}
			elseif ($filter_state == 'T' )
			{
				$where[] = 's.published = -2';
			}
		}
		
		$where=(count($where) ? ' WHERE '.implode(' AND ',$where) : '');
		
		return $where;
	}

	/**
	* Method to return a season array (id, name)
	*
	* @access	public
	* @return	array seasons
	*/
	function getSeasons()
	{
		$query = 'SELECT s.id, s.name 
					FROM #__joomleague_season AS s 
					where s.published=1 
					ORDER BY name ASC ';
		$this->_db->setQuery($query);
		if (!$result = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}
}
