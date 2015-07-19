<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JLG_PATH_ADMIN.'/models/list.php';

/**
 * Divisions Model
 */
class JoomleagueModelDivisions extends JoomleagueModelList
{
	var $_identifier = "divisions";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = '	SELECT	dv.*,
							dvp.name AS parent_name,
							u.name AS editor
					FROM #__joomleague_division AS dv
					LEFT JOIN #__joomleague_division AS dvp ON dvp.id = dv.parent_id
					LEFT JOIN #__users AS u ON u.id = dv.checked_out ' .
					$where . $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');

		$app	= JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $option . 'dv_filter_order',		'filter_order',		'dv.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $option . 'dv_filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ( $filter_order == 'dv.ordering' )
		{
			$orderby 	= ' ORDER BY dv.ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderby 	= ' ORDER BY ' . $filter_order . ' '.$filter_order_Dir . ' , dv.ordering ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');

 		$app	= JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
		$where = array();

		$where[]	= ' dv.project_id = ' . $project_id;

		$filter_order		= $app->getUserStateFromRequest($option.'dv_filter_order',		'filter_order',		'dv.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'dv_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'dv_search',			'search',			'',				'string');
		$search				= JString::strtolower( $search );

		if ($search)
		{
			$where[] = 'LOWER(dv.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}


		$where = (count($where) ? ' WHERE '.implode(' AND ',$where) : '');

		return $where;
	}
	
	/**
	* Method to return a divisions array (id, name)
	*
	* @param int $project_id
	* @access  public
	* @return  array
	*/
	function getDivisions($project_id)
	{
		$query = '	SELECT	id AS value,
					name AS text
					FROM #__joomleague_division
					WHERE project_id=' . $project_id .
					' ORDER BY name ASC ';

		$this->_db->setQuery( $query );
		if ( !$result = $this->_db->loadObjectList("value") )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return array();
		}
		else
		{
			return $result;
		}
		
	}
	
	/**
	 * return count of project divisions
	 *
	 * @param int project_id
	 * @return int
	 */
	function getProjectDivisionsCount($project_id)
	{
		$query='SELECT count(*) AS count
		FROM #__joomleague_division AS d
		JOIN #__joomleague_project AS p on p.id = d.project_id
		WHERE p.id='.$project_id;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
}
