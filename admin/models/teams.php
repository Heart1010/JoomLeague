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
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT c.name as clubname, c.id AS club_id, t.*, u.name AS editor '
				. ' FROM #__joomleague_team AS t '
				. ' LEFT JOIN #__joomleague_club AS c '
				. ' ON t.club_id = c.id'
				. ' LEFT JOIN #__users AS u ON u.id = t.checked_out '
				. $where
				. $orderby
		;
		return $query;
	}
	

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app	= JFactory::getApplication();

		$filter_order		= $app->getUserStateFromRequest($option.'t_filter_order','filter_order','t.ordering','cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'t_filter_order_Dir','filter_order_Dir','','word');

		if ($filter_order == 't.ordering'){
			$orderby 	= ' ORDER BY t.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , t.ordering ';
		}

		return $orderby;
	}
	
	
	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app	= JFactory::getApplication();

		$filter_state		= $app->getUserStateFromRequest( $option.'t_filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $app->getUserStateFromRequest( $option.'t_filter_order',		'filter_order',		't.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $option.'t_filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $app->getUserStateFromRequest( $option.'t_search',			'search',			'',				'string' );
		$search_mode		= $app->getUserStateFromRequest( $option.'t_search_mode',		'search_mode',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		if ($search) {
			if($search_mode)
				$where[] = 'LOWER(t.name) LIKE '.$this->_db->Quote($search.'%');
			else
				$where[] = 'LOWER(t.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}

                if ($cid    =   JRequest::getvar('cid', 0, 'GET', 'INT')) {
                    $where[] = 'club_id ='. $cid;
                }

                $where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
}
