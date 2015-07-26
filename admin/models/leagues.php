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
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($option.'l_filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'l_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'l_search',				'search',			'',				'string');
		$search				= JString::strtolower($search);
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->from('#__joomleague_league AS obj');
		
		// users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id=obj.checked_out');
		
		// Where
		if ($search)
		{
			$query->where('LOWER(obj.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
		
		// Order
		if ($filter_order == 'obj.ordering')
		{
			$query->order('obj.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'obj.ordering ');
		}
		
		return $query;
	}


	/**
	 * Method to return a leagues array (id,name)
	 *
	 * @access	public
	 * @return	array seasons
	 */
	function getLeagues()
	{
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__joomleague_league');
		$query->order('name ASC');
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
