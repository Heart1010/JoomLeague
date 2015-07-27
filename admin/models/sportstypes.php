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
		$app 	= JFactory::getApplication();
		$jinput	= $app->input;
		$option = $jinput->getCmd('option');
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter_order',		'filter_order',		's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->context.'.search',			'search',			'',				'string');
		$search				= JString::strtolower($search);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('s.*');
		$query->from('#__joomleague_sports_type AS s');
		
		// users
		$query->select('u.name AS editor');
		$query->join('LEFT', '#__users AS u ON u.id = s.checked_out');
		
		// Where
		if ($search)
		{
			$query->where('LOWER(s.name) LIKE '.$db->Quote('%'.$search.'%'));
		}
		
		// Order
		if ($filter_order == 's.ordering')
		{
			$query->order('s.ordering '.$filter_order_Dir);
		}
		else
		{
			$query->order($filter_order.' '.$filter_order_Dir,'s.ordering');
		}
		
		return $query;
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
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__joomleague_sports_type');
		$query->order('name ASC');
		$db->setQuery($query);
		if ($db->getErrorNum())
		{
			return array();
		}
		if (!$result = $db->loadObjectList())
		{
			return array();
		}
		foreach ($result as $sportstype){
			$sportstype->name = JText::_($sportstype->name);
		}
		return $result;
	}
}
