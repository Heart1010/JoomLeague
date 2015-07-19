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
 * Statistics Model
 */
class JoomleagueModelStatistics extends JoomleagueModelList
{
	var $_identifier = "statistics";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = "	SELECT	obj.*,
							st.name AS sportstype,		
							u.name AS editor
					FROM #__joomleague_statistic AS obj
					LEFT JOIN #__joomleague_sports_type AS st ON st.id=obj.sports_type_id					
					LEFT JOIN #__users AS u ON u.id = obj.checked_out " .
					$where . $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app	= JFactory::getApplication();

		$filter_order		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		
		if ( $filter_order == 'obj.ordering')
		{
			$orderby 	= ' ORDER BY obj.ordering ' . $filter_order_Dir;
		} else
		{
			$orderby 	= ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ' , obj.ordering ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_sports_type	= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_sports_type',	'filter_sports_type','',	'int');
		$filter_state		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.filter_state',		'filter_state',		'',				'word');
		$search				= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'.'.$this->_identifier.'.search_mode',		'search_mode',		'',				'string');
		$search=JString::strtolower($search);
		
		$where = array();
		if ($filter_sports_type > 0)
		{
			$where[]='obj.sports_type_id='.$this->_db->Quote($filter_sports_type);
		}
		
		if ( $search )
		{
			$where[] = 'LOWER(obj.name) LIKE ' . $this->_db->Quote('%' . $search . '%');
		}
		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'obj.published = 1';
			}
			elseif ( $filter_state == 'U' )
			{
				$where[] = 'obj.published = 0';
			}
		}

		$where	= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

}
