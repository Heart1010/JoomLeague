<?php
/**
 * Joomleague
 * 
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * HTML View class
 */
class JoomleagueViewSeasons extends JLGView
{
	public function display($tpl=null)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		$uri 	= JFactory::getURI();
		$lists	= array();
		
		$filter_order		= $app->getUserStateFromRequest($this->get('context').'.filter_order',		'filter_order',		's.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->get('context').'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$filter_state		= $app->getUserStateFromRequest($this->get('context').'.filter_state',		'filter_state',		'P',			'word');
		$search				= $app->getUserStateFromRequest($this->get('context').'.search',			'search',			'',				'string');
		$search				= JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state'] = JoomleagueHelper::stateOptions($filter_state);
		
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{ 
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASONS_TITLE'),'jl-seasons');
		JLToolBarHelper::addNew('season.add');
		JLToolBarHelper::editList('season.edit');
		JToolBarHelper::divider();
		JLToolBarHelper::publishList('season.publish');
		JLToolBarHelper::unpublishList('season.unpublish');
		JToolBarHelper::divider();
		JLToolBarHelper::custom('season.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('season.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JToolBarHelper::divider();
		JLToolBarHelper::archiveList('season.archive');
		JLToolBarHelper::trash('season.trash');
		JLToolBarHelper::deleteList('', 'season.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
