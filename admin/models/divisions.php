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
		$app 		= JFactory::getApplication();
		$jinput 	= $app->input;
		$option 	= $jinput->getCmd('option');
		$project_id = $app->getUserState($option.'project');
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		'dv.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',			'',				'string');
		$search				= JString::strtolower( $search );
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('dv.*');
		$query->from('#__joomleague_division AS dv');
		
		// Division
		$query->select('dvp.name AS parent_name');
		$query->join('LEFT', '#__joomleague_division AS dvp ON dvp.id = dv.parent_id');
		
		// Users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = dv.checked_out');
		
		// Where
		$query->where('dv.project_id = '.$project_id);
		if ($search)
		{
			$query->where('LOWER(dv.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
		
		// Order
		if ($filter_order == 'dv.ordering')
		{
			$query->order('dv.ordering ' . $filter_order_Dir);
		}
		else
		{
			$query->order($filter_order . ' '.$filter_order_Dir, 'dv.ordering ');
		}

		return $query;
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value,name AS text');
		$query->from('#__joomleague_division');
		$query->where('project_id=' . $project_id);
		$query->order('name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList("value"))
		{
			$this->setError($db->getErrorMsg());
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(d.id) AS count');
		$query->from('#__joomleague_division AS d');
		$query->join('LEFT', '#__joomleague_project AS p on p.id = d.project_id');
		$query->where('p.id = '.$project_id);
		$db->setQuery($query);
		return $db->loadResult();
	}
}
