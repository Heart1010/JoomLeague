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
class JoomleagueViewTeams extends JLGView
{

	function display($tpl=null)
	{
		$option 	= $this->input->getCmd('option');
		$params		= JComponentHelper::getParams($option);
		$app 		= JFactory::getApplication();
		$uri 		= JFactory::getURI();

		$filter_state		= $app->getUserStateFromRequest($option.'t_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'t_filter_order',		'filter_order',		't.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'t_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'t_search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'t_search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		$lists['search_mode'] = $search_mode;

		$this->user = JFactory::getUser();
		$this->config = JFactory::getConfig();
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->component_params = $params;
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{ 
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TEAMS_TITLE'),'jl-Teams');
		
		JLToolBarHelper::addNew('team.add');
		JLToolBarHelper::editList('team.edit');
		JLToolBarHelper::custom('team.copysave','copy.png','copy_f2.png','COM_JOOMLEAGUE_GLOBAL_COPY',true);
		JLToolBarHelper::custom('team.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('team.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList('', 'team.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
